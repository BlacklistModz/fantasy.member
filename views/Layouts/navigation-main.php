<?php

$users[] = array('key'=>'users', 'text'=>'จัดการสมาชิก', 'link'=>$url.'users', 'icon'=>'users');
$users[] = array('key'=>'sales', 'text'=>'จัดการพนักงานขาย', 'link'=>$url.'sales', 'icon'=>'user');
if( !empty($users) ){
	echo $this->fn->manage_nav($users, $this->getPage('on'));
}

$events[] = array('key'=>'events', 'text'=>'รายการนัดหมาย', 'link'=>$url.'events', 'icon'=>'calendar-check-o');
if( !empty($events) ){
	echo $this->fn->manage_nav($events, $this->getPage('on'));
}

$order[] = array('key'=>'payments', 'text'=>'รายการสั่งสินค้า', 'link'=>$url.'payments', 'icon'=>'cube ');
if( !empty($order) ){
	echo $this->fn->manage_nav($order, $this->getPage('on'));
}

$payments[] = array('key'=>'lists1', 'text'=>'รายการรับเงินสด', 'link'=>$url.'payments/cash', 'icon'=>'money');
$payments[] = array('key'=>'lists2', 'text'=>'รายการรับเงินโอน', 'link'=>$url.'payments/bank', 'icon'=>'cc-visa');
$payments[] = array('key'=>'lists3', 'text'=>'รายการรับเช็ค', 'link'=>$url.'payments/check', 'icon'=>'credit-card-alt');
if( !empty($payments) ){
	echo $this->fn->manage_nav($payments, $this->getPage('on'));
}

$paycheck[] = array('key'=>'paycheck', 'text'=>'รายการจ่ายเช็ค', 'link'=>$url.'paycheck', 'icon'=>'credit-card');
if( !empty($paycheck) ){
	echo $this->fn->manage_nav($paycheck, $this->getPage('on'));
}

$suppliers[] = array('key'=>'suppliers', 'text'=>'Suppliers', 'link'=>$url.'suppliers', 'icon'=>'handshake-o');
if( !empty($suppliers) ){
	echo $this->fn->manage_nav($suppliers, $this->getPage('on'));
}

$products[] = array('key'=>'discounts', 'text'=>'จัดการส่วนลด', 'link'=>$url.'discounts', 'icon'=>'cart-arrow-down');
$products[] = array('key'=>'categories', 'text'=>'จัดการหมวดหมู่สินค้า', 'link'=>$url.'categories', 'icon'=>'database');
$products[] = array('key'=>'products', 'text'=>'รายการสินค้า (คอมมิชชั่น)', 'link'=>$url.'products', 'icon'=>'cart-arrow-down');
if( !empty($products) ){
	echo $this->fn->manage_nav($products, $this->getPage('on'));
}

$reports[] = array('key'=>'comission', 'text'=>'รายงาน คอมมิชชั่น', 'link'=>$url.'reports/comission', 'icon'=>'signal');
$reports[] = array('key'=>'revenue', 'text'=>'รายงานรายรับ', 'link'=>$url.'reports/revenue', 'icon'=>'line-chart');
if( !empty($reports) ){
	echo $this->fn->manage_nav($reports, $this->getPage('on'));
}

$cog[] = array('key'=>'settings','text'=>$this->lang->translate('menu','Settings'),'link'=>$url.'settings','icon'=>'cog');
echo $this->fn->manage_nav($cog, $this->getPage('on'));