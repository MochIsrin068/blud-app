<?php
require_once APPPATH."/libraries/_Sp3bExcel.php";
defined('BASEPATH') OR exit('No direct script access allowed');
class Generalcash extends Finance_Controller {
	private $services = null;
    private $name = null;
    private $parent_page = 'finance';
    private $current_page = 'finance/generalcash/';
	private $form_data = null;

	public function __construct(){
		parent::__construct();
		$this->data["parent_page"] = $this->parent_page;
		$this->load->model(array( 
			'm_account' ,
			'm_generalcash' ,
			'm_start_balance' ,
			'm_journal' ,
		));
		$this->load->library('services/Generalcash_services');
		$this->services = new Generalcash_services;
	}
	public function index(){
		$page = ($this->uri->segment(4)) ? ($this->uri->segment(4) -  1 ) : 0;
		// echo $page; return;
        //pagination parameter
        $pagination['base_url'] = base_url( $this->current_page ) .'/index';
        $pagination['total_records'] = $this->m_start_balance->record_count() ;
        $pagination['limit_per_page'] = 10;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4;
        //set pagination
		if ($pagination['total_records']>0) $this->data['links'] = $this->setPagination($pagination);

		$this->load->library('services/StartBalance_services');
		$this->services = new StartBalance_services;
		$table = $this->services->get_table_config( $this->current_page, $pagination['start_record'] + 1 );
		$rows = $this->m_start_balance->get_years( $pagination['start_record'], $pagination['limit_per_page'] )->result();
		$key = $this->input->get('key', FALSE);

		$table[ "rows" ] = $rows;
		
		$this->data[ "table" ] = $this->load->view('templates/tables/generalcash_table', $table, true);

		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Kas Umum";
		$this->data["header"] = "Kas Umum";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

		$this->render( "finance/generalcash_2/content");
		return;
	}
	public function year( $year )
	{	
		$this->load->library('services/Generalcash_services');
		$this->services = new Generalcash_services;
		$table = $this->services->get_montly_table_config( $this->current_page."year/".$year."/" , '');
		$rows = $this->m_journal->journals_by_month( $year )->result( );
		// echo var_dump( count( $rows ) );return;
		$table[ "rows" ] = $rows;
		$this->data[ "table" ] = $this->load->view('templates/tables/monthly_table', $table, true);

		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = " Buku Kas Umum Tahun ".$year;
		$this->data["header"] = "Buku Kas Umum ".$year;
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render( "finance/balance/content_year");
		return;
	}
	public function year_month( $year, $month )
	{	
		$pendapatan =  json_decode( file_get_contents( site_url( "api/journal/accumulation?year=".$year."&month=".$month."&group_by=account_id&order=account_prefix|desc&account=4" ) ) );
		// echo var_dump( $pendapatan );return;
		$pembiayaan =  json_decode( file_get_contents( site_url( "api/journal/accumulation?year=".$year."&month=".$month."&group_by=account_id&order=account_prefix|desc&account=7" ) ) );
		$belanja =  json_decode( file_get_contents( site_url( "api/journal/accumulation?year=".$year."&month=".$month."&group_by=account_id&order=account_prefix&account=5" ) ) );

		$pendapatan_sum =  json_decode( file_get_contents( site_url( "api/journal/accumulation?year=".$year."&month=".$month."&order=account_prefix|desc&account=4" ) ) );
		$pembiayaan_sum =  json_decode( file_get_contents( site_url( "api/journal/accumulation?year=".$year."&month=".$month."&order=account_prefix|desc&account=7" ) ) );
		$belanja_sum =  json_decode( file_get_contents( site_url( "api/journal/accumulation?year=".$year."&month=".$month."&order=account_prefix&account=5" ) ) );

		$this->load->library('services/Generalcash_services');
		$this->services = new Generalcash_services;

		$table = $this->services->get_general_cashes_table_config( $this->current_page, $year, $month ) ;

		$debit = $this->services->convert2debit( array_merge( $pembiayaan, $pendapatan ) );
		$kredit = $this->services->convert2kredit( $belanja );

		$debit_sum = $this->services->convert2debit( array_merge( $pendapatan_sum, $pembiayaan_sum ) );
		$kredit_sum = $this->services->convert2kredit( $belanja_sum );
		
		$this_month_sums = $this->services->get_sum( array_merge($debit_sum, $kredit_sum ), 'Jumlah Bulan Ini' );
		$until_last_month_sums = array();
		if( $month == 1 )
		{
			$tx = "Jumlah Sisa Tahun ".($year - 1);			
			$until_last_month_sums = [];
		} 
		else
		{
			$tx =  'Jumlah S/D Bulan Lalu' ;
			$debit_sum =  json_decode( file_get_contents( site_url( "api/journal/accumulation?year=".$year."&month=1-".( $month-1 )."&order=account_prefix|desc&account=7|4" ) ) );
			$kredit_sum =  json_decode( file_get_contents( site_url( "api/journal/accumulation?year=".$year."&month=1-".( $month-1 )."&order=account_prefix&account=5" ) ) );
			$debit_sum = $this->services->convert2debit( $debit_sum );
			$kredit_sum = $this->services->convert2kredit( $kredit_sum );
			$until_last_month_sums[] = $this->services->get_sum( array_merge($debit_sum, $kredit_sum ) );
		}
		$start_balance =  json_decode( file_get_contents( site_url( "api/balance/accumulation?&order=account_prefix&account=1.1.1&group_by=id&year=".$year ) ) );
		$start_balance = $this->services->convert2debit( $start_balance );

		$until_last_month_sums = $this->services->get_sum( array_merge( $start_balance, $until_last_month_sums ), $tx );

		$until_this_month_sums = $this->services->get_sum( array_merge( [$this_month_sums, $until_last_month_sums] ) , "Jumlah S/d Bulan Ini" );
		
		$last_leftover = $until_last_month_sums->debit -  $until_last_month_sums->credit;

		$leftover = $this->services->get_leftover( $until_this_month_sums );
		
		$table[ "rows" ] = array_merge( 
			$kredit, 
			$this->services->get_delimiter_debit( $debit, $year, $month  ),
			$debit ,
			$this->services->get_delimiter() ,
			[ $this_month_sums  ],
			[ $until_last_month_sums  ],
			[ $until_this_month_sums ],
			[ $leftover ]
		);
		$this->data[ "table" ] =  $this->load->view('templates/tables/__generalcash_table', $table, true);

		########################################################################################################################################################################################################
		########################################################################################################################################################################################################
		$print_bku = array(
			"name" => "Print BKU",
			"modal_id" => "verification_",
			"button_color" => "primary",
			"url" => site_url( $this->current_page. "year/".$year."/".$month."?pdf=true"),
			"form_data" => array(
				"date" => array(
					'type' => 'date',
					'label' => "Tanggal",
					'value' => date("m/d/Y"),
				),
				"closing_balance" => array(
					'type' => 'number',
					'label' => "Saldo ditutup pada",
					'value' => $leftover->credit,
				),
				"cash" => array(
					'type' => 'number',
					'label' => "Uang Tunai",
					'value' => 0,
				),
				"bank_balance" => array(
					'type' => 'number',
					'label' => "Saldo Bank",
					'value' => $leftover->credit,
				),
				"other" => array(
					'type' => 'number',
					'label' => "Lain-lain",
					'value' => 0,
				),
			
			),
			"param"=>"",
		);
		$print_bku["data"] = NULL;
		$print_bku = $this->load->view('templates/actions/modal_form_blank', $print_bku, true ); 
		########################################################################################################################################################################################################
		$sp3b_link = array(
			"name" => "Print SP3B",
			"modal_id" => "sp3b_link_",
			"button_color" => "primary",
			"url" => site_url( $this->current_page. "year/".$year."/".$month."?sp3b=true"),
			"form_data" => array(
				"date" => array(
					'type' => 'date',
					'label' => "Tanggal",
					'value' => date("m/d/Y"),
				),
				"closing_balance" => array(
					'type' => 'number',
					'label' => "Saldo ditutup pada",
					'value' => $leftover->credit,
				),			
			),	
			"param"=>"",
		);
		$sp3b_link["data"] = NULL;
		$sp3b_link = $this->load->view('templates/actions/modal_form_blank', $sp3b_link, true );  
		$this->data[ "modal_form_confirm" ] = $sp3b_link." ".$print_bku ;
		########################################################################################################################################################################################################
		########################################################################################################################################################################################################
		if( $this->input->get('pdf', FALSE) )
		{
			if( !($_POST) ) redirect( site_url( $this->current_page. "year/".$year."/".$month."" ) );
			$_data = (object) array(
				"date" => strtotime( $this->input->post("date") ),
				"closing_balance" => ( $this->input->post("closing_balance") ),
				"cash" => ( $this->input->post("cash") ),
				"bank_balance" => ( $this->input->post("bank_balance") ),
				"other" => ( $this->input->post("other") ),
			);
			$rows =  array_merge( 
				$table[ "rows" ] ,
				$this->services->get_delimiter() ,
				$this->services->get_delimiter() ,
				$this->services->get_footer( $leftover, $_data ) 
			);

			$this->generate_pdf( $rows, $_data );
			return;
		}
		########################################################################################################################################################################################################
		########################################################################################################################################################################################################
		if( $this->input->get('sp3b', FALSE) )
		{
			if( !($_POST) ) redirect( site_url( $this->current_page. "year/".$year."/".$month."" ) );
			$this->load->library('services/SP3B_services');
			$this->services = new SP3B_services;
			// echo json_encode( array_merge( $pendapatan,$pembiayaan,$belanja  ) );
			$sp3b = $this->services->get_sp3b( $pendapatan, $belanja, $pembiayaan );
			$sp3b_sum = $this->services->get_sp3b_sum( $pendapatan_sum, $belanja_sum, $pembiayaan_sum  );
			// echo json_encode( array_merge( $sp3b, $sp3b_sum )  );
			$excel = new _Sp3bExcel;
			$_data = (object) array(
				"date" => strtotime( $this->input->post("date") ),
				'start_balance' 	=>  $last_leftover,
				'pendapatan_sum' 	=> ( !empty( $pendapatan_sum ) ) ? $pendapatan_sum[0]->nominal : 0,
				'belanja_sum' 		=> ( !empty( $belanja_sum ) ) ? $belanja_sum[0]->nominal : 0,
				'pembiayaan_sum' 	=> ( !empty( $pembiayaan_sum ) ) ? $pembiayaan_sum[0]->nominal : 0,
			);
			$excel->create( array_merge( $sp3b, $sp3b_sum ) , $_data );
			return;
		}
		########################################################################################################################################################################################################
		########################################################################################################################################################################################################
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Buku Kas Umum ".$this->_month[ $month ]." Tahun ".$year;
		$this->data["header"] = "Buku Kas Umum ".$this->_month[ $month ]." Tahun ".$year;
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

		$this->render( "finance/generalcash_2/report" );
		return;
	}


