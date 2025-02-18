<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}
	public function verify_and_save($data){
		extract($data);
		
		$query=$this->db->query("select * from db_users where username='$username'")->num_rows();
		if($query>0){ return "This username already exist.";}
		$query=$this->db->query("select * from db_users where mobile='$mobile'")->num_rows();
		if($query>0){ return "This Moble Number already exist.";}
		$query=$this->db->query("select * from db_users where email='$email'")->num_rows();
		if($query>0){ return "This Email ID already exist.";}
		
		$query1="insert into db_users(username,password,mobile,email,role_id,
		created_date,created_time,created_by,system_ip,system_name,status) 
									values('$username','$password','$mobile','$email',$role_id,
									'".$data['CUR_DATE']."','".$data['CUR_TIME']."','".$data['CUR_USERNAME']."','".$data['SYSTEM_IP']."','".$data['SYSTEM_NAME']."',1)";
		
		if ($this->db->simple_query($query1)){
				$this->session->set_flashdata('success', 'Success!! New User created Succssfully!!');
		        return "success";
		}
		else{
		        return "failed";
		}

		

	}
	public function verify_and_update($data){
		
		extract($data);

		$query=$this->db->query("select * from db_users where username='$username' and id<>$q_id")->num_rows();
		if($query>0){ return "This username already exist.";}
		$query=$this->db->query("select * from db_users where mobile='$mobile' and id<>$q_id")->num_rows();
		if($query>0){ return "This Moble Number already exist.";}
		$query=$this->db->query("select * from db_users where email='$email' and id<>$q_id")->num_rows();
		if($query>0){ return "This Email ID already exist.";}
		
		$query1="UPDATE db_users SET username='$username', mobile='$mobile', email='$email',role_id=$role_id where id=$q_id";
		
		if ($this->db->simple_query($query1)){
				$this->session->set_flashdata('success', 'Success!! User Updated Succssfully!!');
		        return "success";
		}
		else{
		        return "failed";
		}

		

	}
	public function status_update($userid,$status){
		
        $query1="update db_users set status='$status' where id=$userid";
        if ($this->db->simple_query($query1)){
            echo "success";
        }
        else{
            echo "failed";
        }
	}
	public function password_update($currentpass,$newpass,$data){
		
        $query=$this->db->query("select * from db_users where password='$currentpass' and id=".$data['CUR_USERID']);
		if($query->num_rows()==1){

			$query1="update db_users set password='$newpass' where id=".$data['CUR_USERID'];
			if ($this->db->simple_query($query1)){
			        return "success";
			}
			else{
			        return "failed";
			}
		}
		else{
			return "Invalid Current Password!";
			}
	}
	//Get users deatils
	public function get_details($id){
		$data=$this->data;

		//Validate This suppliers already exist or not
		$query=$this->db->query("select * from db_users where id=$id");
		if($query->num_rows()==0){
			show_404();exit;
		}
		else{
			$query=$query->row();
			$data['q_id']=$query->id;
			$data['username']=$query->username;
			$data['mobile']=$query->mobile;
			$data['email']=$query->email;
			$data['role_id']=$query->role_id;
			return $data;
		}
	}

	public function delete_user($id){
		if($id==1){
			echo "Restricted! Can't Delete User Admin!!";
			exit();
		}
        $query1="delete from db_users where id=$id";
        if ($this->db->simple_query($query1)){
            echo "success";
            $this->session->set_flashdata('success', 'Success!! User Deleted Succssfully!');
        }
        else{
            echo "failed";
        }
	}

}
