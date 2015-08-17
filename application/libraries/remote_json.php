<?php

class remote_json {
    
    public function get($url) {
        
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $output = curl_exec($ch); 
        $info = curl_getinfo($ch);
        curl_close($ch);     
        
        if (substr($info['http_code'],0,1) != '2') {
            throw new Exception("{$url} return bad HTTP Headers {$info['http_code']}", 404);
        }

        // try to remove non ascii charachters
        $output = preg_replace('/[[:^print:]]/', '', $output);
        $json = json_decode($output, true);
        
        if (json_last_error()) {
            throw new Exception("JSON from {$url} failed to be decoded. " . json_last_error_msg());
        }
        
        return $json;
    }
}