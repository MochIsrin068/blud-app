<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/libraries/LPSAL_excel.php";
class Lpsal extends BpkadHead_Controller {

    private $services = null;
    private $current_page = 'bpkad_head/report/lpsal/';
    
	public function __construct(){
        parent::__construct();
        $this->load->model(array( 
			'm_account' ,
			'm_start_balance' ,
			'm_generalcash' ,
		));
    }

    public function index()
    {
        $this->load->library('services/LPSAL_services');
        $this->services = new LPSAL_services;
        
        $page = ($this->uri->segment(4+1)) ? ($this->uri->segment(4+1) -  1 ) : 0;
        //pagination parameter
        $pagination['base_url'] = base_url($this->current_page) .'index';
        $pagination['total_records'] = $this->m_start_balance->record_count() ;
        $pagination['limit_per_page'] = 10;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4+1;
        //set pagination
		if ($pagination['total_records']>0) $this->data['links'] = $this->setPagination($pagination);

		$table = $this->services->view_table_config( $this->current_page, $pagination['start_record'] + 1 );
        $rows = $this->m_start_balance->get_years( $pagination['start_record'], $pagination['limit_per_page'] )->result();
        $table[ "rows" ] = $rows;
        $this->data[ "table" ] = $this->load->view('templates/tables/generalcash_table', $table, true);

        $alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "LAPORAN PERUBAHAN SALDO ANGGARAN LEBIH";
		$this->data["header"] = "LAPORAN PERUBAHAN SALDO ANGGARAN LEBIH";
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
        $this->data[ "modal_form_add" ] = ''; $this->load->view('templates/actions/modal_form', $modal_form_add, true ); 
        
        $this->render( "finance/report/lpsal/content");
    }

    public function generate()
    {
        $time = strtotime( $this->input->post('date') );
        $year = (int) date("Y", $time ) ;
        $month = (int) date("m", $time ) ;

        $saldo_awal_kas = json_decode( file_get_contents( site_url( "api/balance/accumulation?&order=account_prefix|asc&account=1.1.1&year=".$year ) ) );
        if( is_array( $saldo_awal_kas ) && !empty( $saldo_awal_kas ) )
        {
            $saldo_awal_kas = $saldo_awal_kas[0]->nominal+0;
        }else $saldo_awal_kas = 0; 

        
        $kas_masuk = json_decode( file_get_contents( site_url( "api/journal/accumulation?&order=account_prefix|asc&account=4.1.4&year=".$year."&month=1-".$month ) ) );
        if( is_array( $kas_masuk ) && !empty( $kas_masuk ) )
        {
            $kas_masuk = $kas_masuk[0]->nominal+0;
        }else $kas_masuk = 0; 

        $kas_keluar = json_decode( file_get_contents( site_url( "api/journal/accumulation?&order=account_prefix|asc&account=5&year=2019&month=".$year."&month=1-".$month ) ) );
        if( is_array( $kas_keluar ) && !empty( $kas_keluar ) )
        {
            $kas_keluar = $kas_keluar[0]->nominal +0;
        }else $kas_keluar = 0;

        $pendanaan_masuk = json_decode( file_get_contents( site_url( "api/journal/accumulation?&order=account_prefix|asc&account=7.1.6&year=".$year."&month=1-".$month ) ) );
        if( is_array( $pendanaan_masuk ) && !empty( $pendanaan_masuk ) )
        {
            $pendanaan_masuk = $pendanaan_masuk[0]->nominal +0;
        }else $pendanaan_masuk = 0;

        $pendanaan_keluar = json_decode( file_get_contents( site_url( "api/journal/accumulation?&order=account_prefix|asc&account=7.2.2&year=".$year."&month=1-".$month ) ) );
        if( is_array( $pendanaan_keluar ) && !empty( $pendanaan_keluar ) )
        {
            $pendanaan_keluar = $pendanaan_keluar[0]->nominal + 0;
        }else $pendanaan_keluar = 0;

        $operasi_bersih = $kas_masuk - $kas_keluar;
        $pendanaan_bersih = $pendanaan_masuk  - $pendanaan_keluar;

        $kenaikan_bersih = $operasi_bersih + $pendanaan_bersih;
     
        $date = (object) array(
            "date" => strtotime( $this->input->post('date') ),
        );

        $_data = (object) array(
            'kenaikan_bersih' => $kenaikan_bersih,
        );
        $excel = new LPSAL_excel;
		$excel->create( $_data, $date );
    }
    
}
