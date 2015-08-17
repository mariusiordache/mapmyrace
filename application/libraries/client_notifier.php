<?php

class client_notifier {

    private $_CI = null;
    private $domain = null;
    private $port = null;
    private $socket = null;

    public function __construct() {
        $this->_CI = get_instance();

        $socketio = $this->_CI->config->item('socketio');
        $this->domain = !empty($socketio['domain']) ? $socketio['domain'] : getDomain();
        $this->port = !empty($socketio['udpport']) ? $socketio['udpport'] : 7659;
    }

    protected function getSocket() {
        if ($this->socket === null) {
            $this->socket = fsockopen('udp://' . $this->domain, $this->port, $errno, $errstr, 2);

            if (!$this->socket) {
                error_log("client_notifier {$errno} {$errstr}");
            }
        }

        return $this->socket;
    }
    
    public function sendMessageToChannel($channel, $message) {
        return $this->sendMessage(array(
            'channel' => $channel,
            'message' => $message
        ));
    }

    /**
     * @param array $message Message to send to the client browser
     */
    public function sendMessage($message) {
        if (!is_string($message)) {
            $message = json_encode($message);
        }

        $socket = $this->getSocket();

        if ($socket) {
            fwrite($socket, $message);
            return true;
        }
        
        return false;
    }

    
    public function __destruct() {
        if ($this->socket) {
            fclose($this->socket);
        }
    }
}
