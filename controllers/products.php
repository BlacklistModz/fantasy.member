<?php

class Products extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
    	$this->error();
    }

    public function lists($category=null){
    	$category = isset($_REQUEST["category"]) ? $_REQUEST["category"] : $category;
        $options = array(
            'category'=>$category
        );
        if( isset($_REQUEST["key"]) ){
            $options["q"] = $_REQUEST["key"];
        }
    	if( $this->format=='json' ){
    		$this->view->setData('results', $this->model->query('products')->lists( $options ));
    		$render = 'orders/cart/sections/json';
    	}
    	else{
    		$render = 'orders/cart/sections/lists';
    	}
    	$this->view->render($render);
    }
}