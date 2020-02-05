<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Generalcash extends Finance_Controller {
	private $services = null;
    private $name = null;
    private $parent_page = 'finance';
    private $current_page = 'finance/dataentry/generalcash/';
	private $form_data = null;
	
	public function __construct(){
		parent::__construct();
		$this->data["parent_page"] = $this->parent_page;
		$this->load->model(array( 
			'm_account' ,
			'm_generalcash' ,
			'm_journal' ,
		));
		$this->load->library('services/Generalcash_services');
		$this->services = new Generalcash_services;
	}
	public function index(){
		$page = ($this->uri->segment(4+1)) ? ($this->uri->segment(4 + 1) -  1 ) : 0;
		// echo $page; return;
        //pagination parameter
        $pagination['base_url'] = base_url($this->current_page) .'/index';
        $pagination['total_records'] = $this->m_generalcash->get_total() ;
        $pagination['limit_per_page'] = 10;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4+1;
        //set pagination
		if ($pagination['total_records']>0) $this->data['links'] = $this->setPagination($pagination);

		$table = $this->services->get_table_config( $this->current_page, $pagination['start_record'] + 1 );
		$rows = $this->m_generalcash->general_cashes( $pagination['start_record'], $pagination['limit_per_page'] )->result();
		$key = $this->input->get('key', FALSE);
		if( $key ) $rows = $this->m_generalcash->search( $key )->result();

		$table[ "rows" ] = $rows;
		// echo var_dump( $rows );return;
		$this->data[ "table" ] = $this->load->view('templates/tables/generalcash_table', $table, true);
		// 
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Kas Umum";
		$this->data["header"] = "Kas Umum";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render( "finance/generalcash/content");

		// if( $this->session->flashdata('kuitansi') )
		// {
		// 	$this->generate_receipt( $this->session->flashdata('kuitansi') );
		// }

	}
	public function create(  )
	{
		$this->form_validation->set_rules( $this->services->validation_config() );
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
			$this->render( "finance/generalcash/create");
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
			
			$this->render( "finance/generalcash/detail");
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

			$this->render( "finance/generalcash/create");
        }
	}


	public function delete(  ) {
		if( !($_POST) ) redirect( site_url($this->current_page) );
  
		$data_param[ 'id' ] 	= 	$this->input->post('id');
		$data['date']	= 	$this->input->post('date');

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
		// echo var_dump( $this->m_generalcash->db );
		// redirect( site_url( $this->current_page )  );
		
	}

	public function generate_receipt(  )
	{	

		$data = array(
			'description' => $this->input->post('description'),
			'nominal' => $this->input->post('nominal'),
			'date' => $this->input->post('date'),
			'spelled_out' => $this->input->post('spelled_out'),
			'account_name' => $this->input->post('account_code'),
		);
		
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
		$pdf->Output("KUITANSI ".$data['account_name'].date('d-m-Y', $data['date']).".pdf",'D');
		// header("Location: ".site_url( $this->current_page ));
	}

	public function accumulation(  )
	{	
		$year = $this->input->get('year');
		echo var_dump( $year );
		echo '<br>';
		$month = $this->input->get('month');
		echo var_dump( $month );
		echo '<br>';
		$account_ids = $this->input->get('account_ids');
		$account_ids = explode( "|", $account_ids );
		echo var_dump( $account_ids );
		echo '<br>';
		$start_date = strtotime( $month."/1/".$year." 00:00:01" );
		$end_date = strtotime( "+ 1 month - 1 day", $start_date ) ;
		echo var_dump( $start_date );
		echo '<br>';  
		echo var_dump( $end_date );
		echo '<br>';  

		// $end_date = date("d M Y ", strtotime( "+ 1 month", $start_date ) );
		// $start_date = date("d M Y ", $start_date );
		echo var_dump( $start_date );
		echo '<br>';  
		echo var_dump( $end_date );
		echo '<br>';  
		echo '<br>';  
		$journals = $this->m_journal->journals( $year, NULL, $start_date, $end_date )->result();
		// echo var_dump( $journals );
		$this->load->library('services/Journal_services');
		$this->services = new Journal_services;

		#####################################################################################################################
		// $table = $this->services->get_journal_table_config('', 1 );
		// $table[ "rows" ] = $journals;
		// echo $this->load->view('templates/tables/start_balance_table', $table, true);
		echo '<br>';  
		$this->m_account->get_start_balance_end_point( $account_ids, 1 );
		$account_ids = $this->m_account->get_account_ids_list();
		// echo var_dump( $account_ids );return;
		// $account_ids = [];
		echo '<br>';  
		$sum = $this->m_journal->sum_journals( $year, $account_ids , $start_date, $end_date )->result();

		$table = $this->services->get_journal_table_config('', 1 );
		$table[ "rows" ] = $sum;
		echo $this->load->view('templates/tables/start_balance_table', $table, true);
		echo var_dump( $sum );
		echo '<br>';  
		echo '<br>';  
		// echo var_dump( $sum[0]->total_nominal );
		echo '<br>';  
		echo '<br>';  
	}
}
