<?php 
class Reports extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function comission(){

        $this->view->setPage('on', 'comission');
        $this->view->setPage('title', 'รายการค่าคอมมิชชั่น');

    	$month = isset($_REQUEST["month"]) ? $_REQUEST["month"] : date("m");
    	$year = isset($_REQUEST["year"]) ? $_REQUEST["year"] : date("Y");

    	$start = date("Y-m-d", strtotime("{$year}-{$month}-01"));
    	$end = date("Y-m-t", strtotime($start));

    	$this->view->setData('results', $this->model->summaryComission( $start, $end ));

    	$monthStr = $this->fn->q('time')->month( $month, true );
    	$this->view->setData('period', "{$monthStr} {$year}");

    	if( isset($_GET['main']) ){
			$render = 'reports/comission/sections/main';
		}
		else{
			$render = 'reports/comission/display';
		}

    	$this->view->render( $render );
    }
    public function showComission($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $sale = $this->model->query('payments')->getSale( $id );
        if( empty($sale) ) $this->error();

        $month = isset($_REQUEST["month"]) ? $_REQUEST["month"] : date("m");
        $year = isset($_REQUEST["year"]) ? $_REQUEST["year"] : date("Y");

        $start = date("Y-m-d", strtotime("{$year}-{$month}-01"));
        $end = date("Y-m-t", strtotime($start));

        $options = array(
            'period_start'=>$start,
            'period_end'=>$end,
            'sale'=>$id
        );
        $item = $this->model->query('payments')->lists( $options );

        $this->view->setData('sale', $sale);
        $this->view->setData('item', $item);
        $this->view->setPage('path', 'Forms/reports');
        $this->view->render('comission');
    }

    public function revenue(){

        $this->view->setPage('on', 'revenue');
        $this->view->setPage('title', 'รายงานรายรับ');

        $start = isset($_REQUEST["period_start"]) ? $_REQUEST["period_start"] : date("Y-m-d");
        $end = isset($_REQUEST["period_end"]) ? $_REQUEST["period_end"] : date("Y-m-d");

        $this->view->setData('periodStr', $this->fn->q('time')->str_event_date($start, $end).' '.date("Y", strtotime($end)));

        $options = array(
            'period_start'=>$start,
            'period_end'=>$end,
            'not_process'=>7,
            'unlimit'=>true,
            'dir'=>'ASC'
        );
        $results = $this->model->query('orders')->lists( $options );
        $this->view->setData('results', $results);

        if( empty($_GET["main"]) ){
           $this->view->render('reports/revenue/display'); 
        }
        else{
            $this->view->render('reports/revenue/sections/order-lists');
        }
    }
    public function revenue_total(){
        $start = isset($_REQUEST["period_start"]) ? $_REQUEST["period_start"] : date("Y-m-d");
        $end = isset($_REQUEST["period_end"]) ? $_REQUEST["period_end"] : date("Y-m-d");

        $this->view->setData('start', $start);
        $this->view->setData('end', $end);

        $this->view->setData('periodStr', $this->fn->q('time')->str_event_date($start, $end).' '.date("Y", strtotime($end)));

        $results = $this->model->summaryRevenu($start, $end);
        $this->view->setData('results', $results);

        $this->view->render('reports/revenue/sections/order-total');
    }
}