<?php

class cli_progress_bar {

    private $bs = "\x08";
    private $last_string_length = 0;
    private $index = 0;
    private $total = 0;
    private $length = 20;
    private $start_time = null;
    private $start_index = null;
    private $counter = 0;
    private $last_time_diff = null;
    private $last_start_time = null;
    private $log_file = null;
    private $log_file_handle = null;
    private $file_offset = null;

    public function setLogFile($log_file) {
        $this->log_file = $log_file;
    }

    public function setLogFileHandle($handle) {
        $this->log_file_handle = $handle;
    }

    public function log($msg) {
        if ($this->log_file || $this->log_file_handle) {
            if (empty($this->log_file_handle)) {
                $this->log_file_handle = fopen($this->log_file, "w+");
            }

            if ($msg == chr(8)) {
                $this->file_offset --;
            } else {
                if ($this->file_offset !== null) {
                    fseek($this->log_file_handle, $this->file_offset, SEEK_SET);
                }
                
                fwrite($this->log_file_handle, $msg);
                $this->file_offset = ftell($this->log_file_handle);
            }
        } else {
            echo $msg;
        }
    }

    public function update_total($total) {
        $this->total = $total;
    }
    
    public function set_total($total) {
        
        $this->index = 0;
        $this->total = $total;
        $this->last_start_time = null;
        $this->last_time_diff = null;
        $this->last_string_length = 0;
        $this->start_time = null;
        $this->counter = 0;
        
        get_instance()->crud->resetQueryTime();
    }

    public function finish() {
        $this->show_loader($this->total);
        $this->log("\nTotal time: " . $this->format_time(microtime(true) - $this->start_time) . "\n");
    }
    
    public function update_loader($val) {
        if ($this->index + $val > $this->total) {
            $this->total = $this->index + $val + 1;
        }
        
        return $this->show_loader($this->index + $val);
    }

    public function show_loader($index = null) {
        if (!$index && $this->index % 50 == 0 && $this->index >= 50) {
            $this->last_time_diff = microtime(true) - ($this->last_start_time !== null ? $this->last_start_time : $this->start_time);
            $this->last_start_time = microtime(true);
        }

        if (!$this->start_time) {
            $this->start_time = microtime(true);
            $this->start_index = $index-1;
        }

        if ($index === null) {
            $this->index++;
        } else {
            $this->index = $index ? $index : 1;
        }

        if ($index && $this->total < $this->index) {
            $this->index = $this->total;
        }

        $percent = $this->total > 0 ? $this->index * 100 / $this->total : 100;
        $lines = round($this->length * $percent / 100);

        
        for ($i = 0; $i < $this->last_string_length; $i++) {
            $this->log(chr(8));
        }

        $str = "";
        for ($i = strlen(round($percent)); $i <= 3; $i++) {
            $str .= " ";
        }
        $str .= round($percent) . "%[";
        for ($i = 1; $i <= $lines; $i++) {
            $str .= "=";
        }
        $str .= ">";

        for ($j = $lines + 1; $j < $this->length; $j++) {
            $str .= " ";
        }

        $str .= "] ";

        $whitespaces = strlen($this->total) - strlen($this->index);
        for ($i = 0; $i < $whitespaces; $i++) {
            $str .= " ";
        }
        $str .= "[" . $this->index . "/" . $this->total . "] ";

        $time_diff = microtime(true) - $this->start_time;
        
        if (!$this->last_time_diff) {
            $time_remaining = round($time_diff * ($this->total - $this->index) / ($this->index - $this->start_index), 4);
        } else {
            $time_remaining = round($this->last_time_diff * ($this->total - $this->index) / 50);
        }
        
        $str .= " " . round(memory_get_usage(true)/1024/1024, 2) . " MB ";
        
        $str .= " " . round(get_instance()->crud->getQueryTime(), 2) . "/" . round($time_diff,2) . "s ";

        if ($time_remaining > 0) {
            $str .= "(" . $this->format_time($time_remaining) . " remaining )";
        }

        if ($this->last_string_length) {
            $diff = $this->last_string_length - strlen($str);
            for ($i = 0; $i < $diff; $i++) {
                $str .= " ";
            }
        }
        
        $this->last_string_length = strlen($str);
        $this->log($str);
        
        return $this->index;
    }

    private function format_time($time_remaining) {
        $time_remaining = round($time_remaining);
        $str = '';
        if ($time_remaining > 3600) {
            $hours = floor($time_remaining / 3600);
            $time_remaining -= $hours * 3600;
            $str .= " {$hours}h ";
        }
        if ($time_remaining > 60) {
            $mins = floor($time_remaining / 60);
            $time_remaining -= $mins * 60;
            $str .= "{$mins}m ";
        }
        $str .= "{$time_remaining}s";

        return $str;
    }

}
