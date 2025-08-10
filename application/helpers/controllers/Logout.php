<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load_info();
	}
	public function index()
	{
		$data = $this->data;
		$array_items = array('inv_username','inv_userid','logged_in','permissions','currency');
		$this->session->unset_userdata($array_items);
		$this->load->view('login',$data);		
	}
}
