<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Public_Controller {

	function __construct(){
		parent::__construct();
	}

	public function index(){
		// TODO : tampilkan landing page bagi user yang belum daftar
		if($this->session->userdata('group_id')!=null){
			$this->load->model(array('m_group'));
			$dash = $this->m_group->getWhere(array('id'=>$this->session->userdata('group_id')))[0]->name;
			$this->data["dash"] = $dash;
		}
		$this->render("V_landing_page");
	}
}
