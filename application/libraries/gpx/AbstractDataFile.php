<?php

require_once 'Point.php';

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
