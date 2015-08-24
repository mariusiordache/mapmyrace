<?php

require_once 'AbstractDataFile.php';

/**
 * Description of GpxFile
 *
 * @author marius
 */
class GpxFile extends AbstractDataFile {

    protected function load() {
        $xml = simplexml_load_file($this->file);

        if (isset($xml->trk->name)) {
            $this->name = (string) $xml->trk->name;
        }

        foreach ($xml->trk->trkseg->trkpt as $p) {
            $attr = $p->attributes();

            $this->addPoint(array(
                'latitude' => (string) $attr['lat'],
                'longitude' => (string) $attr['lon'],
                'time' => strtotime((string) $p->time)
            ));
        }
    }

}
