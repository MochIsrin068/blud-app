<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Sac_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(array('m_fund_cluster', 'm_fund_application'));

	}
	public function index()
	{
		$this->data['kelompok'] = $this->m_fund_cluster->get_total();
		$this->data['permohonan'] = $this->m_fund_application->get_total();
		$this->data[ "page_title" ] = "Beranda";
		$this->render( "sac/dashboard/content" );
	}
}
