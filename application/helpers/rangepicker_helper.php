<?php

if (!function_exists('get_range_picker_options')) {
    
    function get_range_picker_options() {
        
        $year = date('Y');
        
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', time() - 86400);
        
        $options = array();
        $options['Today'] = array($today, $today);
        $options['Yesterday'] = array($yesterday, $yesterday);
        $options['Last 7 Days'] = array(date('Y-m-d', time() - 86400 * 6), $today);
        $options['Last 2 Weeks'] = array(date('Y-m-d', time() - 86400 * 13), $today);
        $options['Last 30 Days'] = array(date('Y-m-d', time() - 86400 * 29), $today);
        $options['This Month'] = array(date('Y-m-01'), date('Y-m-t'));
        
        for ($i = 1; $i<= 5; $i++) {
            $month = date('n') - $i;
            
            if ($month < 1) {
                $month = 12;
                $year--;
            }
            
            $tt = strtotime(date("{$year}-{$month}-01"));
            $options[date('F', $tt)] = array(date('Y-m-d', $tt), date('Y-m-t', $tt));
        }
        
        return $options;
    }
}

if (!function_exists('get_range_picker_interval')) {
    
    function get_range_picker_interval($interval) {
        $options = get_range_picker_options();
        
        if (isset($options[$interval])) {
            return $options[$interval];
        }
        
        return null;
    }
    
}