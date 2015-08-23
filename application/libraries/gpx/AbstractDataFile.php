<?php

require_once 'Point.php';
require_once 'GeoCoding.php';

/**
 * Description of Track
 *
 * @author marius
 */
abstract class AbstractDataFile implements Iterator {

    protected $file = null,
            $data = null,
            $name = null;
    private $position = 0;
    protected $rectangle = null;

    public function __construct($file) {
        $this->file = $file;
        $this->position = 0;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getFile() {
        return $this->file;
    }

    public function getDataCount() {
        return count($this->data);
    }

    public function getData() {
        if ($this->data === null) {
            if (!file_exists($this->file)) {
                throw new Exception("File does not exists!");
            }

            $this->load();
        }

        return $this->data;
    }

    /**
     * 
     * @return array containing 4 coordinates $xMin, $yMin, $xMax, $yMax
     */
    public function getRectangle() {
        
        if ($this->rectangle === null) {
            $xMin = null;
            $xMax = null;
            $yMin = null;
            $yMax = null;

            $data = $this->getData();
            
            foreach($data as $point) {
                
                $x = (double) $point->latitude;
                $y = (double) $point->longitude;

                if ($xMin === null || $xMin > $x) $xMin = $x;
                if ($xMax === null || $xMax < $x) $xMax = $x;
                if ($yMin === null || $yMin > $y) $yMin = $y;
                if ($yMax === null || $yMax < $y) $yMax = $y;
            }
        
            $this->rectangle = array($xMin, $yMin, $xMax, $yMax);
        }
        
        return $this->rectangle;
    }
    
    public function getCenter() {
        
        $rect = $this->getRectangle();
        
        $x = abs($rect[0] - $rect[2])/2 + $rect[0];
        $y = abs($rect[1] - $rect[3])/2 + $rect[1];
        
        return array($x, $y);
    }
    
    public function getRadius() {
        $rect = $this->getRectangle();
        $c = $this->getCenter();
        
        $cent = new Point(array(
            'longitude' => $c[1],
            'latitude' => $c[0],
        ));
        
        $rad = $cent->getDistance(new Point(array(
            'longitude' => $rect[1],
            'latitude' => $rect[0],
        )));
        
        return $rad;
    }
    
    public function getLocation() {
        
        $c = $this->getCenter();
         
        $loc = GeoCoding::getAddress($c[1], $c[0]);
        
        return !empty($loc['o']) ? $loc['o'] : (!empty($loc['v']) ? ($loc['v'] . ', ' . $loc['j']) : 'Necunoscut');
    }
    
    public function removeFirstPoints($i) {
        $this->data = array_slice($this->data, $i);

        $this->resetT0();
    }

    public function resetT0() {
        $this->rewind();
        $p = $this->current();
        $l = $p;
        foreach ($this as $p1) {
            $p1->setT0($p);
            $p1->setTotalDistance($l);
            $l = $p1;
        }
    }

    abstract protected function load();

    protected function addPoint($point) {
        if (is_array($point)) {
            $point = new Point($point);
        }

        $this->data[] = $point;
    }

    function rewind() {
        $this->position = 0;
    }

    function current() {
        return $this->data[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->data[$this->position]);
    }

}
