<?php

class Customers_Model extends Model{

    public function __construct() {
        parent::__construct();
    }

    private $_objName = "customers";
    private $_table = "customers";
    private $_field = "*";

    public function lists($options=array()){
        $options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'sub_code',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            'q' => isset($_REQUEST['q'])? $_REQUEST['q']:null,

            'more' => true
        ), $options);

        $date = date('Y-m-d H:i:s', $options['time']);

        $where_str = "";
        $where_arr = array();

        if( isset($_REQUEST["sale"]) ){
            $options['sale'] = $_REQUEST["sale"];
        }
        if( !empty($options['sale']) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "sale_code=:sale";
            $where_arr[':sale'] = $options["sale"];
        }

        if( !empty($options['q']) ){

            $arrQ = explode(' ', $options['q']);
            $wq = '';
            foreach ($arrQ as $key => $value) {
                $wq .= !empty( $wq ) ? " OR ":'';
                $wq .= "sub_code LIKE :q{$key}
                        OR name_store LIKE :q{$key} 
                        OR phone LIKE :q{$key} 
                        OR phone_other LIKE :q{$key}";
                $where_arr[":q{$key}"] = "%{$value}%";
                $where_arr[":s{$key}"] = "{$value}%";
                $where_arr[":f{$key}"] = $value;
            }

            if( !empty($wq) ){
                $where_str .= !empty( $where_str ) ? " AND ":'';
                $where_str .= "($wq)";
            }
        }

        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
        $orderby = $this->orderby( $options['sort'], $options['dir'] );
        $limit = $this->limited( $options['limit'], $options['pager'] );

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

        $sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} WHERE id=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        return $sth->rowCount()==1
            ? $this->convert( $sth->fetch( PDO::FETCH_ASSOC ) , $options )
            : array();
    }
    public function convert($data , $options=array()){

        if( !empty($options['orders']) ){
            $data['orders'] = $this->listsOrders($data['id']);
        }

        $data['address'] = $this->getAddress($data['id']);

        $data['permit']['del'] = true;
        $data['total_order'] = $this->db->count('orders', "ord_customer_id=:id", array(":id"=>$data['id']));
        if( !empty($data['total_order']) ) $data['permit']['del'] = false;
        return $data;
    }

    public function insert(&$data){
        $data['created_at'] = date("c");
        $this->db->insert($this->_objName, $data);
        $data['id'] = $this->db->lastInsertId();
    }
    public function update($id, $data){
        $this->db->update($this->_objName, $data, "id={$id}");
    }
    public function delete($id){
        $this->db->delete($this->_objName, "id={$id}");
        $this->delAddress($id);
    }
    public function getCode($code){
        $sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} WHERE sub_code=:code LIMIT 1");
        $sth->execute( array(':code'=>$code) );

        return $sth->rowCount()==1
            ? $sth->fetch( PDO::FETCH_ASSOC )
            : array();
    }
    public function setAddress($data){
        if( !empty($data['id']) ){
            $this->db->update('customers_address', $data, "id={$data['id']}");
        }
        else{
            $this->db->insert('customers_address', $data);
        }
    }
    public function getAddress($id){
        return $this->db->select("SELECT address, road, area, district, province, post_code, country, c.name AS country_name FROM customers_address ca LEFT JOIN country c ON ca.country=c.id WHERE customer_id=:id", array(":id"=>$id));
    }
    public function delAddress($id){
        $this->db->delete('customers_address', "customer_id={$id}", $this->db->count('customers_address', "customer_id={$id}"));
    }

    #LISTS ORDERS
    public function listsOrders($id){
        $data = $this->db->select("SELECT id, ord_code AS code, ord_sale_code AS sale_code, ord_type_commission AS comission, term_of_payment AS payments, ord_net_price AS price FROM orders WHERE ord_customer_id=:id", array(":id"=>$id));

        $arr = $this->query('orders')->buildFrag($data);
        return $arr;
    }

    public function login($user, $pass){

        $sth = $this->db->prepare("SELECT id,password FROM {$this->_table} WHERE username=:login");

        $sth->execute( array(
            ':login' => $user
        ) );

        $fdata = $sth->fetch( PDO::FETCH_ASSOC );
        if( $sth->rowCount()==1 ){
            if( password_verify($pass, $fdata['password']) ){
                return $fdata['id'];
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
    public function is_username($text){
        return $this->db->count($this->_objName, "username=:text", array(":text"=>$text));
    }
    public function resetUserPass(){
        $pass = $this->fn->q('password')->PasswordHash('1234');
        $results = $this->db->select("SELECT * FROM {$this->_objName} WHERE username is null");
        foreach ($results as $key => $value) {
            $data = array(
                'username'=>'C'.$value['sub_code'],
                'password'=>$pass
            );
            $this->update($value['id'], $data);
        }
    }
}