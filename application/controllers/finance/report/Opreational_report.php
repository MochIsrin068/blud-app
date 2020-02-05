<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/libraries/LO_excel.php";
// C:/xampp/htdocs/blud/application/controllers/finance/report/Lpsal.php
class Opreational_report extends Finance_Controller {
    private $services = null;
    private $name = null;
    private $parent_page = 'finance';
    private $current_page = 'finance/report/opreational_report/';
    private $form_data = null;
    
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
        $this->load->library('services/LO_services');
        $this->services = new LO_services;
        
        $page = ($this->uri->segment(4+1)) ? ($this->uri->segment(4+1) -  1 ) : 0;
        //pagination parameter
        $pagination['base_url'] = base_url($this->current_page) .'index';
        $pagination['total_records'] = $this->m_start_balance->record_count() ;
        $pagination['limit_per_page'] = 10;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4+1;
        //set pagination
		if ($pagination['total_records']>0) $this->data['links'] = $this->setPagination($pagination);

		$table = $this->services->year_table_config( $this->current_page, $pagination['start_record'] + 1 );
        $rows = $this->m_start_balance->get_years( $pagination['start_record'], $pagination['limit_per_page'] )->result();
        $table[ "rows" ] = $rows;
        $this->data[ "table" ] = $this->load->view('templates/tables/generalcash_table', $table, true);

        $alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "LAPORAN OPERASIONAL";
		$this->data["header"] = "LAPORAN OPERASIONAL";
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
        $semester = ( $this->input->post('semester') );
        $time = strtotime( $this->input->post('date') );
        $year = (int) date("Y", $time ) ;
        $month = (int) date("m", $time ) ;

        $beban_pegawai =  json_decode( file_get_contents( site_url( "api/journal/accumulation?&order=account_prefix|desc&account=9&month=&group_by=&year=".$year."&month=1-6".( $semester * 6 )   ) ) );
        if( is_array( $beban_pegawai ) && !empty( $beban_pegawai ) )
        {
            $beban_pegawai = $beban_pegawai[0]->nominal+0;
        }else $beban_pegawai = 0; 
      

        $pendapatan_sum =  json_decode( file_get_contents( site_url( "api/journal/accumulation?&order=account_prefix|desc&account=4.1.4&year=".$year."&month=1-".( $semester * 6 ) ) ) );
        if( is_array( $pendapatan_sum ) && !empty( $pendapatan_sum ) )
        {
            $pendapatan_sum = $pendapatan_sum[0]->nominal+0;
        }else $pendapatan_sum = 0; 

        $belanja_barang_sum =  json_decode( file_get_contents( site_url( "api/journal/accumulation?&order=account_prefix|desc&account=5.2.2&year=".$year."&month=1-".( $semester * 6 ) ) ) );
        if( is_array( $belanja_barang_sum ) && !empty( $belanja_barang_sum ) )
        {
            $belanja_barang_sum = $belanja_barang_sum[0]->nominal+0;
        }else $belanja_barang_sum = 0; 
        ##########################################################################################################################################################################################################################################
        ##########################################################################################################################################################################################################################################
        $penyisihan_piutang_sum =  json_decode( file_get_contents( site_url( "api/balance/accumulation?&order=account_prefix|desc&account=1.1.3&year=".$year ) ) );
        if( is_array( $penyisihan_piutang_sum ) && !empty( $penyisihan_piutang_sum ) )
        {
            $penyisihan_piutang_sum = $penyisihan_piutang_sum[0]->nominal+0;
        }else $penyisihan_piutang_sum = 0; 

        $penysutan_aset_tetap_sum =  json_decode( file_get_contents( site_url( "api/balance/accumulation?&order=account_prefix|desc&account=1.2.5&year=".$year ) ) );
        if( is_array( $penysutan_aset_tetap_sum ) && !empty( $penysutan_aset_tetap_sum ) )
        {
            $penysutan_aset_tetap_sum = $penysutan_aset_tetap_sum[0]->nominal+0;
        }else $penysutan_aset_tetap_sum = 0; 
        $jumlah_pendapatan = $beban_pegawai + $pendapatan_sum;
  
        $jumlah_beban = $beban_pegawai + $belanja_barang_sum + $penyisihan_piutang_sum + $penysutan_aset_tetap_sum;
     
        $surplus_defisit_lo = $jumlah_pendapatan - $penysutan_aset_tetap_sum;
      
        $date = strtotime( $this->input->post('date') );
        $_data = (object) array(
            "beban_pegawai" => $beban_pegawai,
            "pendapatan" => $pendapatan_sum,
            "belanja_barang" => $belanja_barang_sum,
            // "penyisihan_piutang" => $penyisihan_piutang_sum,
            "penyisihan_piutang" => 125230300,
            // "penysutan_aset_tetap" => $penysutan_aset_tetap_sum,
            "penysutan_aset_tetap" => 6502634,
        );

        $excel = new LO_excel;
		$excel->create( $_data, $date, $semester );
    }
    
}
