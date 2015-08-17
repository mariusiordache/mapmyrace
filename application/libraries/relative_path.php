<?php

class relative_path {

    public static function compute($base, $relative) {

        //cleanup a bit
        $thisPath = rtrim(str_replace("//", "/", $relative),"/");
        $to = rtrim(str_replace("//", "/", $base),"/");
        //split in parts
        $thisPathParts = explode("/", $thisPath);
        $toPathParts = explode("/", $to);
        //ignore the common parts
        do {
            $thisPart = array_shift($thisPathParts);
            $toPart = array_shift($toPathParts);
        } while ($thisPart == $toPart);
        //add last part back, it was different
        if ($thisPart) {
            array_unshift($thisPathParts, $thisPart);
        }
        if ($toPart) {
            array_unshift($toPathParts, $toPart);
        }
        //construct the folder navigation
        $goingUp = "";
        if (!count($thisPathParts)) {
            $goingUp = "./";
        } else {
            while (count($thisPathParts)) {
                $goingUp.="../";
                array_shift($thisPathParts);
            }
        }
        //build the relative path
        $theRelativePath = $goingUp . implode("/", $toPathParts);
        return $theRelativePath;
    }

}