	public function create(  )
	{
		$this->fornm_validation->set_rules( $this->services->validation_config() );
        if ($this->form_validation->run() === TRUE )
        {

			$data['account_id'] 	= $this->input->post('account_id');
			$data['description'] 	= $this->input->post('description');
			$data['nominal'] 		= $this->input->post('nominal');
			$data['date'] 			= strtotime( $this->input->post('date') );


			if( $this->m_generalcash->add(  $data ) )
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, 'berhasil' ) );
			}
			else
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'Kas Umum Pada Waktu '.date( 'M/Y', $data['date'] ).' sudah ditutup / DPA Belum terbit' ) );
			}
			redirect( site_url( $this->current_page )  );
        }
        else
        {
			$this->data['message'] = (validation_errors() ? validation_errors() : '');
			if(  validation_errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
          
         	$alert = $this->session->flashdata('alert');
			$this->data["key"] = $this->input->get('key', FALSE);
			$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
			$this->data["current_page"] = $this->current_page;
			$this->data["block_header"] = "Isi Kas Umum";
			$this->data["header"] = "Isi Kas Umum";
			$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

			$form["form_data"] = $this->services->form_data(  );
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
			$this->data[ "form_account_dropdown" ] = $this->load->view('templates/form/bsb_form', $form_account_dropdown , TRUE );  
			$this->render( "finance/generalcash_2/create");
        }
	}
	public function detail( $general_cash_id = NULL )
	{
		if( $general_cash_id == NULL ) redirect(site_url($this->current_page ));

			$this->data['message'] = (validation_errors() ? validation_errors() : '');
			if(  validation_errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
          
         	$alert = $this->session->flashdata('alert');
			$this->data["key"] = $this->input->get('key', FALSE);
			$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
			$this->data["current_page"] = $this->current_page;
			$this->data["block_header"] = "Isi Kas Umum";
			$this->data["header"] = "Isi Kas Umum";
			$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

			$generalcash 				= $this->m_generalcash->general_cash( $general_cash_id )->row();
			$form["form_data"] = array(
				"_date" => array(
					'type' => 'text',
					'label' => "Tanggal",
					'readonly' => '',
					// 'value' => ( $this->date ) ? date('Y-m-d', $this->date ) : date('Y-m-d')  ,
					'value' => ( $generalcash->date ) ? date('d/m/Y', $generalcash->date ) : date('m/d/Y')  ,
				),
				"description" => array(
					'type' => 'text',
					'label' => "Deskripsi",
					'readonly' => '',
					'value' => $generalcash->description,
				),
				"nominal" => array(
					'type' => 'number',
					'label' => "Nominal",
					'readonly' => '',
					'value' => $generalcash->nominal,
				),
				"id" => array(
					'type' => 'hidden',
					'label' => "id",
					'value' => $generalcash->id,
				),
			);
			// echo var_dump( $general_cash );return;
			$account 		= $this->m_account->account( $generalcash->account_id )->row();

			$this->data[ "form_add" ] = $this->load->view('templates/form/bsb_form', $form , TRUE );  
			
			$form_account_dropdown['form_data'] = array(
				"account_id" => array(
					'type' => 'text',
					'label' => "No Rekening",
					'readonly' => '',
					'value' =>  $account->name ." (". $account->description .") ",
				),
			);
			$this->data[ "form_account_dropdown" ] = $this->load->view('templates/form/bsb_form', $form_account_dropdown , TRUE );  

			$modal_form_confirm = array(
				"name" => "Kuitansi",
				"modal_id" => "verification_",
				"button_color" => "warning",
				"url" => site_url( $this->current_page. "generate_receipt/".$generalcash->id ),
				"form_data" => array(
					"_date" => array(
						'type' => 'hidden',
						'label' => "Tanggal",
						'readonly' => '',
						'value' => ( $generalcash->date ),
					),
					"description" => array(
						'type' => 'hidden',
						'label' => "Deskripsi",
						'readonly' => '',
						'value' => $generalcash->description,
					),
					"nominal" => array(
						'type' => 'hidden',
						'label' => "Nominal",
						'readonly' => '',
						'value' => $generalcash->nominal,
					),
					"account_name" => array(
						'type' => 'hidden',
						'label' => "Nominal",
						'readonly' => '',
						'value' => $account->name,
					),
					"id" => array(
						'type' => 'hidden',
						'label' => "id",
						'value' => $generalcash->id,
					),
					"spelled_out" => array(
						'type' => 'text',
						'label' => "Nominal Terbilang",
					),
				),
				"param"=>"",
			);
			$modal_form_confirm["data"] = NULL;
			$this->data[ "modal_form_receipt" ] = '';// $this->load->view('templates/actions/modal_form', $modal_form_confirm, true ); 
			
			$this->render( "finance/generalcash_2/detail");
	}

	public function edit( $general_cash_id = NULL )
	{
		if( $general_cash_id == NULL ) redirect(site_url($this->current_page ));

		$this->form_validation->set_rules( $this->services->validation_config() );
        if ($this->form_validation->run() === TRUE )
        {
			$data['account_id'] 	= $this->input->post('account_id');
			$data['description'] 	= $this->input->post('description');
			$data['nominal'] 		= $this->input->post('nominal');
			$data['date'] 			= strtotime( $this->input->post('date') );
			// echo var_dump( $data );return;
			$data_pram['id'] 			= ( $this->input->post('id') );

			if( $this->m_generalcash->update(  $data, $data_pram ) )
			{
				$data['spelled_out'] = $this->input->post('spelled_out');
				// $this->generate_receipt( $data );
				// $this->session->set_flashdata('kuitansi', $data );
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, 'berhasil' ) );
			}
			else
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'Kas Umum Pada Waktu '.date( 'M/Y', $data['date'] ).' sudah ditutup' ) );
			}
			redirect( site_url( $this->current_page )  );
        }
        else
        {
			$this->data['message'] = (validation_errors() ? validation_errors() : '');
			if(  validation_errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
          
         	$alert = $this->session->flashdata('alert');
			$this->data["key"] = $this->input->get('key', FALSE);
			$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
			$this->data["current_page"] = $this->current_page;
			$this->data["block_header"] = "Isi Kas Umum";
			$this->data["header"] = "Isi Kas Umum";
			$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

			$form["form_data"] = $this->services->form_data( $general_cash_id );
			$general_cash 				= $this->m_generalcash->general_cash( $general_cash_id )->row();
			// echo var_dump( $general_cash );return;
			$this->data['account'] 		= $this->m_account->account( $general_cash->account_id )->row();

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
					'selected' => $general_cash->account_id ,
				),
			);
			$this->data[ "form_account_dropdown" ] = $this->load->view('templates/form/bsb_form', $form_account_dropdown , TRUE );  

			$this->render( "finance/generalcash_2/create");
        }
	}


	public function delete(  ) 
	{
		if( !($_POST) ) redirect( site_url($this->current_page) );
  
		$data_param[ 'id' ] 	= 	$this->input->post('id');
		$data['date']			= 	$this->input->post('date');

		if( $this->m_generalcash->delete( $data_param ) ){
		  	$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, 'berhasil' ) );
		}else{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'Kas Umum Pada Waktu '.date( 'M/Y', $data['date'] ).' sudah ditutup Tidak Bisa menghapus Data' ) );
		}
		redirect( site_url( $this->current_page )  );
		
	}

	public function set_serialnumber( $year ) 
	{
		$start_date = strtotime( "01/1/".$year." 00:00:01" );
		$end_date = strtotime( "12/30/".$year." 23:59:00");
		echo date('d m Y', $start_date );
		echo '<br>';
		echo date('d m Y', $end_date );
		echo '<br>';

		$generalcashes		= $this->m_generalcash->general_cashes_periods( $start_date, $end_date )->result();
		// echo var_dump( $generalcashes[0]->serial_number );return;
		foreach( $generalcashes as $ind => $generalcash )
		{
			$data = array();
			$data_param = array();
			$generalcash->serial_number = $ind+1;
			$data = ( array ) $generalcash;
			echo var_dump( $data );
			echo '<br>';
			echo '<br>';
			$data_param['id'] = $generalcash->id;
			$this->m_generalcash->update( $data, $data_param );
		}
				
	}

	public function generate_receipt( $year, $month )
	{	
		if( !($_POST) ) redirect( site_url($this->current_page) );
		$data = array(
			'description' => $this->input->post('description'),
			'nominal' => $this->input->post('nominal'),
			'date' => strtotime( $this->input->post('date') ),
			'spelled_out' => $this->input->post('spelled_out'),
			'account_name' => $this->input->post('account_code'),
		);
		// echo var_dump($data);
		// return;
		
		$this->data = $data;

		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->load->library('pdf');
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);

		$pdf->SetTitle('KUITANSI');
		
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

		
		$html = $this->load->view('templates/report/receipt', $this->data, true);	

		// return;

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->Output("KUITANSI ".$data['account_name'].date('d-m-Y', $data['date']).".pdf");
		// header("Location: ".site_url( $this->current_page ));
	}

	protected function generate_pdf( $rows, $_data )
	{
		$this->load->library('services/Generalcash_services');
		$this->services = new Generalcash_services;

		$this->data = $this->services->get_general_cashes_table_config( $this->current_page, "", "");
		$this->data['rows'] = $rows;
		$this->data['date'] = $_data->date;
		
		$this->load->library('pdf');
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);

		$pdf->SetTitle( "Buku Kas Umum ".date('d-m-Y', $this->data['date']) );
		
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

		
		$html = $this->load->view('templates/report/bookkeeping_report', $this->data, true);	
		// Position at 15 mm from bottom
		$pdf->Image( site_url( WATERMARK ) , 10, 280, 30, 5 );
		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->Output("Buku Kas Umum ".date('d-m-Y', $this->data['date']).".pdf",'I');
	}
	
}
