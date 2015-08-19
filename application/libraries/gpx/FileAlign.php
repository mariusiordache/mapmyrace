<?php

/**
 * Description of FileAlign
 *
 * @author marius
 */
class FileAlign {

    private $files = array(), $data = null, $avatars = array();

    const DISTANCE = 10;

    public function addFile(AbstractDataFile $file, $avatar) {
        $this->files[] = $file;
        $this->avatars[] = $avatar;
    }
    
    public function getCacheFile() {
        $ff = array();
        
        foreach ($this->files as $f) {
            $ff[] = $f->getFile();
        }
        
        sort($ff);
        return CACHE_PATH . '/' . md5(serialize($ff)) . ".php";
    }

    public function getData() {
        if ($this->data === null) {

            if (empty($this->files)) {
                throw new Exception("No files added for compare!");
            }

            $cf = $this->getCacheFile();
            
            if (0 && file_exists($cf)) {
                include $cf;
                return $data;
            }
            
            $data = array();
            
            foreach($this->files as $j => $f) {
                $f->getData();
                $data['avatars'][$j] = $this->avatars[$j];
                $data['names'][$j] = $f->getName();
                $data['files'][$j] = $f->getFile();
            }
            
            $indexes = $this->getCommonIndexes();
            $this->data = array();
            foreach ($this->files as $i => $f) {
                $f->removeFirstPoints($indexes[$i]);

                foreach ($f as $p) {
                    $this->data[$p->getTimeDiff()][$i] = $p;
                }
                
            }
            
            ksort($this->data);

            $this->data = array_values($this->data);

            $this->fillData();
            $this->setGaps();
            
            $data['points'] = array();
            foreach($this->data as $i => $row) {
                foreach($row as $j => $point) {
                    $data['points'][$i][$j] = $point->toArray();
                }
                ksort($data['points'][$i]);
            }
            
            file_put_contents($cf, "<?php \$data = " . var_export($data, true) . ";");
            
            return $data;
        }
    }

    protected function fillData() {

        $last = null;
        $fc = count($this->files);

        foreach ($this->data as &$points) {

            for ($j = 0; $j < $fc; $j++) {
                if (!isset($points[$j]) && isset($last[$j])) {
                    $points[$j] = $last[$j];
                }
            }

            foreach ($points as $j => $p) {
                $last[$j] = $p;
            }
        }
    }

    protected function setGaps() {
        foreach ($this->data as $i => &$points) {

            $maxd = max(array_map(function($item) {
                        return $item->getTotalDistance();
                    }, $points));

            foreach ($points as $k => &$point) {
                $d = $point->getTotalDistance();

                if ($d === $maxd) {
                    $point->setTimeGap(0);
                    $point->setDistanceGap(0);
                } else {
                    $d0 = round($maxd - $d, 2);
                    $point->setDistanceGap($d0);
                    $j = $i - 10 > 0 ? $i - 10 : 0;

                    $p0 = $this->data[$j][$k];
                    $dd = $d - $p0->getTotalDistance();
                    $td = $point->getTimeDiff() - $p0->getTimeDiff();

                    if ($dd > 0) {
                        $v = $dd / $td;
                        $point->setTimeGap(round($d0 / $v, 2));
                    }
                }
            }
        }
    }

    protected function getCommonIndexes() {
        $fc = count($this->files);
        $indexes = array();
        foreach ($this->files[0] as $j => $p) {
            $indexes[0] = $j;
            $ok = true;

            for ($i = 1; $i < $fc && $ok; $i++) {
                $indexes[$i] = $this->getCommonPointIndex($this->files[$i], $p);
                if ($indexes[$i] === null) {
                    $ok = false;
                    break;
                }
            }

            if ($ok) {
                break;
            }
        }

        return $indexes;
    }

    public function getCommonPointIndex(AbstractDataFile $f, Point $p) {
        $d = null;
        $lastindex = null;
        $dc = $f->getDataCount();

        foreach ($f as $i => $p1) {
            $d0 = $p1->getDistance($p);

            if ($d0 < self::DISTANCE) {
                $lastindex = $i;
            }

            if (($lastindex !== null || $i * 4 > $dc) && $d < $d0) {
                break;
            }

            $d = $d0;
        }

        return $lastindex;
    }

}
