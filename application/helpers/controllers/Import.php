<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load_global();
		$this->load->model('country_model','country');
	}

	public function customers(){
		$this->permission_check('import_customers');
		$data=$this->data;
		$data['page_title']=$this->lang->line('import_customers');
		$this->load->view('import/import_customers', $data);
	}

    public function xss_html_filter($input){
        return $this->security->xss_clean(html_escape($input));
    }

	public function import_customers_csv() {
        
                extract($this->xss_html_filter(array_merge($this->data)));
                $filename = $_FILES["import_file"]["name"];
                
                if($_FILES['import_file']['size'] > 0)
                {  
                	$upload_path='./uploads/csv/customers';
                	if (!file_exists($upload_path)) {
			    		mkdir($upload_path, 0777, true);
			    	}
                	$config['upload_path']          = $upload_path;
	                $config['allowed_types']        = 'csv';
	                $this->load->library('upload', $config);
	                if ( ! $this->upload->do_upload('import_file')){

			                $error = array('error' => $this->upload->display_errors());
			                print($error['error']);
			                exit();
			        }
			        else{
			        	    $file_name=$this->upload->data('file_name');
			        }
			        
			      

                    $file = fopen('uploads/csv/customers/'.$file_name,"r");
                    
                    //Save flag
                    $flag='true';
                    $this->db->trans_begin();
                    $i=1;
                    while(($importdata = fgetcsv($file, NULL, ",")) !== FALSE){

                        $qs5="select customer_init from db_company";
                        $q5=$this->db->query($qs5);
                        $customer_init=$q5->row()->customer_init;

                        //Create customers unique Number
                        $qs4="select coalesce(max(id),0)+1 as maxid from db_customers";
                        $q1=$this->db->query($qs4);
                        $maxid=$q1->row()->maxid;
                        $customer_code=$customer_init.str_pad($maxid, 4, '0', STR_PAD_LEFT);
                        //end

                        $customer_name=$importdata[0];
                        $mobile=$importdata[1];
                        //Validate This customers already exist or not
                        $query=$this->db->query("select * from db_customers where upper(customer_name)=upper('$customer_name')");
                        if($query->num_rows()>0){
                            echo "Import Failed!<br>'".$customer_name."'' Customer Name already Exist.<br>Row Number:".$i++;
                            exit();
                        }
                        $query2=$this->db->query("select * from db_customers where mobile='$mobile'");
                        if($query2->num_rows()>0 && !empty($mobile)){
                            echo "Import Failed!<br>'".$mobile."' Mobile Number already Exist.<br>Row Number:".$i++;
                            exit();
                        }

                        $country_name=trim($importdata[6]);
                        $state_name=trim($importdata[7]);
                        //if not exist country create it and return id, else just return id if exist
                    	$country_id=(!empty($country_name)) ? $this->get_country_id($country_name) : null;

                        //if not exist state create it and return id, else just return id if exist
                        $state_id=(!empty($state_name)) ? $this->get_state_id($state_name,$country_name,$country_id) : null;

                        
                        $row = array(
                            'customer_code'    	=>  $customer_code,
                            'customer_name'     =>  $customer_name,
                            'mobile'     		=>  !empty($mobile)?$mobile:'',
                            'email'         	=>  !empty($importdata[2])?$importdata[2]:'',
                            'phone'       	 	=>  !empty($importdata[3])?$importdata[3]:'',
                            'gstin'       		=>  !empty($importdata[4])?$importdata[4]:'',
                            'tax_number'       	=>  !empty($importdata[5])?$importdata[5]:'',
                            'country_id'       	=>  $country_id,
                            'state_id'       	=>  $state_id,
                            'postcode'       	=>  !empty($importdata[8])?$importdata[8]:'',
                            'address'       	=>  !empty($importdata[9])?$importdata[9]:'',
                            /*System Info*/
                            'created_date'              => $CUR_DATE,
                            'created_time'              => $CUR_TIME,
                            'created_by'                => $CUR_USERNAME,
                            'system_ip'                 => $SYSTEM_IP,
                            'system_name'               => $SYSTEM_NAME,
                            'status'                    => 1,
                        );
                        
                        //If any record failed to save flag will be set false,then all records rolled back
                        if(!$this->db->insert('db_customers',$row)){
                            $flag='false';
                        }
                        
                        //Compulsary records
                        if(empty($importdata[0])){
                          $flag='false';   
                        }


                        
                    }
                    
                    
                    if(!$flag){
                        $this->db->trans_rollback();
                        echo 'failed';
                    }else{
                        $this->db->trans_commit();
                        echo "success";
                        $this->session->set_flashdata('success', 'Success!! Customers Data Imported Successfully!');
                    }
                    fclose($file);
                }
            
 			//unlink('uploads/csv/customers/'.$file_name);
        }

    public function get_country_id($country_name=''){
        $q2=$this->db->query("select id from db_country where upper(country)=upper('$country_name')");
        if($q2->num_rows()>0){
            return $q2->row()->id;
        }
        else{
            $q2=$this->db->query("insert into db_country(country,status) values('$country_name',1)");
            if($q2){
                return $this->db->insert_id();
            }
            return false;
        }
    }
    public function get_state_id($state_name='',$country_name='',$country_id=''){
        $q2=$this->db->query("select id from db_states where upper(state)=upper('$state_name')");
        if($q2->num_rows()>0){
            return $q2->row()->id;
        }
        else{
            $q2=$this->db->query("insert into db_states(state,country,country_id,status) values('$state_name','$country_name',$country_id,1)");
            if($q2){
                return $this->db->insert_id();
            }
            return false;
        }
    }
    public function suppliers(){
        $this->permission_check('import_suppliers');
        $data=$this->data;
        $data['page_title']=$this->lang->line('import_suppliers');
        $this->load->view('import/import_suppliers', $data);
    }
    public function import_suppliers_csv() {
                extract($this->xss_html_filter(array_merge($this->data)));
                $filename = $_FILES["import_file"]["name"];
                
                if($_FILES['import_file']['size'] > 0)
                {   
                    
                    $config['upload_path']          = './uploads/csv/suppliers';
                    $config['allowed_types']        = 'csv';
                    $this->load->library('upload', $config);

                    if ( ! $this->upload->do_upload('import_file')){
                            $error = array('error' => $this->upload->display_errors());
                            print($error['error']);
                            exit();
                    }
                    else{
                            $file_name=$this->upload->data('file_name');
                    }
                    
                  

                    $file = fopen('uploads/csv/suppliers/'.$file_name,"r");
                    
                    //Save flag
                    $flag='true';
                    $this->db->trans_begin();
                    $i=1;
                    while(($importdata = fgetcsv($file, NULL, ",")) !== FALSE){

                        $qs5="select supplier_init from db_company";
                        $q5=$this->db->query($qs5);
                        $supplier_init=$q5->row()->supplier_init;

                        //Create suppliers unique Number
                        $qs4="select coalesce(max(id),0)+1 as maxid from db_suppliers";
                        $q1=$this->db->query($qs4);
                        $maxid=$q1->row()->maxid;
                        $supplier_code=$supplier_init.str_pad($maxid, 4, '0', STR_PAD_LEFT);
                        //end

                        $supplier_name=$importdata[0];
                        $mobile=$importdata[1];
                        //Validate This suppliers already exist or not
                        $query=$this->db->query("select * from db_suppliers where upper(supplier_name)=upper('$supplier_name')");
                        if($query->num_rows()>0){
                            echo "Import Failed!<br>'".$supplier_name."'' supplier Name already Exist.<br>Row Number:".$i++;
                            exit();
                        }
                        $query2=$this->db->query("select * from db_suppliers where mobile='$mobile'");
                        if($query2->num_rows()>0 && !empty($mobile)){
                            echo "Import Failed!<br>'".$mobile."' Mobile Number already Exist.<br>Row Number:".$i++;
                            exit();
                        }

                        $country_name=trim($importdata[6]);
                        $state_name=trim($importdata[7]);
                        //if not exist country create it and return id, else just return id if exist
                        $country_id=(!empty($country_name)) ? $this->get_country_id($country_name) : null;

                        //if not exist state create it and return id, else just return id if exist
                        $state_id=(!empty($state_name)) ? $this->get_state_id($state_name,$country_name,$country_id) : null;

                        
                        $row = array(
                            'supplier_code'     =>  $supplier_code,
                            'supplier_name'     =>  $supplier_name,
                            'mobile'            =>  !empty($mobile)?$mobile:'',
                            'email'             =>  !empty($importdata[2])?$importdata[2]:'',
                            'phone'             =>  !empty($importdata[3])?$importdata[3]:'',
                            'gstin'             =>  !empty($importdata[4])?$importdata[4]:'',
                            'tax_number'        =>  !empty($importdata[5])?$importdata[5]:'',
                            'country_id'        =>  $country_id,
                            'state_id'          =>  $state_id,
                            'postcode'          =>  !empty($importdata[8])?$importdata[8]:'',
                            'address'           =>  !empty($importdata[9])?$importdata[9]:'',
                            /*System Info*/
                            'created_date'              => $CUR_DATE,
                            'created_time'              => $CUR_TIME,
                            'created_by'                => $CUR_USERNAME,
                            'system_ip'                 => $SYSTEM_IP,
                            'system_name'               => $SYSTEM_NAME,
                            'status'                    => 1,
                        );
                        
                        //If any record failed to save flag will be set false,then all records rolled back
                        if(!$this->db->insert('db_suppliers',$row)){
                            $flag='false';
                        }
                        
                        //Compulsary records
                        if(empty($importdata[0])){
                          $flag='false';   
                        }


                        
                    }
                    
                    
                    if(!$flag){
                        $this->db->trans_rollback();
                        echo 'failed';
                    }else{
                        $this->db->trans_commit();
                        echo "success";
                        $this->session->set_flashdata('success', 'Success!! suppliers Data Imported Successfully!');
                    }
                    fclose($file);
                }
            
            //unlink('uploads/csv/suppliers/'.$file_name);
        }
}

