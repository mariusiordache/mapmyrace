<?php

abstract class LoginProvider {

    const REDIRECT_PAGE = '/login_provider_register/receive_provider_data?provider=';

    public static $finish_page = '/login_provider_register';

    public function getRedirectURL()
    {
        return 'http://' . $_SERVER['HTTP_HOST'] .
            self::REDIRECT_PAGE . get_class($this) .
            '&uri=' . urlencode(self::$finish_page . '?provider=' . str_replace('Provider', '', get_class($this)));
    }

}