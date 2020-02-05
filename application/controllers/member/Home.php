<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Member_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(array('m_fund_applicant', 'm_fund_cluster'));
	}
	public function index()
	{	
		$this->data['pemohon'] = $this->m_fund_applicant->get_total();
		$this->data['kelompok'] = $this->m_fund_cluster->get_total();
		$this->data[ "page_title" ] = "Beranda";
		$this->render( "member/dashboard/content" );
	}
}
