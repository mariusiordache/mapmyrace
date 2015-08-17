<?php

if (!function_exists('readYerOrNo')) {

    function readYerOrNo($msg) {
        print("$msg\n[y]es / [n]o: ");
        $fp1 = fopen("/dev/stdin", "r");
        $input = fgets($fp1, 255);
        fclose($fp1);
        $answer = strtolower(substr(trim($input), 0, 1));
        return ($answer == 'y');
    }

}