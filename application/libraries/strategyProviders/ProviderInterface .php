<?php

interface ProviderInterface {
    /**
     * generate the provider register ulr
     *
     * @return mixed
     */
    public function getURL();


    /**
     * process the data received from the provider and register the account
     *
     * @param array $data
     * @return mixed
     */
    public function parseProviderData(array $data);
}
