<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Planing extends Finance_Controller {
	private $services = null;
	private $budgetPlan_services = null;
    private $name = null;
    private $parent_page = 'finance';
    private $current_page = 'finance/planing/';
	private $form_data = null;
	
	public function __construct(){
		parent::__construct();
		$this->data["parent_page"] = $this->parent_page;
		$this->load->model(array( 
			'm_account' ,
			'm_plan' ,
			'm_budget_plan' ,
		));
		$this->load->library('services/BudgetPlan_services');
		$this->load->library('services/Planing_services');
		$this->services = new Planing_services;
		$this->budgetPlan_services = new BudgetPlan_services;
	}
	public function index()
	{
		// 
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;
        //pagination parameter
        $pagination['base_url'] = base_url($this->current_page) .'/index';
        $pagination['total_records'] = $this->m_plan->get_total() ;
        $pagination['limit_per_page'] = 10;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4;
        //set pagination
		if ($pagination['total_records']>0) $this->data['links'] = $this->setPagination($pagination);
		
		//fetch data from database
		$rows = $this->m_plan->plans( $pagination['start_record'], $pagination['limit_per_page'] )->result();
		// $key = $this->input->get('key', FALSE);
		// if( $key ) $rows = $this->m_plan->search( $key )->result();
		// table
		$table = $this->services->get_table_config( $this->current_page, $pagination['start_record'] + 1 );
		$table[ "rows" ] = $rows;
		$this->data[ "table" ] = $this->load->view('templates/tables/rba_table', $table, true);
		// 
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Rencana Belanja Anggaran";
		$this->data["header"] = "Rencana Belanja Anggaran";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		
		$this->render( "finance/planing/content");
	}

	public function detail( $plan_id = NULL )
	{

		$plan = $this->m_plan->plan($plan_id)->row();
		if( $plan->id == NULL )
		{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'terjadi kesalahan' ) );
			redirect(site_url($this->current_page ));
		}
		$pendapatan_sum =  json_decode( file_get_contents( site_url( "api/budget_plan/accumulation?&order=account_prefix|desc&account=4&year=".$plan->year ) ) );
		$pendapatan = $this->budgetPlan_services->set_row( $pendapatan_sum, '4', 'Pendapatan' );
		$pad =  json_decode( file_get_contents( site_url( "api/budget_plan/accumulation?&order=account_prefix|desc&account=4.1&group_by=id&year=".$plan->year ) ) );
		// echo var_dump( $pendapatan );return;
		#############################################################################################################################
		#############################################################################################################################
		$belanja_sum =  json_decode( file_get_contents( site_url( "api/budget_plan/accumulation?&order=account_prefix|desc&account=5&year=".$plan->year ) ) );
		$belanja = $this->budgetPlan_services->set_row( $belanja_sum, '5', 'Belanja' );
		$belanja_langsung = $this->budgetPlan_services->set_row( $belanja_sum, '5. 2', 'Belanja Langsung' );
		$belanja_pegawai_sum =  json_decode( file_get_contents( site_url( "api/budget_plan/accumulation?&order=account_prefix|desc&account=5.2.1&year=".$plan->year ) ) );
		$belanja_pegawai_sum = $this->budgetPlan_services->set_row( $belanja_pegawai_sum, '5.2.1', 'Belanja Pagawai' );
		$belanja_pegawai_honor = $this->budgetPlan_services->set_row( $belanja_pegawai_sum, '5 .2 .1 .02', 'Honoraruim Non PNS' );
		$belanja_pegawai_honorer = $this->budgetPlan_services->set_row( $belanja_pegawai_sum, '5.2.1.02.02', 'Honoraruim Pegawai Honorer/ Tidak Tetap' );
		$belanja_pegawai =  json_decode( file_get_contents( site_url( "api/budget_plan/accumulation?&order=account_prefix|desc&account=5.2.1&group_by=id&year=".$plan->year ) ) );
		#############################################################################################################################
		#############################################################################################################################
		$account_barang_jasa = $this->m_account->accounts_by_name('5.2.2')->row();
		$account_barang_jasa = ( $account_barang_jasa != NULL ) ? $account_barang_jasa : (object) array( 'name' => '5 .2 .2','description'=>'Belanja Barang Dan Jasa' );
		$belanja_barang_jasa_sum =  json_decode( file_get_contents( site_url( "api/budget_plan/accumulation?&order=account_prefix|desc&account=5.2.2&year=".$plan->year ) ) );
		$belanja_barang_jasa_sum = $this->budgetPlan_services->set_row( $belanja_barang_jasa_sum, $account_barang_jasa->name , $account_barang_jasa->description );
		$belanja_barang_jasa =  json_decode( file_get_contents( site_url( "api/budget_plan/accumulation?&order=account_prefix|desc&account=5.2.2&group_by=id&year=".$plan->year ) ) );
		#############################################################################################################################
		#############################################################################################################################
		$pembiayaan_sum =  json_decode( file_get_contents( site_url( "api/budget_plan/accumulation?&order=account_prefix|desc&account=7&year=".$plan->year ) ) );
		$pembiayaan = $this->budgetPlan_services->set_row( $pembiayaan_sum, '7', 'Pembiayaan' );
		$pembiayaan_list =  json_decode( file_get_contents( site_url( "api/budget_plan/accumulation?&order=account_prefix|desc&account=7&group_by=id&year=".$plan->year ) ) );
		#############################################################################################################################
		#############################################################################################################################

		$table = $this->budgetPlan_services->get_table_no_action( "finance/budget_plan/", 1 );
		$table[ "rows" ] = array_merge(
			$this->budgetPlan_services->set_bold( $pendapatan ),
			$pad,
			$this->budgetPlan_services->get_delimiter(),
			$this->budgetPlan_services->set_bold( $belanja ),
			$this->budgetPlan_services->set_bold( $belanja_langsung ),
			$this->budgetPlan_services->set_bold( $belanja_pegawai_sum ),
			$this->budgetPlan_services->set_bold( $belanja_pegawai_honor ),
			$this->budgetPlan_services->set_bold( $belanja_pegawai_honorer ),
			$belanja_pegawai,
			$this->budgetPlan_services->get_delimiter(),
			$this->budgetPlan_services->set_bold( $belanja_barang_jasa_sum ),
			$belanja_barang_jasa,
			$this->budgetPlan_services->get_delimiter(),
			$this->budgetPlan_services->set_bold( $pembiayaan ),
			$pembiayaan_list
		);
	
		$this->data[ "table" ] = $this->load->view('templates/tables/rba_table', $table, true);
		#############################################################################################################################
		#############################################################################################################################
		#############################################################################################################################
		$this->data[ "plan" ] = $plan;

		$form["form_data"] = array(
			"title" => array(
			  'type' => 'text',
			  'readonly' => '',
			  'label' => "Judul",
			),
			"description" => array(
			  'type' => 'text',
			  'label' => "Deskripsi",
			  'readonly' => '',
			  
			),
			"year" => array(
			  'type' => 'number',
			  'label' => "Tahun",
			  'readonly' => '',
			),
		);
		$form["data"] = $plan;
		$this->data[ "form_add" ] = $this->load->view('templates/form/bsb_form', $form , TRUE );  
		/////////////////////////////////////////////////////////////////////
		#############################################################################################################################
		#############################################################################################################################
		if( $this->input->get('pdf', FALSE) )
		{
			if( !($_POST) ) redirect( site_url( $this->current_page) );
			$_data = (object) array(
				"date" => strtotime( $this->input->post("date") ),
				"closing_balance" => '',
				"cash" => '',
				"bank_balance" => '',
				"other" => '',
			);
			$rows =  array_merge( 
					( $pendapatan ),
					$pad,
					$this->budgetPlan_services->get_delimiter(),
					( $belanja ),
					( $belanja_langsung ),
					( $belanja_pegawai_sum ),
					( $belanja_pegawai_honor ),
					( $belanja_pegawai_honorer ),
					$belanja_pegawai,
					$this->budgetPlan_services->get_delimiter(),
					( $belanja_barang_jasa_sum ),
					$belanja_barang_jasa,
					$this->budgetPlan_services->get_delimiter(),
					( $pembiayaan ),
					$pembiayaan_list
			);
			$this->generate_pdf( $rows, $_data );
			return;
		}
		#############################################################################################################################
		#############################################################################################################################
		$print_rba = array(
			"name" => "Print RBA",
			"modal_id" => "print_rba_",
			"button_color" => "primary",
			"url" => site_url( $this->current_page. "detail/$plan_id?pdf=true"),
			"form_data" => array(
				"date" => array(
					'type' => 'date',
					'label' => "Tanggal",
					'value' => date("m/d/Y"),
				),
			),	
			"param"=>"",
		);
		$print_rba["data"] = NULL;
		$this->data[ "modal_form" ] = $this->load->view('templates/actions/modal_form_blank', $print_rba, true );  
		#############################################################################################################################
		#############################################################################################################################
		if( $plan_id == NULL ) redirect( site_url($this->current_page) );
		$alert = $this->session->flashdata('alert');
		$this->session->set_flashdata('alert', '');
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Draf Rencana Belanja Anggaran";
		$this->data["header"] = "Draf  Rencana Belanja Anggaran";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

		$this->render( "finance/planing/detail");
		return;
	}

	public function create()
	{
		$this->form_validation->set_rules( $this->services->validation_config() );
		// $this->form_validation->set_rules( $this->budgetPlan_services->validation_config() );
        if ($this->form_validation->run() === TRUE )
        {
			$data_plan['title'] 		= $this->input->post('title');
			$data_plan['description'] 	= $this->input->post('description');
			$data_plan['year'] 	      	= $this->input->post('year');
			$data_plan['status'] 	      	= $this->input->post('status');


			if( $plan_id = $this->m_plan->add( $data_plan  ) )
			{
				redirect( site_url( $this->current_page )."create_detail/".$plan_id  );
			}
			else
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'Gagal' ) );
			}
			redirect( site_url( $this->current_page )  );
        }
        else
        {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->m_account->errors() : $this->session->flashdata('message')));
			if(  validation_errors() || $this->m_account->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
          
			$alert = $this->session->flashdata('alert');
			$this->session->set_flashdata('alert', '');
			$this->data["key"] = $this->input->get('key', FALSE);
			$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
			$this->data["current_page"] = $this->current_page;
			$this->data["block_header"] = "Buat Draf Rencana Belanja Anggaran";
			$this->data["header"] = "Buat Draf  Rencana Belanja Anggaran";
			$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

			$form["form_data"] = $this->services->form_data(  );
			// echo var_dump( $form );return;
			$this->data[ "form_add" ] = $this->load->view('templates/form/bsb_form', $form , TRUE );  
		
			$this->render( "finance/planing/create");
        }
	}
	public function create_detail( $plan_id = NULL )
	{
		if( $plan_id == NULL ) redirect( site_url($this->current_page) );
		$alert = $this->session->flashdata('alert');
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Draf Rencana Belanja Anggaran";
		$this->data["header"] = "Draf  Rencana Belanja Anggaran";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

		// fetch
		//////////////////////////////////////////////////////////////////////////////////
		$plan = $this->m_plan->plan($plan_id)->row();
		$this->data[ "plan" ] = $plan;
		$form["form_data"] = array(
			"title" => array(
			  'type' => 'text',
			  'readonly' => '',
			  'label' => "Judul",
			),
			"description" => array(
			  'type' => 'text',
			  'label' => "Deskripsi",
			  'readonly' => '',
			  
			),
			"year" => array(
			  'type' => 'number',
			  'label' => "Tahun",
			  'readonly' => '',
			),
		);
		$form["data"] = $plan;
		$this->data[ "form_add" ] = $this->load->view('templates/form/bsb_form', $form , TRUE );  

		//////////////////////////////////////////////////////////////////////////////////
		$modal_budget_plan_add = array(
			"name" => "Tambah",
			"modal_id" => "add_account_",
			"button_color" => "primary",
			"url" => site_url("finance/budget_plan/create"),
			"form_data" => $this->budgetPlan_services->form_data(  ),
			"data" => NULL,
		);
		$additional_form =  array(
			"plan_id" => array(
				'type' => 'hidden',
				'label' => "plan_id",
				'value' => $plan_id,
			),
			"url_return" => array(
				'type' => 'hidden',
				'label' => "plan_id",
				'value' => ("create_detail/".$plan_id ),
			),
		);
		//////////////////////////////////////////////////////////////////////////////////

		//pendapatan
		$this->m_account->get_pendapatan_end_point();
		$pendapatan_end_point_list = $this->m_account->get_account_list();
		///////////////////////////////////////////////////////////////////////////////
		$modal_budget_plan_add[ "modal_id" ] = 'add_pendapatan';
		$modal_budget_plan_add[ "form_data" ] = $this->budgetPlan_services->form_data( -1, $pendapatan_end_point_list );
		$modal_budget_plan_add[ "form_data" ] = array_merge( $modal_budget_plan_add[ "form_data" ], $additional_form );
		$this->data[ "modal_budget_plan_add_pendapatan" ] = $this->load->view('templates/actions/modal_form', $modal_budget_plan_add, true ); 

		///////////////////////////////////////////////////////////////////////////////
		$pendapatan_ids = $this->m_account->get_account_ids_list();
		$pendapatan_rows = $this->m_budget_plan->budget_plans_by_account_ids( $plan_id, $pendapatan_ids )->result();
		$table = $this->budgetPlan_services->get_table_config( "finance/budget_plan/", 1, "create_detail/".$plan_id, $pendapatan_end_point_list  );
		$table[ "rows" ] = $pendapatan_rows;
		$this->data[ "table_pendapatan" ] = $this->load->view('templates/tables/account_table', $table, true);
		//////////////////////////////////////////////////////////////////////////////////

		//Belanja pegawai
		$this->m_account->get_belanja_end_point( ['5.2.1'], 2 );
		$belanja_end_point_list = $this->m_account->get_account_list();
		///////////////////////////////////////////////////////////////////////////////
		$modal_budget_plan_add[ "modal_id" ] = 'add_belanja';
		$modal_budget_plan_add[ "form_data" ] = $this->budgetPlan_services->form_data( -1, $belanja_end_point_list );
		$modal_budget_plan_add[ "form_data" ] = array_merge( $modal_budget_plan_add[ "form_data" ], $additional_form );
		$this->data[ "modal_budget_plan_add_belanja" ] = $this->load->view('templates/actions/modal_form', $modal_budget_plan_add, true ); 

		///////////////////////////////////////////////////////////////////////////////
		$belanja_ids = $this->m_account->get_account_ids_list();
		$belanja_rows = $this->m_budget_plan->budget_plans_by_account_ids( $plan_id, $belanja_ids )->result();
		$table = $this->budgetPlan_services->get_table_config( "finance/budget_plan/", 1, "create_detail/".$plan_id, $belanja_end_point_list  );
		$table[ "rows" ] = $belanja_rows;
		$this->data[ "table_belanja" ] = $this->load->view('templates/tables/account_table', $table, true);
		//////////////////////////////////////////////////////////////////////////////////

		//Belanja barang dan jasa
		$this->m_account->get_belanja_end_point( ['5.2.2', '5.1.2' ], 2 );
		$barang_end_point_list = $this->m_account->get_account_list();
		///////////////////////////////////////////////////////////////////////////////
		$modal_budget_plan_add[ "modal_id" ] = 'add_barang';
		$modal_budget_plan_add[ "form_data" ] = $this->budgetPlan_services->form_data( -1, $barang_end_point_list );
		$modal_budget_plan_add[ "form_data" ] = array_merge( $modal_budget_plan_add[ "form_data" ], $additional_form );
		$this->data[ "modal_budget_plan_add_barang" ] = $this->load->view('templates/actions/modal_form', $modal_budget_plan_add, true ); 

		///////////////////////////////////////////////////////////////////////////////
		$barang_ids = $this->m_account->get_account_ids_list();
		$barang_rows = $this->m_budget_plan->budget_plans_by_account_ids( $plan_id, $barang_ids )->result();
		$table = $this->budgetPlan_services->get_table_config( "finance/budget_plan/", 1, "create_detail/".$plan_id, $barang_end_point_list  );
		$table[ "rows" ] = $barang_rows;
		$this->data[ "table_barang" ] = $this->load->view('templates/tables/account_table', $table, true);
		//////////////////////////////////////////////////////////////////////////////////


		$modal_form_confirm = array(
			"name" => "Selesai",
			"modal_id" => "finish_",
			"button_color" => "success",
			"url" => site_url( $this->current_page. "finish/"),
			"form_data" => array(
				"id" => array(
					'type' => 'hidden',
					'label' => "id",
					'value' => $plan_id,
				  ),
				  "status" => array(
					'type' => 'hidden',
					'label' => "status",
					'value' => 0,
				  ),
			),
		);
		$modal_form_confirm["data"] = NULL;
		$this->data[ "modal_form_finish" ] = $this->load->view('templates/actions/modal_form_confirm', $modal_form_confirm, true ); 
		
		$this->render( "finance/planing/create_detail");

	}
	public function finish( $plan_id = NULL )
	{
		if( !($_POST) ) redirect( site_url($this->current_page) );
  
		$data_plan[ 'status' ] 	= 	$this->input->post('status');
		$data_plan[ 'note' ] 	= 	'';

		$data_param[ 'id' ] 	= 	$this->input->post('id');

		if( $this->m_plan->update( $data_plan, $data_param ) )
		{
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, 'berhasil' ) );
		}else{
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'gagal' ) );
		}
		redirect( site_url( $this->current_page )  );

	}
	public function edit( $plan_id = NULL )
	{
		if( $plan_id == NULL ) redirect(site_url($this->current_page ));
		if( $this->m_plan->is_verified_plan(  $plan_id ) )
		{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'Tidak dapat megubah RBA yang sudah di verifikasi' ) );
			redirect(site_url($this->current_page ));
		}
		$this->form_validation->set_rules( $this->services->validation_config() );
        if ($this->form_validation->run() === TRUE )
        {
			$data_plan['title'] 		= $this->input->post('title');
			$data_plan['description'] 	= $this->input->post('description');
			$data_plan['year'] 	      	= $this->input->post('year');
			$status = 					$this->input->post('status');
			$data_plan['status'] 	    = $this->input->post('status');// ( $status == -1 ) ? 0 : $status ;

			$data_param['id'] 	    = $plan_id;

			// echo var_dump( $data_plan );return;
			if( $this->m_plan->update( $data_plan, $data_param  ) )
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, 'berhasil' ) );	
			}
			else
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'Gagal' ) );
			}
			// return;
			redirect( site_url( $this->current_page ).'edit/'.$plan_id  );
        }
        else
        {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->m_account->errors() : $this->session->flashdata('message')));
			if(  validation_errors() || $this->m_account->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
          
			$alert = $this->session->flashdata('alert');
			$this->session->set_flashdata('alert', '');
			$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
			$this->data["current_page"] = $this->current_page;
			$this->data["block_header"] = "Draf Rencana Belanja Anggaran";
			$this->data["header"] = "Draf  Rencana Belanja Anggaran";
			$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
	
			// fetch
			//fetch data from database
			$plan = $this->m_plan->plan( $plan_id )->row();
			$this->data[ "plan" ] = $plan;
	
			$form["form_data"] = $this->services->form_data( $plan_id  );
			$form["data"] = NULL;
			$this->data[ "form_add" ] = $this->load->view('templates/form/bsb_form', $form , TRUE );  
			/////////////////////////////////////////////////////////////////////

			$modal_budget_plan_add = array(
				"name" => "Tambah",
				"modal_id" => "add_account_",
				"button_color" => "primary",
				"url" => site_url("finance/budget_plan/create"),
				"form_data" => $this->budgetPlan_services->form_data(  ),
				"data" => NULL,
			);
			$additional_form =  array(
				"plan_id" => array(
					'type' => 'hidden',
					'label' => "plan_id",
					'value' => $plan_id,
				),
				"url_return" => array(
					'type' => 'hidden',
					'label' => "plan_id",
					'value' => ("edit/".$plan_id ),
				),
			);
			//////////////////////////////////////////////////////////////////////////////////
	
			//pendapatan
			// $rows = $this->m_budget_plan->budget_plans( $plan_id )->result();
			$this->m_account->get_pendapatan_end_point();
			$pendapatan_end_point_list = $this->m_account->get_account_list();
			///////////////////////////////////////////////////////////////////////////////
			$modal_budget_plan_add[ "modal_id" ] = 'add_pendapatan';
			$modal_budget_plan_add[ "form_data" ] = $this->budgetPlan_services->form_data( -1, $pendapatan_end_point_list );
			$modal_budget_plan_add[ "form_data" ] = array_merge( $modal_budget_plan_add[ "form_data" ], $additional_form );
			$this->data[ "modal_budget_plan_add_pendapatan" ] = $this->load->view('templates/actions/modal_form', $modal_budget_plan_add, true ); 

			///////////////////////////////////////////////////////////////////////////////
			$pendapatan_ids = $this->m_account->get_account_ids_list();
			$pendapatan_rows = $this->m_budget_plan->budget_plans_by_account_ids( $plan_id, $pendapatan_ids )->result();
			// echo var_dump( $this->m_plan->db );return;
			$table = $this->budgetPlan_services->get_table_config( "finance/budget_plan/", 1, "edit/".$plan_id, $pendapatan_end_point_list  );
			$table[ "rows" ] = $pendapatan_rows;
			$this->data[ "table_pendapatan" ] = $this->load->view('templates/tables/account_table', $table, true);
			//////////////////////////////////////////////////////////////////////////////////

			//Belanja pegawai
			$this->m_account->get_belanja_end_point( ['5.2.1'], 2 );
			$belanja_end_point_list = $this->m_account->get_account_list();
			///////////////////////////////////////////////////////////////////////////////
			$modal_budget_plan_add[ "modal_id" ] = 'add_belanja';
			$modal_budget_plan_add[ "form_data" ] = $this->budgetPlan_services->form_data( -1, $belanja_end_point_list );
			$modal_budget_plan_add[ "form_data" ] = array_merge( $modal_budget_plan_add[ "form_data" ], $additional_form );
			$this->data[ "modal_budget_plan_add_belanja" ] = $this->load->view('templates/actions/modal_form', $modal_budget_plan_add, true ); 

			///////////////////////////////////////////////////////////////////////////////
			$belanja_ids = $this->m_account->get_account_ids_list();
			$belanja_rows = $this->m_budget_plan->budget_plans_by_account_ids( $plan_id, $belanja_ids )->result();
			// echo var_dump( $this->m_plan->db );return;
			$table = $this->budgetPlan_services->get_table_config( "finance/budget_plan/", 1, "edit/".$plan_id, $belanja_end_point_list  );
			$table[ "rows" ] = $belanja_rows;

			$this->data[ "table_belanja" ] = $this->load->view('templates/tables/account_table', $table, true);
			//////////////////////////////////////////////////////////////////////////////////

			//Belanja barang dan jasa
			// $rows = $this->m_budget_plan->budget_plans( $plan_id )->result();
			$this->m_account->get_belanja_end_point( ['5.2.2', '5.1.2' ], 2 );
			$barang_end_point_list = $this->m_account->get_account_list();
			///////////////////////////////////////////////////////////////////////////////
			$modal_budget_plan_add[ "modal_id" ] = 'add_barang';
			$modal_budget_plan_add[ "form_data" ] = $this->budgetPlan_services->form_data( -1, $barang_end_point_list );
			$modal_budget_plan_add[ "form_data" ] = array_merge( $modal_budget_plan_add[ "form_data" ], $additional_form );
			$this->data[ "modal_budget_plan_add_barang" ] = $this->load->view('templates/actions/modal_form', $modal_budget_plan_add, true ); 

			///////////////////////////////////////////////////////////////////////////////
			$barang_ids = $this->m_account->get_account_ids_list();
			$barang_rows = $this->m_budget_plan->budget_plans_by_account_ids( $plan_id, $barang_ids )->result();
			// echo var_dump( $this->m_plan->db );return;
			$table = $this->budgetPlan_services->get_table_config( "finance/budget_plan/", 1, "edit/".$plan_id, $barang_end_point_list  );
			$table[ "rows" ] = $barang_rows;
			$this->data[ "table_barang" ] = $this->load->view('templates/tables/account_table', $table, true);

			/////////////////////////////////////////////////////////////////////////////
			// penerimaan
			$this->m_account->get_belanja_end_point( ['7' ], 2 );
			$penerimaan_end_point_list = $this->m_account->get_account_list();
			///////////////////////////////////////////////////////////////////////////////
			$modal_budget_plan_add[ "modal_id" ] = 'add_penerimaan';
			$modal_budget_plan_add[ "form_data" ] = $this->budgetPlan_services->form_data( -1, $penerimaan_end_point_list );
			$modal_budget_plan_add[ "form_data" ] = array_merge( $modal_budget_plan_add[ "form_data" ], $additional_form );
			$this->data[ "modal_budget_plan_add_penerimaan" ] = $this->load->view('templates/actions/modal_form', $modal_budget_plan_add, true ); 
			$penerimaan_ids = $this->m_account->get_account_ids_list();
			$penerimaan_rows = $this->m_budget_plan->budget_plans_by_account_ids( $plan_id, $penerimaan_ids )->result();
			// echo var_dump( $this->m_plan->db );return;
			$table = $this->budgetPlan_services->get_table_config( "finance/budget_plan/", 1, "edit/".$plan_id, $penerimaan_end_point_list  );
			$table[ "rows" ] = $penerimaan_rows;
			$this->data[ "table_penerimaan" ] = $this->load->view('templates/tables/account_table', $table, true);

			/////////////////////////////////////////////////////////////////////////////

			$modal_form_confirm = array(
				"name" => "Selesai",
				"modal_id" => "finish_",
				"button_color" => "success",
				"url" => site_url( $this->current_page. "finish/"),
				"form_data" => array(
					"id" => array(
						'type' => 'hidden',
						'label' => "id",
						'value' => $plan_id,
					  ),
					  "status" => array(
						'type' => 'hidden',
						'label' => "status",
						'value' => ( $plan->status == -1 )? 99 : 0 ,
					  ),
				),
			);
			$modal_form_confirm["data"] = NULL;
			$this->data[ "modal_form_finish" ] = $this->load->view('templates/actions/modal_form_confirm', $modal_form_confirm, true ); 
			
			$this->render( "finance/planing/edit");
        }
	}

	public function delete(  ) {
		if( !($_POST) ) redirect( site_url($this->current_page) );
  
		$data_param[ 'id' ] 	= 	$this->input->post('id');

		if( $this->m_plan->delete( $data_param ) ){
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, 'berhasil' ) );
		}else{
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'gagal' ) );
		}
		redirect( site_url( $this->current_page )  );
		
	}

	protected function generate_pdf( $rows, $_data )
	{

		$this->data = $this->budgetPlan_services->get_table_no_action( "finance/budget_plan/", 1 );
		$this->data['rows'] = $rows;
		$this->data['date'] = $_data->date;
		
		$this->load->library('pdf');
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);

		$pdf->SetTitle( "RBA ".date('d-m-Y', $this->data['date']) );
		
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		 
		$pdf->SetTopMargin(10);
		$pdf->SetLeftMargin(10);
		$pdf->SetRightMargin(10);
		$pdf->SetAutoPageBreak(true);
		$pdf->SetAuthor('BLUD');
		$pdf->SetDisplayMode('real', 'default');
		$pdf->AddPage();
		$pdf->SetFont('times', NULL, 9);

		
		$html = $this->load->view('templates/report/rba_report', $this->data, true);	
		$pdf->writeHTML($html, true, false, true, false, '');
		// Position at 15 mm from bottom
		$pdf->Image( site_url( WATERMARK ) , 10, 280, 30, 5 );

		
		$pdf->Output("RBA ".date('d-m-Y', $this->data['date']).".pdf",'I');
	}
}
