<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/libraries/LraExcel.php";
require_once APPPATH."/libraries/Sp3bExcel.php";
class Budget_realization extends Finance_Controller {
	private $services = null;
    private $parent_page = 'finance/';
	private $current_page = 'finance/report/budget_realization/';
	private $_month = array(
        '',
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
    );
	
	public function __construct(){
		parent::__construct();
		$this->load->model(array( 
			'm_account' ,
			'm_plan' ,
			'm_budget_plan' ,
			'm_generalcash' ,
			'm_budget_realization' ,
			'm_realization' ,
		));
		
	}
	public function index()
	{
		$this->load->library('services/Realization_services');
		$this->services = new Realization_services;
		// 
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;
        //pagination parameter
        $pagination['base_url'] = base_url($this->current_page) .'/index';
        $pagination['total_records'] = $this->m_realization->get_total() ;
        $pagination['limit_per_page'] = 14;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4;
        //set pagination
		if ($pagination['total_records']>0) $this->data['links'] = $this->setPagination($pagination);
		
		//fetch data from database
		$rows = $this->m_realization->realizations( $pagination['start_record'], $pagination['limit_per_page'] )->result();
		$key = $this->input->get('key', FALSE);
		if( $key ) $rows = $this->m_plan->search( $key  )->result();
		// table
		$table = $this->services->get_table_config( $this->current_page, $pagination['start_record'] + 1 );
		$table[ "rows" ] = $rows;
		
		$this->data[ "table" ] = $this->load->view('templates/tables/account_table', $table, true);
		// 
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Laporan Realisasi Anggaran";
		$this->data["header"] = "Laporan Realisasi Anggaran";
		$this->data["sub_header"] = '';

		$this->render( "finance/report/realization/content" );
	}

