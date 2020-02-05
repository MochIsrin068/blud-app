<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends BludHead_Controller {
	public function __construct(){
		parent::__construct();

	}
	public function index()
	{
		$this->data[ "page_title" ] = "Beranda";
		$this->render( "admin/dashboard/content" );
	}
}
