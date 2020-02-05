<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Finance_Controller {
	private $services = null;
    private $name = null;
    private $parent_page = 'finance';
    private $current_page = 'finance/';
	private $form_data = null;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('services/Neraca_saldo_services');
		$this->services = new Neraca_saldo_services;

	}

	public function index( $year = 2019, $month = NULL )
	{
		if( !isset( $month ) ) 
		{
			$month = (int) date('m');
		}		

		if( $month != 1 ) $month = $month - 1 ;

		
		// dari saldo awal
		$kas_dan_bank_sum =  json_decode( file_get_contents( htmlspecialchars_decode(site_url( "api/balance/accumulation?&order=account_prefix|desc&account=1.1.1&year=".$year ) )) );
		$piutang_usaha_sum =  json_decode( file_get_contents( site_url( "api/balance/accumulation?&order=account_prefix|desc&account=1.1.2&year=".$year ) ) );
		$penyisihan_piutang_sum =  json_decode( file_get_contents( site_url( "api/balance/accumulation?&order=account_prefix|desc&account=1.1.3&year=".$year ) ) );
		// echo var_dump( $kas_dan_bank_sum );return;
		$peralatan_n_mesin_sum =  json_decode( file_get_contents( site_url( "api/balance/accumulation?&order=account_prefix|desc&account=1.2.1&year=".$year ) ) );
		$gedung_n_bangunan_sum =  json_decode( file_get_contents( site_url( "api/balance/accumulation?&order=account_prefix|desc&account=1.2.2&year=".$year ) ) );
		$jalan_n_instalasi_sum =  json_decode( file_get_contents( site_url( "api/balance/accumulation?&order=account_prefix|desc&account=1.2.3&year=".$year ) ) );
		$penysutan_aset_tetap_sum =  json_decode( file_get_contents( site_url( "api/balance/accumulation?&order=account_prefix|desc&account=1.2.5&year=".$year ) ) );
		$ekuitas_sum =  json_decode( file_get_contents( site_url( "api/balance/accumulation?&order=account_prefix|desc&account=3&year=".$year ) ) );
		
		########################################################################################################################################################################################################
		// dari bulanan
		$belanja =  json_decode( file_get_contents( site_url( "api/journal/accumulation?&group_by=account_id&order=account_prefix|desc&account=5&year=".$year."&month=1-".$month ) ) );
		// echo $belanja;
		$belanja_sum =  json_decode( file_get_contents( site_url( "api/journal/accumulation?&order=account_prefix|desc&account=5&year=".$year."&month=1-".$month ) ) );
		// echo $belanja_sum;

		$pendapatan_sum =  json_decode( file_get_contents( site_url( "api/journal/accumulation?&order=account_prefix|desc&account=4.1.4&year=".$year."&month=1-".$month ) ) );
		// echo $pendapatan_sum;
		$pokok_penerimaan_sum =  json_decode( file_get_contents( site_url( "api/journal/accumulation?&order=account_prefix|desc&account=7.1.6&year".$year."&month=1-".$month ) ) );
		// echo $pokok_penerimaan_sum;
		########################################################################################################################################################################################################
		// echo var_dump( $kas_dan_bank_sum );
		$kas_dan_bank_sum = $this->services->add( $kas_dan_bank_sum, $this->services->add( $pendapatan_sum, $pokok_penerimaan_sum )  );
		$kas_dan_bank_sum = $this->services->subtract( $kas_dan_bank_sum, $belanja_sum );
		$kas_dan_bank_sum[0]->account_description = "kas dan Bank";
		########################################################################################################################################################################################################
		$piutang_usaha_sum = $this->services->subtract( $piutang_usaha_sum, $pokok_penerimaan_sum ); 
		########################################################################################################################################################################################################
		
		$debit = array_merge( $kas_dan_bank_sum, $piutang_usaha_sum, $peralatan_n_mesin_sum, $gedung_n_bangunan_sum, $jalan_n_instalasi_sum  );
		$debit = $this->services->convert2debit( $debit );

		$credit = array_merge( $penyisihan_piutang_sum,$penysutan_aset_tetap_sum, $ekuitas_sum, $pendapatan_sum   ); 
		$credit = $this->services->convert2krebit( $credit );

		// echo var_dump( $piutang_usaha_sum  );	
		$table = $this->services->get_table_config( '', '' ) ;
		$table[ "rows" ] = array_merge(
			$debit,
			$credit,
			$this->services->convert2debit( $belanja )
		);
		$table[ "rows" ] = array_merge(
			$table[ "rows" ] ,
			[ $this->services->sum( $table[ "rows" ], 'Jumlah' ) ]
		);
		$this->data[ "table" ] =  $this->load->view('templates/tables/monthly_table', $table, true);
		########################################################################################################################################################################################################
		########################################################################################################################################################################################################
		########################################################################################################################################################################################################
		########################################################################################################################################################################################################
		$this->data[ "page_title" ] = "Beranda";
		$this->data["header"] = "Neraca Saldo Sampai Bulan ".$this->_month[ $month ];
		$this->data["sub_header"] = 'Neraca Saldo ';

		$this->render( "finance/dashboard/_content" );
	}
}
