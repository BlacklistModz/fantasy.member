<?php 
class Payments_Model extends Model {
	public function __construct() {
        parent::__construct();
    }

	 #Acount
    private $a_field = "account_id AS id
                        , account_bank_id AS bank_id
                        , account_number AS number
                        , account_name AS name
                        , account_branch AS branch 
                        , b.bank_name
                        , b.bank_code";
    private $a_table = "payments_account a LEFT JOIN payments_bank b ON a.account_bank_id=b.bank_id";
    public function account( $options=array() ){
        $data = array();
        $w = '';
        $w_arr = array();

        if( !empty($options["show"]) ){
        	$w .= !empty($w) ? " AND " : "";
        	$w .= "account_not_show=0";
        }

        $w = !empty($w) ? "WHERE {$w}" : "";

        $results = $this->db->select("SELECT {$this->a_field} FROM {$this->a_table} {$w}", $w_arr);
        foreach ($results as $key => $value) {
            $data[$key] = $value;
            $data[$key]['name_str'] = $value['bank_code'].' - '.$value['number'].' ('.$value['name'].')';
        }
        return $data;
    }
    public function getAccount($id){
        $sth = $this->db->prepare("SELECT {$this->a_field} FROM {$this->a_table} WHERE account_id=:id LIMIT 1");
        $sth->execute( array(':id'=>$id) );

        return $sth->rowCount()==1
            ? $sth->fetch( PDO::FETCH_ASSOC )
            : array();
    }
    public function insertAccount(&$data){
        $this->db->insert("payments_account", $data);
    }
    public function updateAccount($id, $data){
        $this->db->update("payments_account", $data, "account_id={$id}");
    }
    public function deleteAccount($id){
        $this->db->delete("payments_account", "account_id={$id}");
    }
    public function is_number($text){
        return $this->db->count("payments_account", "account_number=:text", array(":text"=>$text));
    }
}

?>