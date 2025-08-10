<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company_model extends CI_Model {

	//Get suppliers_details
	public function get_details(){
		$data=$this->data;

		//Validate This suppliers already exist or not
		$query=$this->db->query("select * from db_company order by id asc limit 1");
		if($query->num_rows()==0){
			show_404();exit;
		}
		else{
			$query=$query->row();
			$data['q_id']=$query->id;
			$data['company_name']=$query->company_name;
			$data['website']=$query->company_website;
			$data['mobile']=$query->mobile;
			$data['phone']=$query->phone;
			$data['email']=$query->email;
			$data['country']=$query->country;
			$data['state']=$query->state;
			$data['city']=$query->city;
			$data['postcode']=$query->postcode;
			$data['address']=$query->address;
			$data['gstin']=$query->gst_no;
			$data['vat']=$query->vat_no;
			$data['website']=$query->website;
			$data['pan']=$query->pan_no;
			$data['bank_details']=$query->bank_details;
			$data['company_logo']=$query->company_logo;


			return $data;
		}
	}
	public function update_company(){

		//Filtering XSS and html escape from user inputs
		extract($this->security->xss_clean(html_escape(array_merge($this->data,$_POST))));

		/*Log uploader*/
		$company_logo='';
		if(!empty($_FILES['company_logo']['name'])){
			$config['upload_path']          = './uploads/company/';
	        $config['allowed_types']        = 'gif|jpg|png|jpeg';
	        $config['max_size']             = 2048;
	        $config['max_width']            = 1000;
	        $config['max_height']           = 1000;

	        $this->load->library('upload', $config);

	        if ( ! $this->upload->do_upload('company_logo'))
	        {
	                $error = array('error' => $this->upload->display_errors());
	                print($error['error']);
	                exit();
	        }
	        else
	        {
	        	   $logo_name=$this->upload->data('file_name');
	        		$company_logo=" ,company_logo='$logo_name' ";
	        }
		}
		/*End*/

		$query1="update db_company set company_name='$company_name',mobile='$mobile',phone='$phone',email='$email',country='$country',state='$state',city='$city',postcode='$postcode',address='$address',gst_no='$gstin',vat_no='$vat',website='$website', pan_no='$pan',bank_details='$bank_details' $company_logo where id=$q_id";

		if ($this->db->simple_query($query1)){
		        return "success";
		}
		else{
		        return "failed";
		}
	}


}
