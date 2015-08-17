<?php

class currency {

    private $CI;

    public function __construct() {

        $this->CI = get_instance();

        $this->update_exchange_rates();
    }

    public function convert($value, $from_currency, $to_currency) {
        $result = $value * ( $this->exchange_rates['rates'][$to_currency] * ( 1 / $this->exchange_rates['rates'][$from_currency] ) );
        return $result;
    }

    public function update_exchange_rates() {
        $this->CI->load->library('curl');

        $json = $this->CI->curl->simple_get('http://openexchangerates.org/api/latest.json?app_id=289e4de0fa9c4549a28988637302f251');
        $json = json_decode($json, true);
        if (isset($json['rates'])) {
            $this->exchange_rates = $json;
        } else {
            log_message('error', 'Exchange rates cannot be pulled from "http://openexchangerates.org/latest.json" (' . $this->CI->curl->info['http_code'] . ')');
        }
    }


}
