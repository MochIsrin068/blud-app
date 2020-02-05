<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Balance extends Finance_Controller {
	private $services = null;
    private $name = null;
    private $parent_page = 'finance';
    private $current_page = 'finance/balance/';
	private $form_data = null;

	private $types = NULL;
	
	public function __construct(){
		parent::__construct();
		$this->data["parent_page"] = $this->parent_page;
		$this->load->model(array( 
			'm_account' ,
			'm_start_balance' ,
			'm_generalcash' ,
		));
		$this->load->library('services/StartBalance_services');
		$this->services = new StartBalance_services;

		$this->types =array(
			"aktiva" => (object) array(
				"name" => "Aktiva",
				"list" => array(
					(object) array(
						"name" => 'Aktiva Lancar',
						"account_name" => [ '1.1' ],
						"fetch_depth" => 1,
					),
				),
			),
			"aset_tetap" => (object) array(
				"name" => "Aset Tetap",
				"list" => array(
					(object) array(
						"name" => 'Aset Tetap',
						"account_name" => [ '2.2' ],
						"fetch_depth" => 1,
					),
				),
			),
			"kewajiban" => (object) array(
				"name" => "Kewajiban",
				"list" => array(
					(object) array(
						"name" => 'Ekuitas',
						"account_name" => [ '3.1' ],
						"fetch_depth" => 1,
					),
				),
			),
			"pendapatan" =>  (object) array(
				"name" => "Pendapatan",
				"list" => array(
					(object) array(
						"name" => 'Pendapatan',
						"account_name" => [ '4' ],
						"fetch_depth" => 2,
					),
				),
			),
			"belanja" =>  (object) array(
				"name" => "Belanja",
				"list" => array(
					(object) array(
						"name" => 'Belanja Pegawai',
						"account_name" => [ '5.2.1' ],
						"fetch_depth" => 2,
					),
					(object) array(
						"name" => 'Belanja Barang dan Jasa',
						"account_name" => [ '5.2.2', '5.1.2' ],
						"fetch_depth" => 2,
					),
				),
			),
		);
	}
	public function index(){
		$page = ($this->uri->segment(4)) ? ($this->uri->segment(4) -  1 ) : 0;
		// echo $page; return;
        //pagination parameter
        $pagination['base_url'] = base_url($this->current_page) .'index';
        $pagination['total_records'] = $this->m_start_balance->record_count() ;
        $pagination['limit_per_page'] = 10;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4;
        //set pagination
		if ($pagination['total_records']>0) $this->data['links'] = $this->setPagination($pagination);

		$table = $this->services->get_table_config( $this->current_page, $pagination['start_record'] + 1 );
		$rows = $this->m_start_balance->get_years( $pagination['start_record'], $pagination['limit_per_page'] )->result();
		$key = $this->input->get('key', FALSE);
		// if( $key ) $rows = $this->m_start_balance->search( $key )->result();

		$table[ "rows" ] = $rows;
		// echo var_dump( $rows );return;
		$this->data[ "table" ] = $this->load->view('templates/tables/generalcash_table', $table, true);
		// 
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Saldo Awal";
		$this->data["header"] = "Saldo Awal";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

		$modal_form_add = array(
			"name" => "Buat Saldo Awal",
			"modal_id" => "add_party_",
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
		$this->data[ "modal_form_add" ] = $this->load->view('templates/actions/modal_form', $modal_form_add, true ); 
		
		$this->render( "finance/balance/content");

	}
	public function create(  )
	{
		if( !($_POST) ) redirect( site_url($this->current_page) );
		
		$year	= $this->input->post('year');
		redirect( site_url($this->current_page."year/".$year ) );
	}
	public function create_balance(  )
	{
		if( !($_POST) ) redirect( site_url($this->current_page) );
		$year = $this->input->post('year');
		$type = $this->input->post('type');
		$this->form_validation->set_rules( $this->services->validation_config() );

        if ($this->form_validation->run() === TRUE )
        {
			$data[ 'year' ] 	= 	$this->input->post('year');
			$data[ 'date' ] 	= 	strtotime( date('1/31/'.$data[ 'year' ] ) );
			$data[ 'account_id' ] 	= 	$this->input->post('account_id');
			$data[ 'position' ] 	= 	$this->input->post('position');
			$data[ 'nominal' ] 	= (double)	$this->input->post('nominal');

			if( $this->m_start_balance->create(  $data ) )
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, 'berhasil' ) );
			}
			else
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'Gagal' ) );
			}
			redirect( site_url($this->current_page."year/".$year."/".$type ) );
        }
        else
        {
			$this->data['message'] = (validation_errors() ? validation_errors() : '');
			if(  validation_errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
			
			redirect( site_url($this->current_page."year/".$year."/".$type ) );
        }
	}
	public function edit(  )
	{
		if( !($_POST) ) redirect( site_url($this->current_page) );

		$year = $this->input->post('year');
		$type = $this->input->post('type');
		$this->form_validation->set_rules( 'nominal', "nominal", 'trim|required' );
		$this->form_validation->set_rules( 'id', 'id', 'trim|required' );

        if ( $this->form_validation->run() === TRUE )
        {
			$data[ 'nominal' ] 	= (double)	$this->input->post('nominal');
			$data[ 'position' ] 	= 	$this->input->post('position');
			
			$data_param[ 'id' ] 	= $this->input->post('id');
			// echo var_dump( $data_param );return;
			if( $this->m_start_balance->update( $data, $data_param  ) )
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, 'berhasil' ) );
			}
			else
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'Gagal' ) );
			}
			redirect( site_url($this->current_page."year/".$year."/".$type ) );
        }
        else
        {
			$this->data['message'] = (validation_errors() ? validation_errors() : '');
			if(  validation_errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
			// echo var_dump( $this->input->post('nominal') );return;
			redirect( site_url($this->current_page."year/".$year."/".$type ) );
        }
	}

	public function year_detail( $year, $type )
	{
		$modal_balance_add = array(
			"name" => "Tambah",
			"modal_id" => "add_account_",
			"button_color" => "primary",
			"url" => site_url("finance/balance/create_balance"),
			// "form_data" => $this->services->form_data(  ),
			"data" => NULL,
		);
		$additional_form =  array(
			"year" => array(
				'type' => 'hidden',
				'label' => "Tahun",
				'readonly' => "",
				'value' => $year,
			),
			"type" => array(
				'type' => 'hidden',
				'label' => "Tahun",
				'readonly' => "",
				'value' => $type,
			),
		);
		
		$DATA_BALANCE = array();
		// echo var_dump(  $this->types[ $type ]->list );
		// return;

		foreach( $this->types[ $type ]->list as $ind => $_type )
		{
			// echo var_dump(  $_type->account_name );
			$this->m_account->get_start_balance_end_point( $_type->account_name, $_type->fetch_depth );
			$end_point_list = $this->m_account->get_account_list();
			$modal_balance_add[ "modal_id" ] = 'modal_add'.$ind;
			$modal_balance_add[ "form_data" ] = $this->services->form_data( $end_point_list );
			$modal_balance_add[ "form_data" ] = array_merge( $modal_balance_add[ "form_data" ], $additional_form );

			$MODAL = $this->load->view('templates/actions/modal_form', $modal_balance_add, true ); 
			##########################################
			$account_ids = $this->m_account->get_account_ids_list();
			$rows = $this->m_start_balance->start_balances_by_account_ids( $year, $account_ids )->result();
			$table = $this->services->get_balance_table_config( "finance/balance/", 1, $end_point_list, $type);
			$table[ "rows" ] = $rows;

			$TABLE = $this->load->view('templates/tables/start_balance_table', $table, true);
			##########################################
			$DATA_BALANCE[]= (object) array(
				"name" => $_type->name, 
				"modal" => $MODAL, 
				"table" => $TABLE, 
			);
		}
		$this->data["LISTS"] = $DATA_BALANCE;

		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Buat Saldo Awal Tahun ".$year;
		$this->data["header"] = $this->types[ $type ]->name;
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

		$this->data[ "DATA_BALANCE" ] = $DATA_BALANCE;

		$this->render( "finance/balance/create_balance");
	}
	public function year( $year )
	{	
		$table = $this->services->get_type_table_config( $this->current_page."year/".$year."/" , '');
		$rows = $this->services->get_type_rows( );
		$table[ "rows" ] = $rows;
		$this->data[ "table" ] = $this->load->view('templates/tables/start_balance_table', $table, true);

		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Buat Saldo Awal Tahun ".$year;
		$this->data["header"] = "Saldo Awal ".$year;
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render( "finance/balance/content_year");
		return;
	}


	public function delete(  ) 
	{
		if( !($_POST) ) redirect( site_url($this->current_page) );
  
		$data_param[ 'id' ] 	= 	$this->input->post('id');
		$year = $this->input->post('year');
		$type = $this->input->post('type');
		if( $this->m_start_balance->delete( $data_param ) ){
		  	$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, 'berhasil' ) );
		}else{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, 'Saldo Awal Pada Waktu '.date( 'M/Y', $data['date'] ).' sudah ditutup Tidak Bisa menghapus Data' ) );
		}
		redirect( site_url($this->current_page."year/".$year."/".$type ) );
		
	}

}
