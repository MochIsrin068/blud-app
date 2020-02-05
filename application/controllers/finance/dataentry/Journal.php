<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Journal extends Finance_Controller {
	private $services = null;
    private $name = null;
    private $parent_page = 'finance';
    private $current_page = 'finance/dataentry/journal/';
	private $form_data = null;

	
	public function __construct(){
		parent::__construct();
		$this->data["parent_page"] = $this->parent_page;
		$this->load->model(array( 
			'm_account' ,
			'm_start_balance' ,
			'm_generalcash' ,
			'm_journal' ,
		));
		$this->load->library('services/Journal_services');
		$this->services = new Journal_services;

		$this->load->library('services/BudgetPlan_services');
		$this->budgetPlan_services = new BudgetPlan_services;

	}
	public function index()
	{
		$page = ($this->uri->segment(4+1)) ? ($this->uri->segment(4+1) -  1 ) : 0;
		// echo $page; return;
        //pagination parameter
        $pagination['base_url'] = base_url($this->current_page) .'index';
        $pagination['total_records'] = $this->m_start_balance->record_count() ;
        $pagination['limit_per_page'] = 10;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4+1;
        //set pagination
		if ($pagination['total_records']>0) $this->data['links'] = $this->setPagination($pagination);

		$table = $this->services->get_table_config( $this->current_page, $pagination['start_record'] + 1 );
		$rows = $this->m_start_balance->get_years( $pagination['start_record'], $pagination['limit_per_page'] )->result();
		// $key = $this->input->get('key', FALSE);
		// if( $key ) $rows = $this->m_start_balance->search( $key )->result();

		$table[ "rows" ] = $rows;
		// echo var_dump( $rows );return;
		$this->data[ "table" ] = $this->load->view('templates/tables/generalcash_table', $table, true);
		// 
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Jurnal Umum";
		$this->data["header"] = "Jurnal Umum";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

		$modal_form_add = array(
			"name" => "Buat Tahun Anggaran",
			"modal_id" => "add_journal_",
			"button_color" => "primary",
			"url" => site_url( $this->current_page."create/" ),
			"form_data" => array(
				"year" => array(
				'type' => 'number',
				'label' => "Tahun",
				'value' => date('Y'),
				),
			),
			'data'=> NULL,
		);
		$this->data[ "modal_form_add" ] = '';//$this->load->view('templates/actions/modal_form', $modal_form_add, true ); 
		
		$this->render( "finance/journal/content");

	}
	public function create(  )
	{
		if( !($_POST) ) redirect( site_url($this->current_page) );
		
		$year	= $this->input->post('year');
		redirect( site_url($this->current_page."year/".$year ) );
	}
	
	public function create_journal( $year )
	{
		$this->form_validation->set_rules( $this->services->validation_config() );
        if ($this->form_validation->run() === TRUE )
        {

			$data['account_id'] 	= $this->input->post('account_id');
			$data['date'] 			= strtotime( $this->input->post('date') );
			$data['year'] 			= $this->input->post('year');
			$data['nominal'] 		= $this->input->post('nominal');
			$data['user'] 			= $this->input->post('user');
			$data['party'] 			= $this->input->post('party');

			// echo var_dump( $data );
			// return;

			if( $this->m_journal->create(  $data ) )
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, 'berhasil' ) );
			}
			else
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'Kas Umum Pada Waktu '.date( 'M/Y', $data['date'] ).' sudah ditutup / DPA Belum terbit' ) );
			}
			redirect( site_url( $this->current_page.'year/'.$year )  );
        }
        else
        {
			$this->data['message'] = (validation_errors() ? validation_errors() : '');
			if(  validation_errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
          
         	$alert = $this->session->flashdata('alert');
			$this->data["key"] = $this->input->get('key', FALSE);
			$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
			$this->data["current_page"] = $this->current_page;
			$this->data["block_header"] = "Buat Jurnal Umum Tahun ".$year;
			$this->data["header"] = "Buat Jurnal Umum Tahun ".$year;
			$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

			$additional_form =  array(
				"year" => array(
					'type' => 'hidden',
					'label' => "Tahun",
					'readonly' => "",
					'value' => $year,
				),
			);
			$form["form_data"] = $this->services->form_data(  );
			$form["form_data"] = array_merge( $form[ "form_data" ], $additional_form );
			$this->data[ "form_add" ] = $this->load->view('templates/form/bsb_form', $form , TRUE );  
			
			$accounts = $this->m_account->get_end_point();
			$account_select = array();
			foreach( $accounts as $account )
			{
				$account_select[ $account->id ] = $account->name ." (". $account->description .") ";
			}
			$form_account_dropdown['form_data'] = array(
				"account_id" => array(
					'type' => 'select_search',
					'label' => "No Rekening",
					'options' => $account_select,
				),
			);
			
			$this->render( "finance/journal/create_journal");
        }
	}

	public function edit( $journal_id )
	{
		$this->form_validation->set_rules( $this->services->validation_config() );
        if ($this->form_validation->run() === TRUE )
        {

			$data['account_id'] 	= $this->input->post('account_id');
			$data['date'] 			= strtotime( $this->input->post('date') );
			$data['year'] 			= $this->input->post('year');
			$data['nominal'] 		= $this->input->post('nominal');
			$data['user'] 			= $this->input->post('user');
			$data['party'] 			= $this->input->post('party');

			$data_param['id'] 			= $this->input->post('id');

			if( $this->m_journal->update( $data, $data_param ) )
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, 'berhasil' ) );
			}
			else
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'Kas Umum Pada Waktu '.date( 'M/Y', $data['date'] ).' sudah ditutup / DPA Belum terbit' ) );
			}
			redirect( site_url( $this->current_page.'year/'.$data['year'] )  );
        }
        else
        {
			$this->data['message'] = (validation_errors() ? validation_errors() : '');
			if(  validation_errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
			
			$journal 				= $this->m_journal->journal( $journal_id )->row();

			$year = $journal->year;
         	$alert = $this->session->flashdata('alert');
			$this->data["key"] = $this->input->get('key', FALSE);
			$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
			$this->data["current_page"] = $this->current_page;
			$this->data["block_header"] = "Edit Jurnal Umum Tahun ".$year;
			$this->data["header"] = "Edit Jurnal Umum Tahun ".$year;
			$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

			$additional_form =  array(
				"year" => array(
					'type' => 'hidden',
					'label' => "Tahun",
					'readonly' => "",
					'value' => $year,
				),
			);
			$form["form_data"] = $this->services->form_data( $journal_id );
			$form["form_data"] = array_merge( $form[ "form_data" ], $additional_form );
			$this->data[ "form_add" ] = $this->load->view('templates/form/bsb_form', $form , TRUE );  
			
			
			$this->render( "finance/journal/create_journal");
        }
	}

	public function year( $year, $page = 0 )
	{	
		$page = ($this->uri->segment(4+2)) ? ($this->uri->segment(4 + 2) -  1 ) : 0;
		// echo $page; return;
        //pagination parameter
        $pagination['base_url'] = base_url($this->current_page) .'year/'.$year.'/';
        $pagination['total_records'] = $this->m_journal->year_record_count( $year );
        $pagination['limit_per_page'] = 100;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4+2;
        //set pagination
		if ($pagination['total_records']>0) $this->data['links'] = $this->setPagination($pagination);
		#####################################################################################################################
		$table = $this->services->get_journal_table_config( $this->current_page."year/".$year."/" , $pagination['start_record'] + 1 );
		$rows = $this->m_journal->journals_limit( $year, $pagination['start_record'], $pagination['limit_per_page'] )->result() ;
		$table[ "rows" ] = $rows;
		$this->data[ "table" ] = $this->load->view('templates/tables/start_balance_table', $table, true);
		

		$modal_form_add_juornal = array(
			"name" => "Buat Jurnal Umum",
			"modal_id" => "add_journal_",
			"button_color" => "primary",
			"url" => site_url( $this->current_page."create/" ),
			
			'data'=> NULL,
		);
		$additional_form =  array(
			"year" => array(
				'type' => 'hidden',
				'label' => "Tahun",
				'readonly' => "",
				'value' => $year,
			),
		);
		
		$modal_form_add_juornal[ "form_data" ] = $this->services->form_data(  );
		$modal_form_add_juornal[ "form_data" ] = array_merge( $modal_form_add_juornal[ "form_data" ], $additional_form );

		#####################################################################################################################
		$link["url"] = site_url( $this->current_page."create_journal/".$year );
		$link["button_color"] = 'primary';
		$link["name"]  = 'Buat Jurnal Umum';
		$link["param"] = NULL;
		$this->data[ "modal_form_add" ] = $this->load->view('templates/actions/link', $link, TRUE ); 
		// $this->data[ "modal_form_add" ] = '';//$this->load->view('templates/actions/modal_form', $modal_form_add_juornal, true ); 
		#####################################################################################################################
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Jurnal Umum Tahun ".$year;
		$this->data["header"] = "Jurnal Umum ".$year;
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		// echo $year;return;

		$this->render( "finance/journal/content_year");
		return;
	}


	public function delete(  ) 
	{
		if( !($_POST) ) redirect( site_url($this->current_page) );
  
		$data_param[ 'id' ] 	= 	$this->input->post('id');
		$year = $this->input->post('year');

		if( $this->m_journal->delete( $data_param ) ){
		  	$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, 'berhasil' ) );
		}else{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'Jurnal Umum Pada Waktu '.date( 'M/Y', $data['date'] ).' sudah ditutup Tidak Bisa menghapus Data' ) );
		}
		redirect( site_url( $this->current_page.'year/'.$year )  );
		
	}
}
