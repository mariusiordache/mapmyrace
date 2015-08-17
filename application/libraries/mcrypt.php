<?php

class mcrypt {

    private $h = null;
    
    public function setFileHandle($h) {
        $this->h = $h;
    }
    
    protected function log($str) {
        if ($this->h !== null) {
            fwrite($this->h, $str . "\n");
        }
    }

    function decrypt($hexkey, $code) {
        $s = microtime(true);

        $this->key = hex2bin($hexkey);
        $code = hex2bin($code);

        // get IV
        $iv = substr($code, 16, 16);

        // get string
        $code = substr($code, 32);
        $this->log($code);

        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, $iv);

        mcrypt_generic_init($td, $this->key, $iv);
        $decrypted = mdecrypt_generic($td, $code);

        
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        $string = utf8_encode(trim($decrypted));
        
        $this->log($string);

        // remove pad
        $pad = ord($string[strlen($string) - 1]);
        $length = strlen($string);
        
        $this->log("Pad " . $pad);
        
        for($i=1; $i<=$pad; $i++) {
            if (ord($string[$length - $i]) != ($pad - $i + 1)) {
                $pad = 0;
                break;
            }
        }
        
        if ($pad) {
            $string = substr($string, 0, -1 * $pad);
        }

        $e = microtime(true);

        return $string;
    }

    function encrypt($hexkey, $str) {

        $this->key = hex2bin($hexkey);
        $code = substr(md5(time()), 0, 16);

        $size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CFB);
        $iv = mcrypt_create_iv($size, MCRYPT_DEV_RANDOM);

        $code .= $iv;

        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, $iv);

        mcrypt_generic_init($td, $this->key, $iv);

        $str .= chr('1');

        $code .= mcrypt_generic($td, $str);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return bin2hex($code);
    }

}
