<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/libraries/Neraca_excel.php";
// C:/xampp/htdocs/blud/application/controllers/finance/report/Lpsal.php
class Neraca_report extends BpkadHead_Controller {
    private $services = null;
    private $current_page = 'bpkad_head/report/neraca_report/';
    
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
        $this->load->library('services/Neraca_services');
        $this->services = new Neraca_services;
        
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

		$table = $this->services->view_table_config( $this->current_page, $pagination['start_record'] + 1 );
        $rows = $this->m_start_balance->get_years( $pagination['start_record'], $pagination['limit_per_page'] )->result();
        $table[ "rows" ] = $rows;
        $this->data[ "table" ] = $this->load->view('templates/tables/generalcash_table', $table, true);

        $alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "LAPORAN NERACA";
		$this->data["header"] = "LAPORAN NERACA";
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

        ########################################################################################################################################################################################################
        $kas_dan_bank_sum =  json_decode( file_get_contents( site_url( "api/balance/accumulation?&order=account_prefix|desc&account=1.1.1&year=".$year ) ) );
        if( is_array( $kas_dan_bank_sum ) && !empty( $kas_dan_bank_sum ) )
        {
            $kas_dan_bank_sum = $kas_dan_bank_sum[0]->nominal+0;
        }else $kas_dan_bank_sum = 0; 

        $piutang_usaha_sum =  json_decode( file_get_contents( site_url( "api/balance/accumulation?&order=account_prefix|desc&account=1.1.2&year=".$year ) ) );
        if( is_array( $piutang_usaha_sum ) && !empty( $piutang_usaha_sum ) )
        {
            $piutang_usaha_sum = $piutang_usaha_sum[0]->nominal+0;
        }else $piutang_usaha_sum = 0; 

        $penyisihan_piutang_sum =  json_decode( file_get_contents( site_url( "api/balance/accumulation?&order=account_prefix|desc&account=1.1.3&year=".$year ) ) );
        if( is_array( $penyisihan_piutang_sum ) && !empty( $penyisihan_piutang_sum ) )
        {
            $penyisihan_piutang_sum = $penyisihan_piutang_sum[0]->nominal+0;
        }else $penyisihan_piutang_sum = 0; 

        $peralatan_n_mesin_sum =  json_decode( file_get_contents( site_url( "api/balance/accumulation?&order=account_prefix|desc&account=1.2.1&year=".$year ) ) );
        if( is_array( $peralatan_n_mesin_sum ) && !empty( $peralatan_n_mesin_sum ) )
        {
            $peralatan_n_mesin_sum = $peralatan_n_mesin_sum[0]->nominal+0;
        }else $peralatan_n_mesin_sum = 0; 

        $gedung_n_bangunan_sum =  json_decode( file_get_contents( site_url( "api/balance/accumulation?&order=account_prefix|desc&account=1.2.2&year=".$year ) ) );
        if( is_array( $gedung_n_bangunan_sum ) && !empty( $gedung_n_bangunan_sum ) )
        {
            $gedung_n_bangunan_sum = $gedung_n_bangunan_sum[0]->nominal+0;
        }else $gedung_n_bangunan_sum = 0; 

        $jalan_n_instalasi_sum =  json_decode( file_get_contents( site_url( "api/balance/accumulation?&order=account_prefix|desc&account=1.2.3&year=".$year ) ) );
        if( is_array( $jalan_n_instalasi_sum ) && !empty( $jalan_n_instalasi_sum ) )
        {
            $jalan_n_instalasi_sum = $jalan_n_instalasi_sum[0]->nominal+0;
        }else $jalan_n_instalasi_sum = 0; 
  

        $penysutan_aset_tetap_sum =  json_decode( file_get_contents( site_url( "api/balance/accumulation?&order=account_prefix|desc&account=1.2.5&year=".$year ) ) );
        if( is_array( $penysutan_aset_tetap_sum ) && !empty( $penysutan_aset_tetap_sum ) )
        {
            $penysutan_aset_tetap_sum = $penysutan_aset_tetap_sum[0]->nominal+0;
        }else $penysutan_aset_tetap_sum = 0; 

        $ekuitas_dana_lancar =  json_decode( file_get_contents( site_url( "api/balance/accumulation?&order=account_prefix|desc&account=3.1.1.1&year=".$year ) ) );
        if( is_array( $ekuitas_dana_lancar ) && !empty( $ekuitas_dana_lancar ) )
        {
            $ekuitas_dana_lancar = $ekuitas_dana_lancar[0]->nominal+0;
        }else $ekuitas_dana_lancar = 0; 

        $ekuitas_dana_investasi =  json_decode( file_get_contents( site_url( "api/balance/accumulation?&order=account_prefix|desc&account=3.1.1.2&year=".$year ) ) );
        if( is_array( $ekuitas_dana_investasi ) && !empty( $ekuitas_dana_investasi ) )
        {
            $ekuitas_dana_investasi = $ekuitas_dana_investasi[0]->nominal+0;
        }else $ekuitas_dana_investasi = 0; 
    

        ########################################################################################################################################################################################################
        $belanja_sum =  json_decode( file_get_contents( site_url( "api/journal/accumulation?&order=account_prefix|desc&account=5&year=".$year."&month=1-".( $semester * 6 ) ) ) );
        if( is_array( $belanja_sum ) && !empty( $belanja_sum ) )
        {
            $belanja_sum = $belanja_sum[0]->nominal+0;
        }else $belanja_sum = 0; 
       

        $pendapatan_sum =  json_decode( file_get_contents( site_url( "api/journal/accumulation?&order=account_prefix|desc&account=4.1.4&year=".$year."&month=1-".( $semester * 6 ) ) ) );
        if( is_array( $pendapatan_sum ) && !empty( $pendapatan_sum ) )
        {
            $pendapatan_sum = $pendapatan_sum[0]->nominal+0;
        }else $pendapatan_sum = 0; 
      

        $pokok_penerimaan_sum =  json_decode( file_get_contents( site_url( "api/journal/accumulation?&order=account_prefix|desc&account=7.1.6&year".$year."&month=1-".( $semester * 6 ) ) ) );
        if( is_array( $pokok_penerimaan_sum ) && !empty( $pokok_penerimaan_sum ) )
        {
            $pokok_penerimaan_sum = $pokok_penerimaan_sum[0]->nominal+0;
        }else $pokok_penerimaan_sum = 0; 
        ########################################################################################################################################################################################################

        $kas_dan_bank_sum = $kas_dan_bank_sum + $pendapatan_sum + $pokok_penerimaan_sum - $belanja_sum ;
        $piutang_usaha_sum = $piutang_usaha_sum - $pokok_penerimaan_sum ;

        $date = strtotime( $this->input->post('date') );

        $_data = (object) array(
            "kas_dan_bank" => $kas_dan_bank_sum,
            "piutang_usaha" => $piutang_usaha_sum,
            "penyisihan_piutang" => $penyisihan_piutang_sum * -1,
            "peralatan_n_mesin" => $peralatan_n_mesin_sum,
            "gedung_n_bangunan" => $gedung_n_bangunan_sum,
            "jalan_n_instalasi" => $jalan_n_instalasi_sum,
            "akumulasi_penysutan_aset_tetap" => $penysutan_aset_tetap_sum * -1,
            "ekuitas_dana_lancar" => $ekuitas_dana_lancar,
            "ekuitas_dana_investasi" => $ekuitas_dana_investasi,
        );

        $excel = new Neraca_excel;
		$excel->create( $_data, $date , $semester );
    }
    
}
