<?php

class Point {

    public $latitude, $longitude, $time, $timediff = 0, $distance = 0, $gap = null;

    public function __construct($data = array()) {

        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }

    public function getDistance(Point $p) {

        if ($this->latitude == $p->latitude && $this->longitude == $this->latitude) {
            return 0;
        }

        $theta = $this->longitude - $p->longitude;
        $distance = (sin(deg2rad($this->latitude)) * sin(deg2rad($p->latitude))) + (cos(deg2rad($this->latitude)) * cos(deg2rad($p->latitude)) * cos(deg2rad($theta)));
        $distance = rad2deg(acos($distance));
        $distance *= 60 * 1.1515 * 1.609344 * 1000;
        
        return (round($distance, 6));
    }
    
    public function setT0(Point $p) {
        $this->timediff = $this->time - $p->time;
    }
    
    public function getTotalDistance() {
        return $this->distance;
    }
    
    public function setTotalDistance(Point $p) {
        $this->distance = $p->getTotalDistance() + $this->getDistance($p);
    }
    
    public function getTimeDiff() {
        return $this->timediff;
    }
    
    public function setTimeGap($t) {
        $this->gap['t'] = $t;
    }
    
    public function setDistanceGap($d) {
        $this->gap['d'] = $d;
    }
    
    public function toArray() {
        return array(
            'lat' => $this->latitude,
            'lon' => $this->longitude,
            'd' => $this->distance,
            't' => $this->timediff,
            'g' => $this->gap
        );
    }
}
