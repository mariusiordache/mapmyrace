<?php

class ProviderBuilder {

    /** @var ProviderInterface */
    private $strategy = NULL;

    public function __construct($class_name) {
        include_once("LoginProvider.php");
        include_once("ProviderInterface .php");
        @include_once("$class_name.php");
        if (class_exists($class_name)) {
            $this->strategy = new $class_name;
        }
    }

    public function getURL() {
        if ($this->strategy) {
            return $this->strategy->getURL();
        }

        return null;
    }

    public function processData(array $data)
    {
        return $this->strategy->parseProviderData($data);
    }
}
