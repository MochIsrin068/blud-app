<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Balance extends CI_Controller {
	private $services = null;
    private $name = null;
    private $parent_page = 'finance';
    private $current_page = 'finance/dataentry/journal/';
	private $form_data = null;

	private $types = NULL;
	
	public function __construct(){
		parent::__construct();
		$this->data["parent_page"] = $this->parent_page;
		$this->load->model(array( 
			'm_account' ,
			'm_start_balance' ,
			'm_generalcash' ,
			'm_journal' ,
		));
		$this->load->library('services/StartBalance_services');
		$this->services = new StartBalance_services;

	}

	public function test(  ) 
	{
		// $contents =  trim( file_get_contents("http://192.168.1.73/blud/api/journal/accumulation?month1&group_by=real_date&order=account_prefix&account=7"),  "\xEF\xBB\xBF" );
		$contents =  ( file_get_contents( site_url( "api/journal/accumulation?month1&group_by=account_id&order=account_prefix&account=7|4|5" ) ) );
		$contents = utf8_encode($contents);
		$contents = json_decode($contents);

		$this->load->library('services/StartBalance_services');
		$this->services = new StartBalance_services;

		$table = $this->services->get_journal_table_config_no_action('', 1 );
		$table[ "rows" ] = $contents ;
		echo $this->load->view('templates/tables/start_balance_table', $table, true);
		// print_r( $contents );
	}

	public function accumulation(  )
	{	
		$year = ( $this->input->get('year', 1) ) ? $this->input->get('year', 1) : date('Y') ;

		$month = ( $this->input->get('month', 1) ) ? $this->input->get('month', 1) : '1' ;
		$month = explode( "-", $month );
		
		$account = $this->input->get('account');
		if( $account != NULL ) $account = explode( "|", $account );

		$group_by = ( $this->input->get('group_by', 1) ) ? $this->input->get('group_by', 1) : [] ;
		$group_by = ( empty($group_by) ) ?[] : explode( "|", $group_by );

		$_month = [ $month[0] ];
		if( count( $month ) > 1 ) 
		{
			$_month = array();
			for( $i = $month[0] ; $i <= $month[1]; $i++ ) $_month[]=$i;
		}
		
		$order = ( $this->input->get('order', 1) ) ? $this->input->get('order', 1) : "id|asc" ;
		$order = explode( "|", $order );

		if( count( $order ) == 1 ) $order[]= "asc";
		

		$this->m_account->get_start_balance_end_point( $account, 1 );
		$account_ids = $this->m_account->get_account_ids_list();
		$account_ids = ( empty( $account_ids ) ) ? NULL: $account_ids;
		// $account_ids = [];
		$sum = $this->m_start_balance->sum_balance( $year, $account_ids , $_month , $group_by, $order )->result();

		header('Content-Type: application/json');
		// if( !empty( $sum ) )
		// {	
		// 	$sum =  array(
		// 		(object ) array(
		// 			'id'=>0,
		// 			'account_id'=>0,
		// 			'nominal'=>0,
		// 			'date'=>0,
		// 			'year'=>$year,
		// 			'real_date'=> 0,
		// 			'account_prefix'=> '',
		// 			'account_code'=> $account,
		// 			'account_description'=> '',
		// 		)
		// 	);
		// }
		echo json_encode( $sum );return;
	}

	public function accumulation_table(  )
	{	
		$year = ( $this->input->get('year', 1) ) ? $this->input->get('year', 1) : date('Y') ;

		$month = ( $this->input->get('month', 1) ) ? $this->input->get('month', 1) : '1' ;
		$month = explode( "-", $month );
		
		$account = $this->input->get('account');
		if( $account != NULL ) $account = explode( "|", $account );

		$group_by = ( $this->input->get('group_by', 1) ) ? $this->input->get('group_by', 1) : [] ;
		$group_by = ( empty($group_by) ) ?[] : explode( "|", $group_by );

		$_month = [ $month[0] ];
		if( count( $month ) > 1 ) 
		{
			$_month = array();
			for( $i = $month[0] ; $i <= $month[1]; $i++ ) $_month[]=$i;
		}
		
		$order = ( $this->input->get('order', 1) ) ? $this->input->get('order', 1) : "id|asc" ;
		$order = explode( "|", $order );

		if( count( $order ) == 1 ) $order[]= "asc";
		

		$this->m_account->get_start_balance_end_point( $account, 1 );
		$account_ids = $this->m_account->get_account_ids_list();
		$account_ids = ( empty( $account_ids ) ) ? NULL: $account_ids;
		// $account_ids = [];
		$sum = $this->m_start_balance->sum_balance( $year, $account_ids , $_month , $group_by, $order )->result();

		$this->load->library('services/StartBalance_services');
		$this->services = new StartBalance_services;
		$table = $this->services->get_balance_table_config_no_action('', 1 );
		$table[ "rows" ] = $sum;
		
		echo $this->load->view('templates/tables/start_balance_table', $table, true);
	}

}
