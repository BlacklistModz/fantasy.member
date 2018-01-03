<?php

class Products_Model extends Model{

    public function __construct() {
        parent::__construct();
    }

    private $_objName = "products";
    private $_table = "products p LEFT JOIN categories c ON p.pds_categories_id=c.id";
    private $_field = "p.*, c.name_en AS category_name_en, c.name_th AS category_name";
    // private $_cutNamefield = "pds_";

    public function lists($options=array()){
        $options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'id',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            'q' => isset($_REQUEST['q'])? $_REQUEST['q']:null,

            'more' => true
        ), $options);

        $date = date('Y-m-d H:i:s', $options['time']);

        $where_str = "";
        $where_arr = array();

        if( isset($_REQUEST["category"]) ){
        	$options["category"] = $_REQUEST["category"];
        }
        if( !empty($options["category"]) ){
        	$where_str .= !empty($where_str) ? $where_str : "";
        	$where_str .= "pds_categories_id=:category";
        	$where_arr[":category"] = $options["category"];
        }

        if( !empty($options['q']) ){

            $arrQ = explode(' ', $options['q']);
            $wq = '';
            foreach ($arrQ as $key => $value) {
                $wq .= !empty( $wq ) ? " OR ":'';
                $wq .= "pds_code LIKE :q{$key}
                        OR pds_barcode LIKE :q{$key} 
                        OR pds_name LIKE :q{$key} ";
                $where_arr[":q{$key}"] = "%{$value}%";
                $where_arr[":s{$key}"] = "{$value}%";
                $where_arr[":f{$key}"] = $value;
            }

            if( !empty($wq) ){
                $where_str .= !empty( $where_str ) ? " AND ":'';
                $where_str .= "($wq)";
            }
        }

        if( isset($_REQUEST["status"]) ){
        	$options["status"] = $_REQUEST["status"];
        }
        if( !empty($options["status"]) ){
        	$where_str .= !empty($where_str) ? $where_str : "";
        	$where_str .= "pds_status=:status";
        	$where_arr[":status"] = $options["status"];
        }

        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
        $orderby = $this->orderby( $options['sort'], $options['dir'] );
        $limit = $this->limited( $options['limit'], $options['pager'] );
        if( !empty($options['unlimit']) ) $limit = "";

        $arr['lists'] = $this->buildFrag( $this->db->select("SELECT {$this->_field} FROM {$this->_table} {$where_str} {$orderby} {$limit}", $where_arr ), $options  );

        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;
        
        return $arr;
    }
    public function buildFrag($results, $options=array()) {
        $data = array();
        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data[] = $this->convert($value , $options);
        }
        return $data;
    }
    public function get($id, $options=array()){

        $sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} WHERE p.id=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        return $sth->rowCount()==1
            ? $this->convert( $sth->fetch( PDO::FETCH_ASSOC ) , $options )
            : array();
    }
    public function convert($data , $options=array()){
    	// $data = $this->cut($this->_cutNamefield, $data);
    	if( !empty($data['pds_FilePhoto']) ){
    		$data['image_url'] = "http://fantasy.co.th/fileUploads/products/{$data['pds_FilePhoto']}";
    	}
        $data['pricing'] = $this->getPrice($data['id']);
        return $data;
    }

    public function insert(&$data){
    	$this->db->insert($this->_objName, $data);
    	$data['id'] = $this->db->lastInsertId();
    }
    public function update($id, $data){
    	$this->db->update($this->_objName, $data, "id={$id}");
    }
    public function delete($id){
    	$this->db->delete($this->_objName, "id={$id}");
    }

    #Price
    public function getPrice($id){
        $sth = $this->db->prepare("SELECT frontend, seller, wholesales, employee FROM products_pricing WHERE product_id = :id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        return $sth->rowCount()==1
            ? $sth->fetch( PDO::FETCH_ASSOC )
            : array();
    }
}