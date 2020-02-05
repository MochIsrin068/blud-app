<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Budget_plan extends CI_Controller {
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
			'm_budget_plan' ,
		));
		$this->load->library('services/BudgetPlan_services');
		$this->services = new BudgetPlan_services;

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
		$sum = $this->m_budget_plan->sum_budget_plan( $year, $account_ids , $_month , $group_by, $order )->result();

		header('Content-Type: application/json');

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
		$sum = $this->m_budget_plan->sum_budget_plan( $year, $account_ids , $_month , $group_by, $order )->result();
		// echo var_dump( $this->m_budget_plan->db );return;
		$this->load->library('services/BudgetPlan_services');
		$this->services = new BudgetPlan_services;
		$table = $this->services->get_table_no_action('', 1 );
		$table[ "rows" ] = $sum;
		
		echo $this->load->view('templates/tables/start_balance_table', $table, true);
	}

}