	public function detail_realization( $realization_id )
	{
		$realization =  $this->m_realization->realization( $realization_id )->row();
		if( $realization->id == NULL )
		{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'terjadi kesalahan' ) );
			redirect(site_url($this->current_page ));
		}
		$this->data["realization"] = $realization;
		$this->load->library('services/Realization_services');
		$this->services = new Realization_services;

		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Laporan Realisasi Anggaran";
		$this->data["header"] = "Laporan Realisasi Anggaran";
		$this->data["sub_header"] = $realization->title;

		$rows = $this->m_budget_realization->monthly_budget_realization( $realization_id )->result();
		$table = $this->services->get_montly_table_config( $this->current_page, $realization_id );
		$table[ "rows" ] = $rows;
		
		$this->data[ "table" ] = $this->load->view('templates/tables/monthly_LRA_table', $table, true);

		// $monthly_lra = $this->m_account
		$month_select = array();
		foreach( $this->_month as $ind => $month )
		{
			if( $ind == 0 ) continue;
			$month_select[ $ind ] = $month;
		}
		$modal_form_generate = array(
			"name" => "Generate",
			"modal_id" => "finish_",
			"button_color" => "primary",
			"url" => site_url( $this->current_page. "create_budget_realization/"),
			"form_data" => array(
					"month" => array(
						'type' => 'select',
						'label' => "Bulan",
						'options' => $month_select,
					),
					"year" => array(
						'type' => 'hidden',
						'label' => "Tahun",
						'value' => $realization->year,
					),
					"realization_id" => array(
						'type' => 'hidden',
						'label' => "realization_id",
						'value' => $realization_id,
					),
			),
		);
		$modal_form_generate ["data"] = NULL;
		$this->data[ "modal_form_create" ] = $this->load->view('templates/actions/modal_form', $modal_form_generate, true ); 

		$this->render( "finance/report/realization/detail" );
	}


	public function detail_budget_realization( $realization_id, $month )
	{
		$realization =  $this->m_realization->realization( $realization_id )->row();
		if( $realization->id == NULL )
		{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'terjadi kesalahan' ) );
			redirect(site_url($this->current_page.'budget_realization' ));
		}
		
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Laporan Realisasi Anggaran Bulan ".$this->_month[ $month ] ;
		$this->data["header"] = "Laporan Realisasi Anggaran Bulan ".$this->_month[ $month ] ;
		$this->data["sub_header"] = '';

		$this->load->library('services/Report_LRA_services' );
		$this->services = new Report_LRA_services;
		$LRA = $this->services->get_lra( $realization_id , $month  );
		if( $LRA == FALSE )
		{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'terjadi kesalahan, generate kembali laporan' ) );
			redirect(site_url($this->current_page ));
		}
		// echo var_dump( $this->m_budget_realization->db );return;
		$lra_pendapatan = array_merge( 
					$this->services->get_pendapatan_block(), 
					$LRA->pendapatan ,
					[ $this->services->bold( $this->services->get_sum( $LRA->pendapatan ) ) ]
			  	);
		$lra_pegawai = array_merge( 
					$this->services->get_pegawai_block(), 
					$LRA->pegawai ,
					[$this->services->bold( $this->services->get_sum( $LRA->pegawai ) )]
				);
		$lra_barang = array_merge( 
					$this->services->get_belanja_block(), 
					$LRA->barang ,
					[$this->services->bold( $this->services->get_sum( $LRA->barang ) )]
				);
		$lra_penerimaan = array_merge( 
					$this->services->get_penerimaan_block(), 
					$LRA->penerimaan ,
					[ $this->services->bold( $this->services->get_pembiayaan_netto( $LRA->penerimaan ) )  ]
				);
		$pegawai_barang = $this->services->get_sum( array( $this->services->get_sum( $LRA->pegawai ) , $this->services->get_sum( $LRA->barang ) ) , "( Belanja Pegawai + Belanja Barang )" );
		$table = $this->services->get_lra_table_config(  );
		$table[ "rows" ] = array_merge( $lra_pendapatan, $lra_pegawai, $lra_barang, [ $this->services->bold( $pegawai_barang ) ] , [ $this->services->bold(  $LRA->defisit ) ]  , $lra_penerimaan );
		$this->data[ "lra_table" ] = $this->load->view('templates/tables/LRA_table', $table, true);
		
		if( $this->input->post('export') != NULL )
		{	
			$lra_pendapatan = array_merge( 
						$this->services->get_pendapatan_block( FALSE ), 
						$LRA->pendapatan ,
						[ ( $this->services->get_sum( $LRA->pendapatan ) ) ]
					);
			$lra_pegawai = array_merge( 
						$this->services->get_pegawai_block( FALSE ), 
						$LRA->pegawai ,
						[ ( $this->services->get_sum( $LRA->pegawai ) )]
					);
			$lra_barang = array_merge( 
						$this->services->get_belanja_block( FALSE ), 
						$LRA->barang ,
						[ ( $this->services->get_sum( $LRA->barang ) )]
					);
			$lra_penerimaan = array_merge( 
						$this->services->get_penerimaan_block( FALSE ), 
						$LRA->penerimaan ,
						[ ( $this->services->get_pembiayaan_netto( $LRA->penerimaan ) )  ]
					);
			$pegawai_barang = $this->services->get_sum( array( $this->services->get_sum( $LRA->pegawai ) , $this->services->get_sum( $LRA->barang ) ) , "( Belanja Pegawai + Belanja Barang )" );

			$table[ "rows" ] = array_merge( $lra_pendapatan, $lra_pegawai, $lra_barang, [ ( $pegawai_barang ) ] , [ (  $LRA->defisit ) ]  , $lra_penerimaan );
			$excel = new LraExcel;
			$excel->create( $table[ "rows" ], $realization->year, $this->_month[ $month ] );
			return;
		}
		// return;
		$modal_form_valid = array(
			"name" => "Export",
			"modal_id" => "export_",
			"button_color" => "warning",
			"url" => site_url( $this->current_page. "detail_budget_realization/". $realization_id .'/'. $month ),
			"form_data" => array(
				"export" => array(
					'type' => 'hidden',
					'label' => "export",
					'value' => 1,
				),
			),
		);
		$modal_form_valid["data"] = NULL;
		$this->data[ "modal_form_valid" ] = $this->load->view('templates/actions/modal_form_confirm', $modal_form_valid, true ); 
		
		$link["url"] = site_url( $this->current_page. "sp3b_report/". $realization_id .'/'. (int) $month );
		$link["button_color"] = 'primary';
		$link["name"] = 'Laporan SP3B';
		$this->data[ "sp3b_link" ] = '';// $this->load->view('templates/actions/link', $link, TRUE ); 

		$this->render( "finance/report/realization/LRA" );
	}

	public function sp3b_report( $realization_id, $month, $last_balance  )
	{

		$realization =  $this->m_realization->realization( $realization_id )->row();
		if( $realization->id == NULL )
		{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'terjadi kesalahan' ) );
			redirect(site_url($this->current_page.'budget_realization' ));
		}
		
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Laporan Realisasi Anggaran Bulan ".$this->_month[ $month ] ;
		$this->data["header"] = "Laporan Realisasi Anggaran Bulan ".$this->_month[ $month ] ;
		$this->data["sub_header"] = '';

		$this->load->library('services/Report_LRA_services' );
		$this->services = new Report_LRA_services;
		$LRA = $this->services->get_lra( $realization_id , $month  );
		if( $LRA == FALSE )
		{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'terjadi kesalahan, generate kembali laporan' ) );
			redirect(site_url($this->current_page.'budget_realization' ));
		}
		$pendapatan = $LRA->pendapatan;
		$belanja = array_merge( $LRA->pegawai, $LRA->barang );
		$penerimaan = $LRA->penerimaan;
		$sums = array(
			$this->services->get_sum( $pendapatan )->this_month,
			$this->services->get_sum( $belanja )->this_month,
			$this->services->get_sum( $penerimaan )->this_month,
		);
		$this->load->library('services/Report_SP3B_services' );
		$this->services = new Report_SP3B_services;

		$sp3b = $this->services->get_sp3b( $pendapatan, $belanja, $penerimaan );
		$excel = new Sp3bExcel;
		$excel->create( $sp3b, $realization->year, $this->_month[ $month ], $last_balance, $sums );
		// echo json_encode( $sp3b );
	}

	public function create_budget_realization(  )
	{
		$year = $this->input->post('year');
		$month =$this->input->post('month');

		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Laporan Realisasi Anggaran Bulan ".$this->_month[ $month ]." Tahun ".$year;
		$this->data["header"] = "Laporan Realisasi Anggaran Bulan ".$this->_month[ $month ]." Tahun ".$year;
		$this->data["sub_header"] = '';

		$realization_id = $this->input->post('realization_id');

		
		$start_date = strtotime( $month."/1/".$year." 00:00:01" );
		$end_date = strtotime( $month."/31/".$year." 23:59:00");
		
		$plan = $this->m_plan->plan_by_year( $year )->row();
		if( $plan->id == NULL )
		{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'DPA Belum ada' ) );
			redirect(site_url($this->current_page.'budget_realization' ));
		} 
		if( $plan->status != 2 )
		{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'DPA Belum di verifikasi BPKAD' ) );
			redirect(site_url($this->current_page.'budget_realization' ));
		}

		if( ! $this->m_budget_realization->is_monthly_exist( $month - 1, $realization_id )  &&  $month != 1 )
		{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'Generate dahulu LRA bulan sebelumnya !' ) );
			redirect(site_url($this->current_page."detail_realization/".$realization_id ));
		}

		$this->load->library('services/Report_LRA_services' );
		$this->services = new Report_LRA_services;

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$DATA_LIST = array();
		// pendapatan
		$pendapatan_budget_plans = $this->services->get_pendapatan_budget_plans( $plan->id );
		$pendapatan_generalcash =  $this->m_generalcash->general_cashes_account_ids( $pendapatan_budget_plans->account_ids ,$start_date, $end_date  )->result() ;
		// echo var_dump( $pendapatan_generalcash );return;
		$lra_pendapatan = $this->services->get_pendapatan_block();
		$rows = $this->services->sync_realization( $pendapatan_budget_plans->budget_plans , $pendapatan_generalcash, $month, $realization_id );
		$lra_pendapatan = array_merge( $lra_pendapatan, $rows );
		$sum_pendapatan = $this->services->get_sum($rows);
		array_push( $lra_pendapatan,  $this->services->bold( $sum_pendapatan ) );
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$DATA_LIST = array_merge( $DATA_LIST, $rows );
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// pegawai
		$pegawai_budget_plans = $this->services->get_pegawai_budget_plans( $plan->id );
		$pegawai_generalcash =  $this->m_generalcash->general_cashes_account_ids( $pegawai_budget_plans->account_ids ,$start_date, $end_date  )->result() ;
		$lra_pegawai = $this->services->get_pegawai_block();
		$rows = $this->services->sync_realization( $pegawai_budget_plans->budget_plans , $pegawai_generalcash, $month, $realization_id );
		$lra_pegawai = array_merge( $lra_pegawai, $rows );
		$sum_pegawai = $this->services->get_sum($rows);
		array_push( $lra_pegawai,  $this->services->bold( $sum_pegawai ) );
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$DATA_LIST = array_merge( $DATA_LIST, $rows );
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// barang dan jasa
		$barang_budget_plans = $this->services->get_barang_budget_plans( $plan->id );
		$barang_generalcash =  $this->m_generalcash->general_cashes_account_ids( $barang_budget_plans->account_ids ,$start_date, $end_date  )->result() ;
		$lra_barang =  $this->services->get_belanja_block();
		$rows = $this->services->sync_realization( $barang_budget_plans->budget_plans , $barang_generalcash, $month, $realization_id );
		
		$lra_barang = array_merge( $lra_barang, $rows );
		$sum_barang = $this->services->get_sum($rows );
		array_push( $lra_barang,  $this->services->bold( $sum_barang ) );
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$DATA_LIST = array_merge( $DATA_LIST, $rows );
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// penerimaan
		$penerimaan_budget_plans = $this->services->get_penerimaan_budget_plans( $plan->id );
		$penerimaan_generalcash =  $this->m_generalcash->general_cashes_account_ids( $penerimaan_budget_plans->account_ids ,$start_date, $end_date  )->result() ;
		$lra_penerimaan =  $this->services->get_penerimaan_block();
		$rows = $this->services->sync_realization( $penerimaan_budget_plans->budget_plans , $penerimaan_generalcash, $month, $realization_id );
		$lra_penerimaan = array_merge( $lra_penerimaan, $rows );
		$sum = $this->services->get_pembiayaan_netto($rows);
		array_push( $lra_penerimaan, $this->services->bold( $sum )  );
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$DATA_LIST = array_merge( $DATA_LIST, $rows );
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// defisit
		$defisit = $this->services->get_defisit( $sum_pendapatan, $this->services->get_sum( array( $sum_pegawai, $sum_barang ) ) , $realization_id,  $month );
		$pegawai_barang = $this->services->get_sum( array( $sum_pegawai, $sum_barang ) , "( Belanja Pegawai + Belanja Barang )" );
		$DATA_LIST = array_merge( $DATA_LIST, [ $defisit ] );
		// defisit

		$table = $this->services->get_lra_table_config(  );
		$table[ "rows" ] = array_merge( $lra_pendapatan, $lra_pegawai, $lra_barang, [ $this->services->bold( $pegawai_barang ) ] , [ $this->services->bold( $defisit ) ] , $lra_penerimaan );
		// $table[ "rows" ] = $DATA_LIST;
		$this->data[ "lra_table" ] = $this->load->view('templates/tables/LRA_table', $table, true);
		// echo json_encode( $DATA_LIST );return;

		if( $this->input->post('validate') != NULL )
		{
			$this->m_budget_realization->add_batch( $DATA_LIST );
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, 'Berhasil Validasi' ) );
			redirect(site_url($this->current_page."detail_realization/".$realization_id ));
		}
		// return;
		$modal_form_valid = array(
			"name" => "Validasi",
			"modal_id" => "validate_",
			"button_color" => "success",
			"url" => site_url( $this->current_page. "create_budget_realization/"),
			"form_data" => array(
				"validate" => array(
					'type' => 'hidden',
					'label' => "validate",
					'value' => 1,
				),
				"month" => array(
					'type' => 'hidden',
					'label' => "Bulan",
					'value' => $month,
				),
				"year" => array(
					'type' => 'hidden',
					'label' => "Tahun",
					'value' => $year,
				),
				"realization_id" => array(
					'type' => 'hidden',
					'label' => "realization_id",
					'value' => $realization_id,
				),
			),
		);
		$modal_form_valid["data"] = NULL;
		$this->data[ "modal_form_valid" ] = $this->load->view('templates/actions/modal_form_confirm', $modal_form_valid, true ); 
		$this->data[ "sp3b_link" ] = ''; 
		// return;
		$this->render( "finance/report/realization/LRA" );
	}

	public function clear( $realization_id )
	{
		$data_param[ 'realization_id' ] 	= $realization_id;

		if( $this->m_budget_realization->delete( $data_param ) ){
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, 'berhasil' ) );
		}else{
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'gagal' ) );
		}
		redirect( site_url( $this->current_page."detail_realization/".$realization_id )  );
	}
	
}
