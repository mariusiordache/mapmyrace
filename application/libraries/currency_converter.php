<?php

class currency_converter {

    private $CI;
    private $exchange_rates = false;
    private $default_currency = 'EUR';

    public function __construct() {

        $this->CI = get_instance();
        $this->CI->load->driver('cache');

        if (extension_loaded('apc')) {
            /* load exchange rates from apc cache */
            $this->exchange_rates = $this->CI->cache->apc->get('exchange_rates');
        }
        if ($this->exchange_rates === false) {
            $this->build_rates();
            if (extension_loaded('apc')) {
                $this->CI->cache->apc->save('exchange_rates', $this->exchange_rates, 12 * 3600);
            }
        }
    }

    /**
     * Convert one currency to another
     */
    public function convert($from, $to, $amount, $date = null) {
        $multiply = 1;

        if ($date && !isset($this->exchange_rates[$date])) {
            
            // exact date does not match, lookup previous date
            $dates = array_keys($this->exchange_rates);
            $d = strtotime($date);
            $new_date = null;
            
            for($i=count($dates) - 1; $i>=0; $i--) {
                if (strtotime($dates[$i]) > $d) {
                    if (!$i) {
                        throw new Exception("No currency rates for {$date}.");
                    } else {
                        $new_date = $dates[$i+1];
                        break;
                    }
                }
            }
            
            $date = !$new_date ?  $dates[0] : $new_date;
            
        }

        $rates = !empty($date) ? $this->exchange_rates[$date] : array_shift(array_values($this->exchange_rates));
        
        // replace EUR sign
        $from = str_replace(chr(0XE2) . chr(0X82) . chr(0XAC), "EUR", $from);
        $from = str_replace("$", "USD", $from);

        if ($from != $this->default_currency) {
            if (!isset($rates[$from])) {
                throw new Exception("Unknown currency {$from}");
            }

            $multiply = $rates[$from];
        }

        if (!isset($rates[$to])) {
            throw new Exception("Unknown currency {$to}");
        }

        $rate = $rates[$to];
        return $amount * $rate / $multiply;
    }

    private function build_rates() {
        $eu_exchange_url = 'http://www.ecb.europa.eu/stats/eurofxref/eurofxref-hist.xml';

        $cache_file = $this->CI->config->item('webroot_path') . '/cache/' . date('Ymd') . md5($eu_exchange_url);

        if (!file_exists($cache_file) || time() - filemtime($cache_file) > 12 * 3600) {
            $this->CI->load->library('Curl');
            $this->CI->curl->http_header('Content-type', 'text/html; charset=utf-8');
            $content = $this->CI->curl->simple_get($eu_exchange_url);
            if ($this->CI->curl->info['http_code'] != 404) {
                file_put_contents($cache_file, $content);
            }
        }

        if (file_exists($cache_file)) {
            $this->exchange_rates = array();

            $xml = simplexml_load_file($cache_file);
            $old_date = null;
            
            foreach ($xml->Cube->Cube as $item) {

                $date = (string) $item['time'];
                $this->exchange_rates[$date]['EUR'] = 1;

                if ($old_date) {
                    for($xx = strtotime($old_date) - 86400; $xx > strtotime($date); $xx-=86400) {
                        $this->exchange_rates[date('Y-m-d', $xx)] = $this->exchange_rates[$old_date];
                    }
                }
                
                foreach ($item as $row) {
                    $row = (array) $row;
                    $this->exchange_rates[$date][$row['@attributes']['currency']] = $row['@attributes']['rate'];
                }
                
                $old_date = $date;
            }
            
            return $this->exchange_rates;
        }
    }

}
