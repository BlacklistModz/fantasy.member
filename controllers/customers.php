<?php

class Customers extends Controller {

	function __construct() {
		parent::__construct();
	}

	public function index($id=null){
		$this->view->setPage('on', 'customers');
		$this->view->setPage('title', 'Customers');

		if( !empty($id) ){
			$item = $this->model->get($id, array('orders'=>true));
			if( empty($item) ) $this->error();

			$this->view->setData('topbar', array(
            'title'=>array(
                0 => array(
                    'text' => '<i class="icon-user"></i> '.$item['name_store']
                ),
                1 => array(
                    'text' => $item['sub_code']
                )
            ),
            'nav' => array(
                0 => array(
                                    // 'type' => 'link',
                    'icon' => 'icon-remove',
                    'url' => URL.'customers'
                    // 'text' => '(0)',
                ),
            )
        ) );

			$this->view->setData('item', $item);
			$render = 'customers/profile/display';
		}
		else{

			$key = isset($_GET['key']) ? $_GET['key'] : null;
			$options = array(
				'sale' => !empty($this->me['sale_code']) ? $this->me['sale_code'] : '',
				'q' => $key
			);
			$results = $this->model->lists( $options );

			$this->view->setData('topbar', array(
				'title' => array( 0 => 
					array( 'text' => '<i class="icon-users"></i> Customers ('.$results['total'].')' ),
				),
			) );

			if( $this->format=='json' ){
				$this->view->setData('results', $results);
				$this->view->render('customers/lists/json');
				exit;
			}
			$render = 'customers/lists/display';
		}
		$this->view->render($render);
	}

}