<?php

class GoogleProvider extends LoginProvider implements ProviderInterface {

    private $_CI;
    private $path;

    public function __construct() {
        $this->_CI = get_instance();

        $this->path = $this->_CI->config->item('google')['path'];
    }

    /**
     * get the API url
     *
     * @return string
     */
    public function getURL()
    {
        $client = new Google_Client();
        $client->setAuthConfigFile($this->path);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $client->addScope(array(
                Google_Service_Compute::DEVSTORAGE_FULL_CONTROL,
                Google_Service_Oauth2::PLUS_ME
            )
        );
        $client->setRedirectUri($this->getRedirectURL());
        return $client->createAuthUrl();
    }

    /**
     * process the data received from the provider and register the account
     *
     * @param array $data
     * @return mixed
     */
    public function parseProviderData(array $data)
    {
        $client = new Google_Client();
        $client->setAuthConfigFile($this->path);
        $client->setAccessType('offline');
        $client->addScope(array(
                Google_Service_Compute::DEVSTORAGE_FULL_CONTROL,
                Google_Service_Oauth2::PLUS_ME
            )
        );
        $client->setRedirectUri($this->getRedirectURL());
        $client->authenticate($_GET['code']);

        $access_token = json_decode($client->getAccessToken());

        $oauth2 = new Google_Service_Oauth2($client);
        /** @var Google_Service_Oauth2_Userinfoplus $userInfo */
        $userInfo = $oauth2->userinfo->get();

        if ($userInfo) {
            return array(
                'name' => $userInfo->getName(),
                'unique_id' => $userInfo->getId(),
                'attributes' => array(array('attribute' => 'refresh_token', 'value' => isset($access_token->refresh_token) ? $access_token->refresh_token : ''))
            );
        }

        return array();
    }
}