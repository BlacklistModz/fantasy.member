<?php

class Orders extends Controller {

	function __construct() {
		parent::__construct();
	}

	public function index($id=null){
		$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;

		$this->view->setPage('on', 'orders');
        $this->view->setPage('title', 'Orders');

		if( !empty($id) ){
			$item = $this->model->get($id, array('items'=>true));
            if( empty($item) ) $this->error();

            $this->view->setData('topbar', array(
                'title' => array( 0 => 
                    array( 'text' => '<i class="icon-cube"></i> Orders ('.$item['code'].')' ),
                ),
                'nav' => array(
                    0 => array(
                                    // 'type' => 'link',
                        'icon' => 'icon-remove',
                                    // 'text' => 'Cancel',
                        'url' => URL.'mobile/orders'
                    ),
                )
            ) );

            $this->view->setData('item', $item);
            $render = 'orders/profile/display';
		}
		else{
			$options = array(
				'sort'=>'ord_dateCreate',
				'dir'=>'DESC'
			);
			$options['customer'] = $this->me['id'];
            $results = $this->model->lists( $options );

            $total_qty = $this->model->summaryItemCusOrder($this->me['id']);

            $this->view->setData('topbar', array(
                'title' => array( 0 => 
                    array( 'text' => '<i class="icon-cube"></i> Orders ('.$results['total'].')' ),
                ),
                'nav' => array(
                	0 => array(
                		'icon' => 'icon-cart-plus',
                		'url' => URL.'orders/create/'.$this->me['id'],
                		'text' => '('.$total_qty.')'
                	),
                )
            ) );

            if( $this->format=='json' ){
                $this->view->setData('results', $results);
                $this->view->render('orders/lists/json');
                exit;
            }
            $render = 'orders/lists/display';
		}
		$this->view->render($render);
	}
	public function create($cus=null){
		if( empty($this->me) ) $this->error();
		$this->view->setPage('title', 'Orders - New Order');

		$cus = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $cus;
		if( empty($cus) || empty($this->me) ) $this->error();

        $cate = $this->model->query('categories')->getFirst(array('show_sub'=>true));
       	if( empty($cate) ) $this->error();
       	$this->view->setData('cate', $cate);

        $category = $this->model->query('categories')->lists( array('sort'=>'seq', 'dir'=>'ASC', 'not_is_sub'=>true, 'show_sub'=>true) );
        $this->view->setData('category', $category);

        $total_qty = $this->model->summaryItemCusOrder($this->me['id']);

        $this->view->setData('topbar', array(
            'title'=>array(
                0 => array(
                    'text' => '<i class="icon-user"></i> '.$this->me['name_store']
                ),
                1 => array(
                    'text' => $this->me['sub_code']
                )
            ),
            'nav' => array(
                0 => array(
                                    // 'type' => 'link',
                    'icon' => 'icon-cart-plus',
                    'text' => '(<span id="total">'.$total_qty.'</span>)',
                    'url' => URL.'orders/checkout/'.$this->me['id']
                ),
            )
        ) );

        $render = 'orders/cart/display';
        $this->view->render($render);
	}
	public function saveCusOrder(){
		if( empty($_POST) ) $this->error();

		$item = $this->model->query('products')->get($_POST["id"]);
		if( empty($item) ) $this->error();

		$pro_price = !empty($item['pricing']) ? $item['pricing']['frontend'] : 0;
		$total_pro_price = $pro_price * $_POST["quantity"];

		$order = $this->model->getCusOrder($this->me['id'], array('customer'=>$_POST["cus_id"], 'not_delete'=>true));

		if( !empty($order) ){
			$id = $order['id'];
			// $data['net_price'] = $order['net_price'] + $total_pro_price;
			// $this->model->updateCusOrder($id, $data);
		}
		else{
			$sale = $this->model->query('sales')->getCode($this->me['sale_code']);
			$data = array(
				'sale_id'=>$sale['id'],
				'customer_id'=>$this->me['id'],
				'name_store'=>$this->me['name_store'],
				'sub_code'=>$this->me['sub_code'],
				'sale_code'=>$this->me['sale_code'],
				'net_price'=>$total_pro_price
			);
			$this->model->insertCusOrder($data);
			$id = $data['id'];
		}

		if( !empty($id) ){
			$postData = array(
				'customer_orders_id'=>$id,
				'products_id'=>$_POST["id"],
				'products_name'=>$item['pds_name'],
				'quantity'=>$_POST["quantity"],
				'price'=>$pro_price,
				'discount'=>'0.00',
				'prices'=>$total_pro_price
			);
			$_item = $this->model->getItemCusOrder($id, $_POST["id"]);
			if( !empty($_item) ){
				$postData['id'] = $_item['id'];
				$postData['quantity'] += $_item['quantity'];
				// $postData['price'] = $pro_price;
				$postData['prices'] = $pro_price * $postData['quantity'];
			}
			$this->model->setItemCusOrder($postData);

			if( empty($_item) ){
				$_item['id'] = $postData['id'];
			}
			$_item['quantity'] = $postData['quantity'];

			$_order = $this->model->get_cusOrder($id);
			if( !empty($_order['items']) && !empty($_item) ){
				$total = $this->model->getSummary($_order['items']);
				$_discount = $this->model->query('discounts')->getDiscountItem($_POST["id"]);
				if( !empty($_discount) ){
					foreach ($total['id'] as $key => $value) {
						if( $_discount['id'] == $key ){
							$postData = array(
								'id'=>$_item['id'],
								'discount'=>$pro_price - $value,
								'prices'=>$value * $_item['quantity']
							);
							$this->model->setItemCusOrder($postData);
							break;
						}
					}
				}
			}

			$n_order = $this->model->get_cusOrder($id);
			$g_total = $this->model->getTotal($n_order['items']);

			$data = array(
				'net_price'=>$g_total['amount'],
				'price'=>$g_total['total'],
				'discount'=>$g_total['discount']
			);
			$this->model->updateCusOrder($id, $data);
		}

		$arr['message'] = 'เลือกสินค้าเรียบร้อย';
		// $arr['url'] = 'refresh';
		echo json_encode($arr);
	}
	public function checkout($id=null){
		$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
		if( empty($id) || empty($this->me) ) $this->error();

		$this->view->setPage('title', 'Checkout');

		$order = $this->model->getCusOrder($this->me['id'], array('not_delete'=>true));
		$this->view->setData('order', $order);

		$this->view->setData('topbar', array(
            'title'=>array(
                0 => array(
                    'text' => '<i class="icon-user"></i> '.$this->me['name_store']
                ),
                1 => array(
                    'text' => $this->me['sub_code']
                )
            ),
            'nav' => array(
                0 => array(
                                    // 'type' => 'link',
                    'icon' => 'icon-remove',
                    'url' => URL.'orders/create/'.$this->me['id'],
                ),
            )
        ) );
		$this->view->render('orders/checkout/display');
	}
	public function updateItemCusOrder($id=null){
		$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;

		$item = $this->model->getItemCus($id);
		if( empty($item) ) $this->error();

		$order = $this->model->get_cusOrder($item['customer_orders_id']);
		if( empty($order) ) $this->error();

		$product = $this->model->query('products')->get($item['products_id']);
		$pro_price = !empty($product['pricing']) ? $product['pricing']['frontend'] : 0;
		$total_pro_price =  $pro_price * $_POST["quantity"];

		$postData['id'] = $item['id'];
		$postData['quantity'] = $_POST['quantity'];
		$postData['prices'] = $total_pro_price;
		$this->model->setItemCusOrder($postData);

		$g_order = $this->model->get_cusOrder($item['customer_orders_id']);
		$_total = $this->model->getSummary($g_order['items']);
		$_discount = $this->model->query('discounts')->getDiscountItem($item["products_id"]);
		if( !empty($_discount) ){
			foreach ($_total['id'] as $key => $value) {
				if( $_discount['id'] == $key ){
					$postData = array(
						'id'=>$item['id'],
						'discount'=>$pro_price - $value,
						'prices'=>$value * $_POST['quantity']
					);
					$this->model->setItemCusOrder($postData);
					break;
				}
			}
		}

		$_order = $this->model->itemsCusOrder($item['customer_orders_id']);
		$g_summary = $this->model->getSummary($_order);
		foreach ($_order as $key => $value) {
			$_dis = $this->model->query('discounts')->getDiscountItem($value['products_id']);
			if( !empty($_dis) ){
				foreach ($g_summary['id'] as $id => $price) {
					if( $id == $_dis['id'] ){
						$itemData = array(
							'id' => $value['id'],
							'discount' => $value['price'] - $price
						);
						$this->model->setItemCusOrder($itemData);
					}
				}
			}
		}

		$_items = $this->model->itemsCusOrder($item['customer_orders_id']);
		$total = $this->model->getTotal($_items);
		$orderData = array(
			'price' => $total['total'],
			'discount' => $total['discount'],
			'net_price' => $total['amount']
		);
		$this->model->updateCusOrder($order['id'], $orderData);
		echo json_encode($total);
	}
	public function del_cus_item($id=null){
		$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;

		$item = $this->model->getItemCus($id);
		if( empty($item) ) $this->error();

		$order = $this->model->get_cusOrder($item['customer_orders_id']);
		if( empty($order) ) $this->error();

		// $this->model->updateCusOrder($order['id'], array('net_price'=>$order['net_price'] - $item['prices']));
		$this->model->unsetItemCusOrder($id);

		$checkItem = $this->model->checkItemCusOrder($order['id']);
		if( empty($checkItem) ) $this->model->deleteCusOrder($order['id']);

		$total = array(
			'total'=>0,
			'discount'=>0,
			'amount'=>0
		);
		if( !empty($checkItem) ){
			$_order = $this->model->get_cusOrder($item['customer_orders_id']);
			$total = $this->model->getTotal($_order['items']);

			$orderData = array(
				'price' => $total['total'],
				'discount' => $total['discount'],
				'net_price' => $total['amount']
			);
			$this->model->updateCusOrder($order['id'], $orderData);
		}

		// $arr['message'] = 'ยกเลิกรายการเรียบร้อยแล้ว';
		echo json_encode($total);
	}
	public function confirmOrder($id=null){
		$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
		if( empty($id) || empty($this->me) ) $this->error();

		$order = $this->model->get_cusOrder($id, array('items'=>true));
		if( empty($order) ) $this->error();

		// $total = $this->model->getTotal( $order['items'] );

		$postData = array(
			'site_id'=>null,
			'create_user_id'=>$this->me['id'],
			'create_user_type'=>'Customer',
			'ord_customer_id'=>$this->me['id'],
			'ord_sale_code'=>$order['sale_code'],
			'ord_dateCreate'=>date("Y-m-d"),
			'ord_type_commission'=>'sales',
			'user_name'=>$order['name_store'],
			'user_code'=>$order['sub_code'],
			'ord_process'=>9,
			'term_of_payment'=>0,
			'ord_status'=>'A',
			'order_note'=>'',
			'ord_net_price'=>$order['net_price'],
			'ord_discount_extra'=>$order['discount'],
			'ord_tax'=>'0.00'
		);
		$this->model->insert($postData);
		$_id = $postData['id'];
		$order_code = 'B'.sprintf("%06d",$_id);
		if( !empty($_id) ){
			foreach ($order['items'] as $key => $value) {
				$product = $this->model->query('products')->get($value['products_id']);
				$data = array(
					'site_id'=>0,
					'ord_id'=>$_id,
					'ord_code'=>$order_code,
					'itm_type'=>'d',
					'itm_id'=>$value['products_id'],
					'itm_name'=>$value['products_name'],
					'itm_code'=>$product['pds_code'],
					'itm_qty'=>$value['quantity'],
					'itm_unit'=>'1',
					'itm_price'=>$value['price'],
					'itm_discount'=>$value['discount'],
					'itm_prices'=>$value['prices'],
					'itm_status'=>'A',
					'itm_remark'=>null
				);
				$this->model->setItem($data);
			}

			$this->model->update($_id, array('ord_code'=>$order_code));
			$this->model->updateCusOrder($order['id'], array('deleted_at'=>date("c")));

			$arr['message'] = 'ยืนยันการสั่งซื้อเรียบร้อย';
			$arr['url'] = URL.'orders';
		}
		else{
			$arr['message'] = 'Error ! ไม่สามารถยืนยันการสั่งซื้อได้';
		}

		echo json_encode($arr);
	}

	#SETUP FOR JSON
	public function setsubMenu($id=null){
		$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        $results = $this->model->query('categories')->listsSubCategories( $id );

        $this->view->setData('results', $results);
        $this->view->render('orders/cart/sections/sub-category');
	}
	public function setProducts($cate=null, $cus=null){
		$cate = isset($_REQUEST["cate"]) ? $_REQUEST["cate"] : $cate;
		$cus = isset($_REQUEST["cus"]) ? $_REQUEST["cus"] : $cus;
		$key = isset($_REQUEST["key"]) ? $_REQUEST["key"] : null;

		$item = $this->model->query('categories')->get($cate);
		if( empty($item) ) $this->error();

		$customer = $this->model->query('customers')->get($cus);
		if( empty($customer) ) $this->error();

		$this->view->key = $key;
		$this->view->item = $item;
		$this->view->customer = $customer;
		$this->view->render('orders/cart/sections/lists');
	}
}