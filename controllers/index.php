<?php

class Index extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->model->query('customers')->resetUserPass();
        header('location:'.URL.'orders');
    }

    private function _getUrl() {
        $url = isset( $_GET['url'] ) ? $_GET['url']:null;
        if( empty($url) ) $this->error();
        $url = rtrim($url, '/');
        $this->_url = explode('/', $url);
    }

    public function search($page=null) {
        $this->_getUrl();
        // $this->getPage($page);

        // $this->view->render('search');
        $this->error();
    }
}
