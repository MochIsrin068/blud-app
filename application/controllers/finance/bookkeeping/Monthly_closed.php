<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/libraries/LraExcel.php";
require_once APPPATH."/libraries/Sp3bExcel.php";
// C:/xampp/htdocs/blud/application/controllers/finance/bookkeeping/Monthly_closed.php
class Monthly_closed extends Finance_Controller {
	private $services = null;
    private $parent_page = 'finance/';
	private $current_page = 'finance/bookkeeping/monthly_closed/';
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
			'm_bookkeeping' ,
		));
		
	}
	public function index()
	{
		$this->load->library('services/MonthlyClosed_services');
		$this->services = new MonthlyClosed_services;
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
		$rows = $this->m_plan->dpa_plans( $pagination['start_record'], $pagination['limit_per_page'] )->result();
		$key = $this->input->get('key', FALSE);
		if( $key ) $rows = $this->m_plan->search( $key  )->result();
		// table
		$table = $this->services->get_dpa_table_config( $this->current_page, $pagination['start_record'] + 1 );
		$table[ "rows" ] = $rows;
		
		$this->data[ "table" ] = $this->load->view('templates/tables/rba_table', $table, true);
		// 
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Buku Kas Umum";
		$this->data["header"] = "Buku Kas Umum";
		$this->data["sub_header"] = '';

		$this->render( "finance/report/realization/content" );
	}

	public function monthly_detail( $plan_id )
	{
		$plan =  $this->m_plan->plan( $plan_id )->row();
		if( $plan->id == NULL )
		{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'terjadi kesalahan' ) );
			redirect(site_url($this->current_page ));
		}
		$this->data["plan"] = $plan;
		$this->load->library('services/MonthlyClosed_services');
		$this->services = new MonthlyClosed_services;

		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Buku Kas Umum Tahun Anggaran ".$plan->year;
		$this->data["header"] = "Buku Kas Umum Tahun Anggaran ".$plan->year;
		$this->data["sub_header"] = $plan->title;

		// $rows = $this->services->get_monthly_rows( $plan_id );
		$rows = $this->m_bookkeeping->bookkeepings( $plan_id )->result() ;
		$table = $this->services->get_montly_table_config( $this->current_page);
		$table[ "rows" ] = $rows;
		
		$this->data[ "table" ] = $this->load->view('templates/tables/monthly_closed_table', $table, true);
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
			"url" => site_url( $this->current_page. "closed_report/"),
			"form_data" => array(
					"month" => array(
						'type' => 'select',
						'label' => "Bulan",
						'options' => $month_select,
					),
					"plan_id" => array(
						'type' => 'hidden',
						'label' => "realization_id",
						'value' => $plan_id,
					),
			),
		);
		$modal_form_generate ["data"] = NULL;
		$this->data[ "modal_form_create" ] = $this->load->view('templates/actions/modal_form', $modal_form_generate, true ); 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$this->render( "finance/report/monthly_closed/detail" );
	}


	// public function closed_report( $plan_id, $month )
	public function closed_report(  )
	{

		$plan_id = $this->input->post('plan_id');
		$month = $this->input->post('month');

		$this->load->library('services/Report_LRA_services');
		$this->LRA_services = new Report_LRA_services;
		$realization = $this->m_realization->realization_by_plan_id( $plan_id )->row();
		// echo var_dump ( $realization ) ;return;
		if( $realization->id == NULL )
		{
			echo 'terjadi kesalahan 1';
			// return;
		}
		// $LRA = $this->LRA_services->generate_lra( $plan_id , $month );
		// $LRA = $this->LRA_services->generate_lra( $realization->id, $month );
		if( $month == 1 )
		{
			$last_LRA = NULL;
		}
		else
		{
			$last_LRA = $this->LRA_services->get_lra( $realization->id, $month - 1 );
			if( $last_LRA == FALSE )
			{
				$last_LRA = NULL;
				if( $month != 12 )
				{
					$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'Generate dahulu Laporan bulan sebelumnya !' ) );
					redirect(site_url($this->current_page."monthly_detail/".$plan_id ));
					return;
				}		
			}
		}
	
		// $table = $this->LRA_services->get_lra_table_config(  );
		// $table[ "rows" ] = $LRA;get_penerimaan_dagur
		// $this->data[ "lra_table" ] = $this->load->view('templates/tables/LRA_table', $table, true);
		// echo $this->data[ "lra_table" ];
		// return;

		$plan =  $this->m_plan->plan( $plan_id )->row();
		if( $plan->id == NULL )
		{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'terjadi kesalahan' ) );
			redirect(site_url($this->current_page ));
		}
		$this->data["plan"] = $plan;
		
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Laporan Tutup Buku Bulan ".$this->_month[ $month ]." Tahun ".$plan->year ;
		$this->data["header"] = "Laporan Tutup Buku Bulan ".$this->_month[ $month ]." Tahun ".$plan->year ;
		$this->data["sub_header"] = '';

		$this->load->library('services/MonthlyClosed_services');
		$this->services = new MonthlyClosed_services;

		$rows = $this->m_generalcash->monthly_general_cashes( $plan->year , $month )->result();
		$table = $this->services->get_general_cashes_table_config( $this->current_page);
		// echo json_encode( $last_LRA );return;
		$rows = $this->services->convert2report(  $rows, $last_LRA );
		$acumulation = $this->services->get_acumulation();
		$table[ "rows" ] = $rows;
		// echo var_dump( $rows );return;
		
		$this->data[ "table" ] = $this->load->view('templates/tables/closed_table', $table, true);
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$modal_form_confirm = array(
			"name" => "Validasi Tutup Buku",
			"modal_id" => "verification_",
			"button_color" => "success",
			"url" => site_url( $this->current_page. "verification/"),
			"form_data" => array(
				"plan_id" => array(
					'type' => 'hidden',
					'label' => "id",
					'value' => $plan_id,
				),
				"debit" => array(
					'type' => 'number',
					'label' => "Penerimaan",
					'value' => $acumulation->debit,
				),
				"credit" => array(
					'type' => 'number',
					'label' => "Pengeluaran",
					'value' => $acumulation->credit,
				),
				"closing_balance" => array(
					'type' => 'number',
					'label' => "Saldo ditutup pada",
					'value' => $acumulation->debit - $acumulation->credit,
				),
				"cash" => array(
					'type' => 'number',
					'label' => "Uang Tunai",
					'value' => 0,
				),
				"bank_balance" => array(
					'type' => 'number',
					'label' => "Saldo Bank",
					'value' => $acumulation->debit - $acumulation->credit,
				),
				"other" => array(
					'type' => 'number',
					'label' => "Lain-lain",
					'value' => 0,
				),
				"date" => array(
					'type' => 'hidden',
					'label' => "tanggal",
					// 'value' => date("m/d/Y"),
					'value' => $month."/28/".$plan->year,
				),
				"month" => array(
					'type' => 'hidden',
					'label' => "Bulan",
					'value' => $month,
				),
			),
			"param"=>"",
		);
		$modal_form_confirm["data"] = NULL;
		$this->data[ "modal_form_confirm" ] = $this->load->view('templates/actions/modal_form', $modal_form_confirm, true ); 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// return;
		$this->render( "finance/report/monthly_closed/report" );
	}
	public function detail_report( $bookkeeping_id )
	{
		$bookkeeping = $this->m_bookkeeping->bookkeeping( $bookkeeping_id )->row();
		if( $bookkeeping->id == NULL )
		{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'terjadi kesalahan' ) );
			redirect( site_url( $this->current_page.'monthly_detail/'.$bookkeeping->plan_id ) );
		}
		$year = date('Y', $bookkeeping->date );
		$month = date('m', $bookkeeping->date );
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Laporan Tutup Buku Bulan ".$this->_month[ (int) $month ]." Tahun ".$year ;
		$this->data["header"] = "Laporan Tutup Buku Bulan ".$this->_month[ (int) $month ]." Tahun ".$year ;
		$this->data["sub_header"] = '';
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->load->library('services/Report_LRA_services');
		$this->LRA_services = new Report_LRA_services;
		$realization = $this->m_realization->realization_by_plan_id( $bookkeeping->plan_id )->row();

		if( $realization->id == NULL )
		{
			echo 'terjadi kesalahan 1';
			return;
		}
		// $LRA = $this->LRA_services->generate_lra( $plan_id , $month );
		// $LRA = $this->LRA_services->generate_lra( $realization->id, $month );
		if( $month == 1 )
		{
			$last_LRA = NULL;
		}
		else
		{
			$last_LRA = $this->LRA_services->get_lra( $realization->id, $month - 1 );
			if( $last_LRA == FALSE )
			{
	
				$last_LRA = NULL;
				if( $month != 12 )
				{
					$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'Generate dahulu Laporan bulan sebelumnya !' ) );
					redirect(site_url($this->current_page."monthly_detail/".$plan_id ));
					return;
				}	
			}
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->load->library('services/MonthlyClosed_services');
		$this->services = new MonthlyClosed_services;

		$rows = $this->m_generalcash->general_cashes_by_bookkeeping_id( $bookkeeping->id )->result();
		$table = $this->services->get_general_cashes_table_config( $this->current_page);
		$rows = $this->services->convert2report(  $rows, $last_LRA );

		$last_balance = $this->services->get_last_balance();

		$rows = array_merge( $rows, $this->services->get_footer( $bookkeeping ) );
		$table[ "rows" ] = $rows;
		// echo var_dump( $rows );return;
		
		$this->data[ "table" ] = $this->load->view('templates/tables/closed_table', $table, true);

		$url_print = '<a target="blank" href="'.site_url( $this->current_page. "detail_report/".$bookkeeping_id."?pdf=true" ).'" style="margin-top:0px !important;padding-top:4px !important;padding-bottom:5px !important;" style="margin-left: 5px;" class=" btn btn-sm btn-primary">Print PDF</a>';
		// $this->data[ "modal_form_confirm" ] = $this->load->view('templates/actions/link', $link, TRUE ); 

		$realization_id = $realization->id;
		$link["url"] = site_url( "finance/report/budget_realization/sp3b_report/". $realization_id .'/'.(int) $month."/".$last_balance );
		$link["button_color"] = 'primary';
		$link["name"] = 'Laporan SP3B';
		$this->data[ "sp3b_link" ] = $this->load->view('templates/actions/link', $link, TRUE ); 
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->data[ "modal_form_confirm" ] = $this->data[ "sp3b_link" ]." ".$url_print ;
		
		$pdf = $this->input->get('pdf', FALSE);
		if( $pdf )
		{
			$this->generate_pdf( $rows, $bookkeeping );
			return;
		}

		$this->render( "finance/report/monthly_closed/report" );

	}

	protected function generate_pdf( $rows, $bookkeeping )
	{	
		$this->load->library('services/MonthlyClosed_services');
		$this->services = new MonthlyClosed_services;

		$this->data = $this->services->get_general_cashes_table_config( $this->current_page);
		$this->data['rows'] = $rows;
		$this->data['date'] = $bookkeeping->date;
		
		// $table[ "rows" ] = $rows;
		// echo var_dump( $rows );return;
		
		// $this->data[ "table" ] = $this->load->view('templates/report/bookkeeping_report_table', $table, true);

		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->load->library('pdf');
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);

		$pdf->SetTitle( "Buku Kas Umum ".date('d-m-Y', $this->data['date']) );
		
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		 
		$pdf->SetTopMargin(10);
		$pdf->SetLeftMargin(10);
		$pdf->SetRightMargin(10);
		//$pdf->setFooterMargin(20);
		$pdf->SetAutoPageBreak(true);
		$pdf->SetAuthor('BLUD');
		$pdf->SetDisplayMode('real', 'default');
		$pdf->AddPage();
		$pdf->SetFont('times', NULL, 9);

		
		$html = $this->load->view('templates/report/bookkeeping_report', $this->data, true);	

		// return;

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->Output("Buku Kas Umum ".date('d-m-Y', $this->data['date']).".pdf",'I');
		// header("Location: ".site_url( $this->current_page ));
	}

	public function verification()
	{
		if( !($_POST) ) redirect( site_url($this->current_page) );

		$plan = $this->m_plan->plan( $this->input->post('plan_id') )->row();

		// if( $plan->status == 2 ) redirect( site_url($this->current_page) );
		$data['plan_id'] 			= $plan->id;
		$data['debit'] 				= $this->input->post('debit');
		$data['credit'] 			= $this->input->post('credit');
		$data[ 'cash' ] 			= $this->input->post('cash');
		$data[ 'bank_balance' ] 	= $this->input->post('bank_balance');
		$data[ 'other' ] 			= $this->input->post('other');
		$data[ 'date' ] 			= strtotime( $this->input->post('date') );
		$data[ 'month' ] 			= date( 'm', $data[ 'date' ] );
		$data[ 'status' ] 			= 1;


		if( $this->create_lra( $plan->id , $data[ 'month' ] )  )
		{
			// echo var_dump( $data );return;
			if( $bookkeeping_id =  $this->m_bookkeeping->add( $data ) )
			{
				$year = date('Y', $data[ 'date' ] );
				$month = date('m', $data[ 'date' ] );
		  
				$start_date = strtotime( $month."/1/".$year." 00:00:01" );
				$end_date = strtotime( $month."/31/".$year." 23:59:00");
				$_data_gcash['bookkeeping_id'] = $bookkeeping_id;
				$data_param['date >'] = $start_date;
				$data_param['date <'] = $end_date;

				$this->m_generalcash->put_bookkeeping_id( $_data_gcash, $data_param  );
			
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, 'berhasil' ) );
			}else{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'gagal' ) );
			}
			// return;
			redirect( site_url( $this->current_page.'monthly_detail/'.$plan->id )  );
			
		}else{
			return;
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'Generate dahulu Laporan bulan sebelumnya !' ) );
			redirect(site_url($this->current_page."monthly_detail/".$plan->id ));
			// return;
		}
	}

	protected function create_lra( $plan_id, $month )
	{
		$this->load->library('services/Report_LRA_services');
		$this->LRA_services = new Report_LRA_services;

		$realization = $this->m_realization->realization_by_plan_id( $plan_id )->row();
		// $last_LRA = $this->LRA_services->get_lra( $realization->id, $month - 1 );
		// if( $last_LRA == FALSE && $month != 1 )
		// {
		// 	return FALSE;
		// }
		$LRA = $this->LRA_services->generate_lra( $realization->id, $month );
		// echo var_dump( $LRA );return;

		if( $LRA == FALSE )
		{
			return FALSE;
		}
		$this->m_budget_realization->add_batch( $LRA );
		return TRUE;
	}

	public function clear( $plan_id )
	{
		$data_param[ 'plan_id' ] 	= $plan_id;

		if( $this->m_bookkeeping->delete( $data_param ) ){
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, 'berhasil' ) );
		}else{
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'gagal' ) );
		}
		redirect( site_url( $this->current_page.'monthly_detail/'.$plan_id )  );
		
	}
	
}
