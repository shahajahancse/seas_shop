<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load_global();
		$this->load->model('items_model','items');
	}

	public function index()
	{
		$this->permission_check('items_view');
		$data=$this->data;
		$data['page_title']=$this->lang->line('items_list');
		$this->load->view('items-list',$data);
	}
	public function add()
	{
		$this->permission_check('items_add');
		$data=$this->data;
		$data['page_title']=$this->lang->line('items');
		$this->load->view('items',$data);
	}

	public function newitems(){
		$this->form_validation->set_rules('item_name', 'Item Name', 'trim|required');
		$this->form_validation->set_rules('category_id', 'Category Name', 'trim|required');
		$this->form_validation->set_rules('unit_id', 'Unit', 'trim|required');
		$this->form_validation->set_rules('price', 'Item Price', 'trim|required');
		$this->form_validation->set_rules('item_code', 'Barcode is Unique', 'trim|required');
		// $this->form_validation->set_rules('tax_id', 'Tax', 'trim|required');
		$this->form_validation->set_rules('purchase_price', 'Purchase Price', 'trim|required');
		//$this->form_validation->set_rules('profit_margin', 'Profit Margin', 'trim|required');
		$this->form_validation->set_rules('sales_price', 'Sales Price', 'trim|required');
		if ($this->check_unique_barcode() == FALSE) {
			echo "Item Barcode Field is Unique Required";
			exit();
		}

		if ($this->form_validation->run() == TRUE) {
			$result=$this->items->verify_and_save();
			echo $result;
		} else {
			echo "Please Fill Compulsory(* marked) Fields.";
		}
	}

	public function check_unique_barcode()
	{
		$item_code = $this->input->post('item_code'); // Get the barcode value
		$this->db->where('item_code', $item_code);  // Exclude the barcode record
		$query = $this->db->get('db_items');
		if ($query->num_rows() > 0) {
			return FALSE; // barcode already exists
		}
		return TRUE; // barcode is unique
	}

	public function update($id){
		$this->permission_check('items_edit');
		$data=$this->data;
		$this->load->model('items_model');
		$result = $this->items_model->get_details($id,$data);
		// dd($result);
		$data=array_merge($data,$result);
		$data['page_title']=$this->lang->line('items');
		$this->load->view('items', $data);
	}

	public function update_items(){
		$this->form_validation->set_rules('item_name', 'Item Name', 'trim|required');
		$this->form_validation->set_rules('category_id', 'Category Name', 'trim|required');
		$this->form_validation->set_rules('unit_id', 'Unit', 'trim|required');
		$this->form_validation->set_rules('price', 'Item Price', 'trim|required');
		$this->form_validation->set_rules('purchase_price', 'Purchase Price', 'trim|required');
		//$this->form_validation->set_rules('profit_margin', 'Profit Margin', 'trim|required');
		$this->form_validation->set_rules('sales_price', 'Sales Price', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$result=$this->items->update_items();
			echo $result;
		} else {
			echo "Please Fill Compulsory(* marked) Fields.";
		}
	}

	public function get_brand_name($brand_id=''){
		if($brand_id==NULL || $brand_id=='' || $brand_id ==0){
			return;
		}
		return $this->db->query('select brand_name from db_brands where id="'.$brand_id.'"')->row()->brand_name;
	}
	public function ajax_list()
	{
		$list = $this->items->get_datatables();

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $items) {
			if($items->stock < $items->alert_qty){
				$alert='red';
			}else{
				$alert='';
			}

			$no++;
			$row = array();
			$row[] = '<input type="checkbox" name="checkbox[]" value='.$items->id.' class="checkbox column_checkbox '.$alert.'" >';


			$row[] = (!empty($items->item_image)) ? "
						<a title='Click for Bigger!' href='".base_url($items->item_image)."' data-toggle='lightbox'>
						<image style='border:1px #72afd2 solid;' src='".base_url(return_item_image_thumb($items->item_image))."' width='75%' height='50%'> </a>" : "
						<image style='border:1px #72afd2 solid;' src='".base_url()."theme/images/no_image.png' title='No Image!' width='75%' height='50%' >";
			$row[] = $items->item_code;
			$row[] = $items->item_name;
			$row[] = $items->brand_name;//$this->get_brand_name($items->brand_id);
			$row[] = $items->category_name;
			$row[] = $items->lot_number;
			$row[] = $items->unit_name;
			$row[] = $items->stock;
			$row[] = $items->alert_qty;
			$row[] = $this->currency($items->sales_price);
			$row[] = $items->tax_name."<br>(".$items->tax."%)";

			 		if($items->status==1){
			 			$str= "<span onclick='update_status(".$items->id.",0)' id='span_".$items->id."'  class='label label-success' style='cursor:pointer'>Active </span>";}
					else{
						$str = "<span onclick='update_status(".$items->id.",1)' id='span_".$items->id."'  class='label label-danger' style='cursor:pointer'> Inactive </span>";
					}
			$row[] = $str;

			 		$str2 = '<div class="btn-group" title="View Account">
										<a class="btn btn-primary btn-o dropdown-toggle" data-toggle="dropdown" href="#">
											Action <span class="caret"></span>
										</a>
										<ul role="menu" class="dropdown-menu dropdown-light pull-right">';

											if($this->permissions('items_edit'))
											$str2.='<li>
												<a title="Edit Record ?" href="'.base_url('items/update/'.$items->id).'">
													<i class="fa fa-fw fa-edit text-blue"></i>Edit
												</a>
											</li>';

											if($this->permissions('items_delete'))
											$str2.='<li>
												<a style="cursor:pointer" title="Delete Record ?" onclick="delete_items('.$items->id.')">
													<i class="fa fa-fw fa-trash text-red"></i>Delete
												</a>
											</li>

										</ul>
									</div>';
			$row[] = $str2;

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->items->count_all(),
						"recordsFiltered" => $this->items->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	public function update_status(){
		$this->permission_check_with_msg('items_edit');
		$id=$this->input->post('id');
		$status=$this->input->post('status');

		$this->load->model('items_model');
		$result=$this->items_model->update_status($id,$status);
		return $result;
	}

	public function delete_items(){
		$this->permission_check_with_msg('items_delete');
		$id=$this->input->post('q_id');
		return $this->items->delete_items_from_table($id);
	}
	public function multi_delete(){
		$this->permission_check_with_msg('items_delete');
		$ids=implode (",",$_POST['checkbox']);
		return $this->items->delete_items_from_table($ids);
	}

	//Used in Purchase and sales Forms
	public function get_json_items_details(){
		$data = array();
		$display_json = array();
		//if (!empty($_GET['name'])) {
			$name = strtolower(trim($_GET['name']));
			$sql =$this->db->query("SELECT id,item_name,item_code,stock FROM db_items where  status=1 and  (LOWER(item_name) LIKE '%$name%' or LOWER(item_code) LIKE '%$name%')   limit 10");

			foreach ($sql->result() as $res) {
			      $json_arr["id"] = $res->id;
				  $json_arr["value"] = $res->item_name;
				  $json_arr["label"] = $res->item_name;
				  $json_arr["item_code"] = $res->item_code;
				  $json_arr["stock"] = $res->stock;
				  array_push($display_json, $json_arr);
				 /* $display_json[] =$res->id;
				  $display_json[] =$res->item_name;
				  $display_json[] =$res->item_code;*/
			}
		//}
		//echo json_encode($data);exit;
		echo json_encode($display_json);exit;
	}

	public function labels($purchase_id=''){
		$this->permission_check('print_labels');
		$data=$this->data;
		$data['page_title']=$this->lang->line('print_labels');
		$data['purchase_id']=$purchase_id;
		$this->load->view('labels',$data);
	}

	/*Labels Print request*/
	public function return_row_with_data($rowcount,$item_id){
		echo $this->items->get_items_info($rowcount,$item_id);
	}

	public function preview_labels(){
		echo $this->items->preview_labels();
	}

	//GET Labels from Purchase Invoice
	public function show_labels($purchase_id=''){
		$i=1;
		$result='';
		$q2=$this->db->query("select item_id,purchase_qty from db_purchaseitems where purchase_id='$purchase_id'");
		if($q2->num_rows()>0){

			foreach ($q2 -> result() as $res2) {
				$result.= $this->items->get_purchase_items_info($i++,$res2->item_id,$res2->purchase_qty);
			}
		}
		echo $result;
	}
	public function delete_stock_entry(){
		$this->permission_check_with_msg('items_delete');
		$entry_id = $this->input->post('entry_id');
		echo $this->items->delete_stock_entry($entry_id);
	}

	public function item_adjustment()
	{
		$this->permission_check('items_view');
		$data = $this->data;
		$this->db->select('db_items.*, db_category.category_name, cm.company_name, db_units.unit_name, db_tax.tax_name');
		$this->db->from('db_items');
		$this->db->join('db_category', 'db_category.id = db_items.category_id', 'left');
		$this->db->join('db_brand_companies cm', 'cm.id = db_items.company_id', 'left');
		$this->db->join('db_units', 'db_units.id = db_items.unit_id', 'left');
		$this->db->join('db_tax', 'db_tax.id = db_items.tax_id');
		$this->db->where('db_items.status', 1);
		$this->db->order_by('db_items.id', 'ASC');
		$data['items'] = $this->db->get()->result();

		$data['page_title']=$this->lang->line('items_list');
		$this->load->view('item_adjustment',$data);
	}
	/**
	 * Updates multiple items in one go.
	 */
	public function ajax_update()
	{
		$item_ids = $this->input->post('id');
		$prices = $this->input->post('price');
		$tax_names = $this->input->post('tax_name');
		$purchase_prices = $this->input->post('purchase_price');
		$profit_margins = $this->input->post('profit_margin');
		$discount = $this->input->post('discount');
		$sales_prices = $this->input->post('sales_price');
		$stocks = $this->input->post('stock');

		$data = array();
		foreach ($item_ids as $key => $id) {
			$data = array(
				'price' => $prices[$id],
				'tax_id' => $tax_names[$id],
				'purchase_price' => $purchase_prices[$id],
				'profit_margin' => $profit_margins[$id],
				'discount' => $discount[$id],
				'sales_price' => $sales_prices[$id],
				'stock' => $stocks[$id],
			);
			// dd($data);
			$this->db->where('id', $id);
			$this->db->update('db_items', $data);
		}
		redirect('items/item_adjustment','refresh');
	}

	public function ajax_items_by_company_id($com_id = null, $c_id = null, $name = null)
	{
		$name = urldecode(str_replace('%2520', ' ', $name));
		$this->db->select('db_items.*, db_category.category_name, cm.company_name, db_units.unit_name, db_tax.tax_name');
		$this->db->from('db_items');
		$this->db->join('db_category', 'db_category.id = db_items.category_id', 'left');
		$this->db->join('db_brand_companies cm', 'cm.id = db_items.company_id', 'left');
		$this->db->join('db_units', 'db_units.id = db_items.unit_id', 'left');
		$this->db->join('db_tax', 'db_tax.id = db_items.tax_id');
		$this->db->where('db_items.status', 1);
		if ($name != 'All') {
			$this->db->like('db_items.item_name', $name);
		}
		if ($c_id != 'All') {
			$this->db->where('db_items.category_id', $c_id);
		}
		if ($com_id != 'All') {
			$this->db->where('db_items.company_id', $com_id);
		}
		$this->db->order_by('db_items.id', 'ASC');
		$items = $this->db->get()->result();
		$taxs = $this->db->get('db_tax')->result();
		$html = '';
		foreach ($items as $key => $row) {
			$html .= "<tr>" ?>
				<input type="hidden" name="id[]" value="<?= $row->id ?>">
				<td> <?= $key+1; ?> </td>
				<td> <?= $row->item_name ?> </td>
				<td> <?= $row->category_name ?> </td>
				<td> <?= $row->company_name ?> </td>
				<td><input value="<?= $row->price ?>" class="form-control input-sm" name="price[<?= $row->id ?>]" id="price<?= $row->id ?>" onkeyup="calculateSalePrice(<?= $row->id ?>)"></td>
				<td>
					<select name="tax_name[<?= $row->id ?>]" class="form-control all-tax input-sm" id="tax_name<?= $row->id ?>" onchange="calculateSalePrice(<?= $row->id ?>)">
						<?php foreach ($taxs as $tax): ?>
							<option value="<?= $tax->id ?>" <?= $tax->id == $row->tax_id ? "selected" : "" ?>><?= $tax->tax_name ?></option>
						<?php endforeach; ?>
					</select>
				</td>
				<td><input value="<?= $row->purchase_price ?>" class="form-control input-sm" name="purchase_price[<?= $row->id ?>]" id="purchase_price<?= $row->id ?>" readonly></td>
				<td>
					<input value="<?= $row->profit_margin ?>" class="form-control all-profit input-sm" name="profit_margin[<?= $row->id ?>]" id="profit_margin<?= $row->id ?>" onkeyup="calculateSalePrice(<?= $row->id ?>)">
				</td>
				<td>
					<input value="<?= $row->discount ?>" class="form-control all-discount input-sm" name="discount[<?= $row->id ?>]" id="discount<?= $row->id ?>" onkeyup="calculateSalePrice(<?= $row->id ?>)">
				</td>
				<td>
					<input value="<?= $row->sales_price ?>" class="form-control input-sm" name="sales_price[<?= $row->id ?>]" id="sales_price<?= $row->id ?>" readonly>
				</td>
				<td> <input value="<?= $row->stock ?>" class="form-control input-sm" name="stock[<?= $row->id ?>]" id="stock<?= $row->id ?>"> </td>
			</tr>
		<?php }
		echo $html;
	}
}
