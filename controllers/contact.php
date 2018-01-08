<?php

class Contact extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
    	$this->view->render('contact/info');
    }
    public function feedback(){
    	if( empty($this->me) ) $this->error();

    	$this->view->setData('topbar', array(
    		'title' => array( 0 =>
    			array( 'text' => '<i class="icon-handshake-o"></i> คำติชม' ),
    		)
    	) );

    	if( !empty($_POST) ){
    		try{
    			$form = new Form();
    			$form 	->post('feed_title')->val('is_empty')
    					->post('feed_desc')->val('is_empty');
    			$form->submit();
    			$postData = $form->fetch();

    			if( empty($arr['error']) ){
    				$postData['feed_cus_id'] = $this->me['id'];
    				$this->model->query('feedback')->insert($postData);

    				$arr['message'] = 'ส่งคำติชมเรียบร้อย';
    				$arr['url'] = 'refresh';
    			}

    		} catch (Exception $e) {
    			$arr['error'] = $this->_getError($e->getMessage());
    		}
    		echo json_encode($arr);
    	}
    	else{
    		$results = $this->model->query('feedback')->lists( array('customer'=>$this->me['id']) );
    		$this->view->setData('results', $results['lists']);
    		$this->view->render('contact/feedback');
    	}
    }

    public function bank(){

    }
}