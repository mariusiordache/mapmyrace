<?php

/**
 * Description of MY_Output
 *
 * @author marius
 */
class MY_Output extends CI_Output {

    const TYPE_AJAX = 'ajax';
    const TYPE_PAGE = 'page';

    private $type = null;

    public function setAjax() {
        $this->type = self::TYPE_AJAX;
    }

    public function setPage() {
        $this->type = self::TYPE_PAGE;
    }
    
    public function isAjax() {
        return $this->type === self::TYPE_AJAX;
    }

    public function isPage() {
        return $this->type === self::TYPE_PAGE;
    }
    
    public function getType() {
        return $this->type;
    }
}
