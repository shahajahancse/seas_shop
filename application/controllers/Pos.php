<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pos extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load_global();
		$this->load->model('pos_model','pos_model');
	}

	public function is_sms_enabled(){
		return false;
		//return is_sms_enabled();
	}

	public function index()
	{
		$this->permission_check('sales_add');
		$data=$this->data;
		$data['page_title']='POS';
		$this->load->view('pos',$data);
	}
	public function get_json_items_list(){
		$name = strtolower(trim($_GET['name']));
		$sql = $this->db->query("SELECT id,item_name,item_code,stock FROM db_items where status=1 and (LOWER(item_name) LIKE '%$name%' or LOWER(item_code) LIKE '%$name%') limit 10");
		$data = $sql->result();
		echo json_encode($data);exit;
	}

	public function return_row_with_data($item_id){
		$this->db->select('db_items.*, db_items.tax_type, db_items.vat_amt, db_tax.tax');
		$this->db->from('db_items');
		$this->db->join('db_tax', 'db_tax.id = db_items.tax_id');
		$res2 = $this->db->where("db_items.id", $item_id)->where("db_items.status",1)->get()->row();
		echo json_encode($res2);exit;
	}

	//adding new item from Modal
	public function newcustomer(){
		$this->form_validation->set_rules('customer_name', 'Customer Name', 'trim|required');
		if ($this->form_validation->run() == TRUE) {
			$this->load->model('customers_model');
			$result=$this->customers_model->verify_and_save();
			//fetch latest item details
			$res=array();
			$query=$this->db->query("select id,customer_name from db_customers order by id desc limit 1");
			$res['id']=$query->row()->id;
			$res['customer_name']=$query->row()->customer_name;
			$res['result']=$result;
			echo json_encode($res);
		} else {
			echo "Please Fill Compulsory(* marked) Fields.";
		}
	}
	public function pos_save_update(){
	    echo $this->pos_model->pos_save_update();
	}
	public function hold_invoice(){
	    echo $this->pos_model->hold_invoice();
	}





	// old code //
	public function get_details(){
		echo $this->pos_model->get_details();
		exit;
	}
	public function receive_order(){
	    echo $this->pos_model->receive_order();
	}
	public function edit($sales_id){
		$this->permission_check('sales_edit');
	    $data=$this->data;
	    $data['sales_id']=$sales_id;
	    $data['page_title']='POS Update';
		$this->load->view('pos',$data);
	}
	public function fetch_sales($sales_id){
	    $result=$this->pos_model->edit_pos($sales_id);
	}
	/* ######################################## HOLD INVOICE ############################# */
	public function hold_invoice_list(){
		$data =array();
		$result= $this->pos_model->hold_invoice_list();

		$data['result']=$result;
		$q1=$this->db->query("SELECT * FROM temp_holdinvoice WHERE STATUS=1 GROUP BY invoice_id");
		$data['tot_count']=$q1->num_rows();
		echo json_encode($data);
	}
	public function hold_invoice_delete($invoice_id){
		$result=$this->pos_model->hold_invoice_delete($invoice_id);
		echo trim($result);
	}
	public function hold_invoice_edit(){
		echo $this->pos_model->hold_invoice_edit();
	}

	public function add_payment_row(){
		return $this->load->view('modals_pos_payment/modal_payments_multi_sub');
	}

	//Print sales POS invoice
	public function print_invoice_pos($sales_id){
		if(!$this->permissions('sales_add') && !$this->permissions('sales_edit')){
			$this->show_access_denied_page();
		}
		$data=$this->data;
		$data['page_title']=$this->lang->line('sales_invoice');
		$data = array_merge($data,array('sales_id'=>$sales_id));
		$this->load->view('sal-invoice-pos',$data);
	}

}
