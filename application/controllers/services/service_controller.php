<?php

abstract class service_controller extends KMS_Web_Controller {

    abstract protected function configure();

    abstract protected function process($request);

    protected $type_id = null, $service_id, $shouldExit = false, $gearmanWorker;

    public function __construct() {
        parent::__construct();

        $this->load->model('service_type_collection');
        $this->load->model('service_status_collection');

        set_time_limit(0);
    }

    protected function preStart() {
        $this->configure();

        $this->gearmanWorker = new GearmanWorker();

        $this->gearmanWorker->addServer($this->config->item('services', 'gearman_server'));
        $this->gearmanWorker->addFunction($this->getServiceName(), function (GearmanJob $job) {

            $this->db->reconnect();

            $workload = $job->workload();

            $json_workload = json_decode($workload, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $workload = $json_workload;
            }

            try {
                $return['success'] = true;
                $return['response'] = $this->process($workload);
            } catch (Exception $ex) {
                $return['success'] = false;
                $return['error'] = $ex->getMessage();
            }

            return json_encode($return);
        });

        $this->register();
    }

    public function start($port = null, $ip = null) {

        $this->preStart($ip, $port);

        //declare(ticks = 1);
        //pcntl_signal(SIGTERM, array($this, 'shutdown'));
        //pcntl_signal(SIGINT, array($this, 'shutdown'));

        while (1) {

            $this->gearmanWorker->work(); // work() will block execution until a job is delivered

            if ($this->gearmanWorker->returnCode() != GEARMAN_SUCCESS) {
                break;
            }

            $this->memory_usage = memory_get_usage(true) / 1024 / 1024;
        }
    }

    public function getServiceResult($service, $params) {
        return $this->notifyService($service, $params, false);
    }

    public function notifyBackgroundService($service, $params) {
        return $this->notifyService($service, $params, true);
    }

    public function notifyService($service, $params, $background = true) {

        $client = new GearmanClient();
        $client->addServer($this->config->item('services', 'gearman_server'));

        if (!is_string($params)) {
            $params = json_encode($params);
        }

        $this->log("Notify {$service}:{$params}");

        if ($background) {
            return $client->doBackground($service, $params);
        } else {
            $response = $client->doNormal($service, $params);

            $reply = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $response = $reply;
            }

            return $response;
        }
    }

    public function setBusy($status) {
        $this->service_status_collection->save(array(
            'busy' => $status ? 1 : 0
                ), $this->service_id);
    }

    private function register() {
        $ret = $this->service_status_collection->save(array(
            'type_id' => $this->getTypeId()
        ));

        $this->service_id = $ret['id'];

        // we should notify master now
    }

    public function shutdown() {
        $this->shouldExit = true;
        echo "Shuting down in progress ... \n";
    }

    public function __destruct() {
        if ($this->service_id) {
            $this->service_status_collection->delete($this->service_id);
            echo "Unregistered succesfully\n";
        }
    }

    protected function getIp() {
        return gethostbyname($this->config->item('cookie_domain'));
    }

    protected function getServiceName() {
        $type = str_replace("_controller", "", get_called_class());
        return $type;
    }

    protected function getTypeId() {

        if ($this->type_id === null) {

            $filters = array('name' => $this->getServiceName());
            $existing = $this->service_type_collection->get_one($filters);

            if (!empty($existing)) {
                $this->type_id = $existing['id'];
            } else {
                $res = $this->service_type_collection->save($filters);
                $this->type_id = $res['id'];
            }
        }

        return $this->type_id;
    }

    public function log($text) {
        if (!is_string($text)) {
            $text = json_encode($text, JSON_PRETTY_PRINT);
        }
        echo date('Y-m-d H:i:s.u') . ': ' . $text . "\n";
    }

}
