<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_management extends Admin_Controller {
	public function __construct(){
		parent::__construct();

		
	} 
	public function index( $id_user = NULL )
	{
		$this->data[ "page_title" ] = "User Management";
		
		if( !$id_user == NULL )
		{
			$this->data[ "page_title" ] = "User Account";
			$this->data[ "users" ] = $this->ion_auth->user( $id_user )->result();
			$this->render( "admin/user_management/V_detail" );
		} 
		else
		{
			$this->data[ "users" ] = $this->ion_auth->users()->result();
			$this->render( "admin/user_management/V_list" );
		}
	}
	public function delete_user( $id_user = NULL )
	{
		if( !($_POST) ) redirect(site_url('admin'));  

		$id_user = $this->input->post('id_user');
		if( $this->ion_auth->delete_user( $id_user ) ){
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->ion_auth->messages() ) );
		}else{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->ion_auth->errors() ) );
		}
		redirect(site_url('admin/user_management'));  
	}
}
