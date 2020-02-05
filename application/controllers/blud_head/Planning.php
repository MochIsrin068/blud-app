<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Planning extends BludHead_Controller {
	private $services = null;
	private $budgetPlan_services = null;
    private $name = null;
    private $parent_page = 'blud_head';
    private $current_page = 'blud_head/planning/';
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
		$this->load->library('services/BludHeadPlaning_services');
		$this->services = new BludHeadPlaning_services;
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
		$key = $this->input->get('key', FALSE);
		if( $key ) $rows = $this->m_plan->search( $key  )->result();
		// table
		$table = $this->services->get_bludHead_table_config( $this->current_page, $pagination['start_record'] + 1 );
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
		
		$this->render( "blud_head/planning/content");
	}

	public function detail( $plan_id = NULL )
	{
		$plan = $this->m_plan->plan($plan_id)->row();
		if( $plan->id == NULL )
		{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'terjadi kesalahan' ) );
			redirect(site_url($this->current_page ));
		}
		#############################################################################################################################
		#############################################################################################################################
		$pendapatan_sum =  json_decode( file_get_contents( site_url( "api/budget_plan/accumulation?&order=account_prefix|desc&account=4&year=".$plan->year ) ) );
		$pendapatan = $this->budgetPlan_services->set_row( $pendapatan_sum, '4', 'Pendapatan' );
		$pad =  json_decode( file_get_contents( site_url( "api/budget_plan/accumulation?&order=account_prefix|desc&account=4.1&group_by=id&year=".$plan->year ) ) );
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

		$modal_form_confirm = array(
			"name" => "Verifikasi RBA",
			"modal_id" => "verification_",
			"button_color" => "success",
			"url" => site_url( $this->current_page. "verification/"),
			"form_data" => array(
				"id" => array(
					'type' => 'hidden',
					'label' => "id",
				  ),
				  "status" => array(
					'type' => 'hidden',
					'label' => "status",
					'value' => 1,
				  ),
			),
			"param"=>"",
		);
		$modal_form_confirm["data"] = $plan;
		$this->data[ "modal_form_confirm" ] = $this->load->view('templates/actions/modal_form_confirm', $modal_form_confirm, true ); 

		$modal_form_reject = array(
			"name" => "Tolak RBA",
			"modal_id" => "rejection_",
			"button_color" => "danger",
			"url" => site_url( $this->current_page. "rejection/"),
			"form_data" => array(
				"id" => array(
					'type' => 'hidden',
					'label' => "id",
				  ),
				  "status" => array(
					'type' => 'hidden',
					'label' => "status",
					'value' => -1,
				  ),
				  "note" => array(
					'type' => 'textarea',
					'label' => "Catatan",
				  ),
			),
			"param"=>"",
		);
		$modal_form_reject["data"] = $plan;
		$this->data[ "modal_form_reject" ] = $this->load->view('templates/actions/modal_form', $modal_form_reject, true ); 
		
		$this->render( "blud_head/planning/detail");
	}

	public function verification(  ) 
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
	public function rejection(  ) 
	{
		if( !($_POST) ) redirect( site_url($this->current_page) );
  
		$data_plan[ 'status' ] 	= 	$this->input->post('status');
		$data_plan[ 'note' ] 	= 	$this->input->post('note');

		$data_param[ 'id' ] 	= 	$this->input->post('id');

		if( $this->m_plan->update( $data_plan, $data_param ) )
		{
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, 'berhasil' ) );
		}else{
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'gagal' ) );
		}
		redirect( site_url( $this->current_page )  );
	}
}
