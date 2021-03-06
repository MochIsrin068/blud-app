<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Budget_plan extends BpkadHead_Controller {
	private $services = null;

    private $parent_page = 'bpkad_head';
    private $current_page = 'bpkad_head/planning/';
	
	public function __construct(){
		parent::__construct();
		$this->data["parent_page"] = $this->parent_page;
		$this->load->model(array( 
			'm_account' ,
			'm_plan' ,
			'm_budget_plan' ,
		));
		$this->load->library('services/BudgetPlan_services');
		$this->services = new BudgetPlan_services;
	}
	public function create( )
	{
		if( !($_POST) ) redirect( site_url($this->current_page) );

		$this->form_validation->set_rules( $this->services->single_validation_config() );
        if ($this->form_validation->run() === TRUE )
        {
			$plan_id = $this->input->post('plan_id');

			$data_budget_plan['account_id'] 	= $this->input->post('account_id');
			$data_budget_plan['description'] 	= $this->input->post('description');
			$data_budget_plan['unit'] 			= $this->input->post('unit');
			$data_budget_plan['quantity'] 		= $this->input->post('quantity');
			$data_budget_plan['price'] 			= $this->input->post('price');
			$data_budget_plan['plan_id'] 		= $this->input->post('plan_id');

			if( $this->m_budget_plan->add(  $data_budget_plan ) )
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, 'berhasil' ) );
			}
			else
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'Gagal' ) );
			}
			redirect( site_url( $this->current_page ).$this->input->post('url_return') );
        }
        else
        {
			$this->data['message'] = (validation_errors() ? validation_errors() : '');
			if(  validation_errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
			
			redirect( site_url( $this->current_page ).$this->input->post('url_return') );
			// redirect( site_url( $this->current_page )."create_detail/".$plan_id );
        }
	}

	public function edit( $budget_plan_id = NULL )
	{
		if( !($_POST) ) redirect( site_url($this->current_page) );		

		$this->form_validation->set_rules( $this->services->single_validation_config() );
        if ($this->form_validation->run() === TRUE )
        {
			$plan_id = $this->input->post('plan_id');

			$data_budget_plan['account_id'] 	= $this->input->post('account_id');
			$data_budget_plan['description'] 	= $this->input->post('description');
			$data_budget_plan['unit'] 			= $this->input->post('unit');
			$data_budget_plan['quantity'] 		= $this->input->post('quantity');
			$data_budget_plan['price'] 			= $this->input->post('price');

			// $id = $this->input->post('id');
			$data_param['id'] 	    = $this->input->post('id');

			// if( $this->m_budget_plan->update( $id, $data_budget_plan ) )
			if( $this->m_budget_plan->update(  $data_budget_plan, $data_param ) )
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, 'berhasil' ) );
			}
			else
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'Gagal' ) );
			}
			redirect( site_url( $this->current_page ).$this->input->post('url_return') );			
        }
        else
        {
			$this->data['message'] = (validation_errors() ? validation_errors() : '');
			if(  validation_errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
          
			redirect( site_url( $this->current_page ).$this->input->post('url_return') );	         	
        }
	}

	public function delete(  ) {
		if( !($_POST) ) redirect( site_url($this->current_page) );
  
		$data_param[ 'id' ] 	= 	$this->input->post('id');
		$plan_id 				= 	$this->input->post('plan_id');

		if( $this->m_budget_plan->delete( $data_param ) ){
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, 'berhasil' ) );
		}else{
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'gagal' ) );
		}
		redirect( site_url( $this->current_page ).$this->input->post('url_return') );
	}

}
