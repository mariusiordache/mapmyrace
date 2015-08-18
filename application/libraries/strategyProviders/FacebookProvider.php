<?php

use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use Facebook\FacebookSession;
use Facebook\GraphUser;

class FacebookProvider extends LoginProvider implements ProviderInterface {

    private $_CI;
    private $defaultAppId;
    private $defaultAppSecret;

    public function __construct() {
        @session_start();
        $this->_CI = get_instance();

        $this->defaultAppId = $this->_CI->config->item("facebook")['defaultAppId'];
        $this->defaultAppSecret = $this->_CI->config->item("facebook")['defaultAppSecret'];
    }

    /**
     * get the API url
     *
     * @return string
     */
    public function getURL() {
        FacebookSession::setDefaultApplication($this->defaultAppId, $this->defaultAppSecret);
        $helper = new FacebookRedirectLoginHelper($this->getRedirectURL());

        return $helper->getLoginUrl(array('user_about_me','read_insights'));
    }

    /**
     * process the data received from the provider and register the account
     *
     * @param array $data
     * @return mixed
     */
    public function parseProviderData(array $data) {

        FacebookSession::setDefaultApplication($this->defaultAppId, $this->defaultAppSecret);
        $helper = new FacebookRedirectLoginHelper($this->getRedirectURL());
        try {
            $session = $helper->getSessionFromRedirect();

            /** @var GraphUser $user_profile */
            $user_profile = (new FacebookRequest($session, 'GET', '/me'))->execute()->getGraphObject(GraphUser::className());

            $longLivedAccessToken = $session->getAccessToken()->extend();
            if ($session) {
                return array(
                    'name' => $user_profile->getName(),
                    'unique_id' => $user_profile->getId(),
                    'attributes' => array(array('attribute' => 'long_live_token', 'value' => (string)$longLivedAccessToken))
                );
            }
        } catch(FacebookRequestException $ex) {
            // When Facebook returns an error
        } catch(\Exception $ex) {
            // When validation fails or other local issues
        }

        return array();
    }
}