<?php

class cron_controller extends KMS_Web_Controller {

    private $logFile = null, $logFileHandle = null;

    public function __construct() {
        parent::__construct();
        $this->start_time = microtime(true);
        $this->load->model('cron_run_collection');
        $this->load->helper('generic_helper');
        $this->load->library('current_user');
        
        // make sure all crons run with UTC time
        date_default_timezone_set("UTC");
        
        set_time_limit(0);
    }
    
    protected function setLogFile($file) {
        $this->logFile = $this->config->item('webroot_path') . '/tmp/' . $file;
    }

    protected function enableLogFile() {
        $this->logFile = $this->config->item('webroot_path') . '/tmp/' . get_called_class() . '.log';
        return $this->logFile;
    }

    public function getLogFileHandle() {
        if (empty($this->logFileHandle)) {
            $this->logFileHandle = fopen($this->logFile, "w+");
        }
        
        return $this->logFileHandle;
    }

    public function start($identifier = null, $max_concurrent_crons = 1, $force_exit = true) {

        $identifier = !$identifier ? get_called_class() : $identifier;

        $running = $this->cron_run_collection->get(array('identifier' => $identifier, 'status' => 'running'));

        
        if (count($running) < $max_concurrent_crons) {
            $this->cron_run = $this->cron_run_collection->new_instance();
            $this->cron_run->save(array(
                'start_date' => date("Y-m-d H:i:s"),
                'identifier' => $identifier,
                'status' => 'running',
                'pid' => getmypid()
            ));
            
            $this->cron_run->load_info();

            syslog(1, "{$identifier} pid " . getmypid() . " started");
            $this->log("{$identifier} pid " . getmypid() . " started");
            
            return true;
        } else {
            foreach ($running as $cron_run) {
                if (empty($cron_run['pid'])) {
                    // time check is deprecated, because some crons can take too much time to run
                    // use this only if we don't have a pid
                    if (time() - strtotime($cron_run['start_date']) > 1800) {
                        $aux = $this->cron_run_collection->new_instance($cron_run['id']);
                        $aux->save(array('status' => 'error', 'result_data' => json_encode(array('error' => 'found sleeping'))));
                    }
                } else if (!file_exists("/proc/{$cron_run['pid']}/status")) {

                    syslog(1, "{$identifier} Found missing pid {$cron_run['pid']}");

                    // pid no longer exists
                    $this->cron_run_collection->update(array(
                        'status' => 'error',
                        'result_data' => json_encode(array('error' => 'Pid no longer exists'))
                            ), $cron_run['id']);

                    return $this->start($identifier, $max_concurrent_crons, $force_exit);
                }
            }
        }

        if ($force_exit) {
            die('Cron already running. Die.');
        }
        return false;
    }

    public function error($data = array()) {
        $this->cron_run->save(array('status' => 'error', 'result_data' => json_encode($data)));
    }

    public function finish($data = array()) {

        syslog(1, "{$this->cron_run->info['identifier']} Finished pid {$this->cron_run->info['pid']}");
        $this->cron_run->save(array(
            'status' => 'finished',
            'result_data' => json_encode($data),
            'pid' => 0
        ));
    }

    public function is_started() {
        return isset($this->cron_run) && ($this->cron_run->id > 0);
    }

    public function log($text) {
        $microseconds = microtime(true) * 10000 % 10000;
        
        $msg = date('D, j M H:i:s.') . $microseconds . ' ' . $text . "\n";

        if ($this->logFile !== null) {
            fwrite($this->getLogFileHandle(), $msg);
        } else {
            echo $msg;
        }
    }

}
