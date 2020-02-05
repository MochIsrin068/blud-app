<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/libraries/LPSAL_excel.php";
class Lra extends Finance_Controller {
    private $services = null;
    private $name = null;
    private $parent_page = 'finance';
    private $current_page = 'finance/report/lra/';
    private $form_data = null;

    private $MONTH = null;
    private $YEAR = null;
    private $is_semester = null;

    private $LIST = array();
    private $LIST_NUMBER = array();
    private $budget_plan_sums = array();
    private $until_last_month_sums = array();
    private $this_month_sums = array();

   
    public $base_data = array();
    public $semester_data = array();
    public $SEMESTER = array();
    
	public function __construct(){
        parent::__construct();
        // $this->output->enable_profiler(TRUE);
        $this->is_semester = FALSE;
        $this->load->library('services/Lra_services');
        $this->services = new Lra_services;
        $this->load->model(array( 
			'm_account' ,
			'm_start_balance' ,
			'm_generalcash' ,
        ));
        $this->base_data = array(
            (object) array(
                'header_name' => 'Pendapatan',
                'account' => '4',
                'group_by' => 'account_id',
                'child' => [
                   
                ],
            ),
            (object) array(
                'header_name' => 'Belanja',
                'account' => '5',
                'group_by' => 'account_id',
                'child' => [
                    (object) array(
                        'header_name' => 'Belanja Pegawai',
                        'account' => '5.2.1',
                        'group_by' => 'account_id',
                        'child' => [
                   
                        ],
                    ),
                    (object) array(
                        'header_name' => 'Belanja Barang Jasa',
                        'account' => '5.2.2',
                        'group_by' => 'account_id',
                        'child' => [
                   
                        ],
                    ),
                ],
            ),
            (object) array(
                'header_name' => 'Pembiayaan',
                'account' => '7',
                'group_by' => 'account_id',
                'child' => [
                    (object) array(
                        'header_name' => 'Penerimaan Pembiayaan',
                        'account' => '7.1',
                        'group_by' => 'account_id',
                        'child' => [
                   
                        ],
                    ),
                    (object) array(
                        'header_name' => 'Pengeluaran Pembiayaan',
                        'account' => '7.2',
                        'group_by' => 'account_id',
                        'child' => [
                   
                        ],
                    ),
                ],
            ),
        );
        ##########################################
        $this->semester_data = array(
            (object) array(
                'header_name' => 'Pendapatan',
                'account' => '4',
                'group_by' => 'account_id',
                'child' => [
                    (object) array(
                        'header_name' => 'Lain lain PAD yang Sah',
                        'account' => '4.1.4',
                        'group_by' => '',
                        'child' => [
                   
                        ],
                    ),
                ],
            ),
            (object) array(
                'header_name' => 'Belanja',
                'account' => '5',
                'group_by' => 'account_id',
                'child' => [
                    (object) array(
                        'header_name' => 'Belanja Pegawai',
                        'account' => '5.2.1',
                        'group_by' => '',
                        'child' => [
                   
                        ],
                    ),
                    (object) array(
                        'header_name' => 'Belanja Barang Jasa',
                        'account' => '5.2.2',
                        'group_by' => '',
                        'child' => [
                   
                        ],
                    ),
                ],
            ),
            (object) array(
                'header_name' => 'Pembiayaan',
                'account' => '7',
                'group_by' => 'account_id',
                'child' => [
                    (object) array(
                        'header_name' => 'Penerimaan Pembiayaan',
                        'account' => '7.1',
                        'group_by' => 'account_id',
                        'child' => [
                   
                        ],
                    ),
                    (object) array(
                        'header_name' => 'Pengeluaran Pembiayaan',
                        'account' => '7.2',
                        'group_by' => 'account_id',
                        'child' => [
                   
                        ],
                    ),
                ],
            ),
        );
    }

    public function index()
    {
        $this->load->library('services/Report_6_services');
        $this->services = new Report_6_services;
        
        $page = ($this->uri->segment(4+1)) ? ($this->uri->segment(4+1) -  1 ) : 0;
        //pagination parameter
        $pagination['base_url'] = base_url($this->current_page) .'index';
        $pagination['total_records'] = $this->m_start_balance->record_count() ;
        $pagination['limit_per_page'] = 10;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4+1;
        //set pagination
		if ($pagination['total_records']>0) $this->data['links'] = $this->setPagination($pagination);

		$table = $this->services->get_table_config( $this->current_page, $pagination['start_record'] + 1 );
        $rows = $this->m_start_balance->get_years( $pagination['start_record'], $pagination['limit_per_page'] )->result();
        $table[ "rows" ] = $rows;
        $this->data[ "table" ] = $this->load->view('templates/tables/generalcash_table', $table, true);

        $alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "LAPORAN REALISASI ANGGARAN";
		$this->data["header"] = "LAPORAN REALISASI ANGGARAN";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

        $this->data[ "modal_form_add" ] = ''; 
        
        $this->render( "finance/report/lpsal/content");
    }
    #############################################################################################################################
    #############################################################################################################################
    #############################################################################################################################
    protected function get_data( array $datas )
    {
        if( empty( $datas ) )
        {
            return;
        }
        foreach( $datas as $data )
        {   
            $_list = array();
            if( empty( $data->child ) )
            {
                $this->budget_plan_sums =  json_decode( file_get_contents( site_url( "api/budget_plan/accumulation?&order=account_prefix|asc&group_by=".$data->group_by."&account=".$data->account."&year=".$this->YEAR ) ) );
                if( $this->is_semester )
                {
                    if( $this->SEMESTER == 1 )
                    {
                        $this->until_last_month_sums =  [];
                    } 
                    else
                    {
                        $this->until_last_month_sums =  json_decode( file_get_contents( site_url( "api/journal/accumulation?&order=account_prefix|asc&group_by=".$data->group_by."&account=".$data->account."&year=".$this->YEAR."&month=1-".( ( $this->SEMESTER-1 ) *6 )     ) ) );
                    }
                    $this->this_month_sums =  json_decode( file_get_contents( site_url( "api/journal/accumulation?&order=account_prefix|asc&group_by=".$data->group_by."&account=".$data->account."&year=".$this->YEAR."&month=".( ( $this->SEMESTER-1 ) *6 +1 )."-".( $this->SEMESTER*6 ) ) ) );
                }
                else
                {
                    if( $this->MONTH == 1 )
                    {
                        $this->until_last_month_sums =  [];
                    } 
                    else
                    {
                        $this->until_last_month_sums =  json_decode( file_get_contents( site_url( "api/journal/accumulation?&order=account_prefix|asc&group_by=".$data->group_by."&account=".$data->account."&year=".$this->YEAR."&month=1-".( $this->MONTH-1 ) ) ) );
                    }
                    $this->this_month_sums =  json_decode( file_get_contents( site_url( "api/journal/accumulation?&order=account_prefix|asc&group_by=".$data->group_by."&account=".$data->account."&year=".$this->YEAR."&month=".$this->MONTH ) ) );
                }
                

                $_list = $this->sync(  );
            }
            if( $data->group_by == '' )
            {
                $this->LIST  = array_merge($this->LIST, ( $this->services->vertical_merge( $data,  $_list ) ) );
                // $this->LIST  = array_merge($this->LIST, $_list);

                $this->LIST_NUMBER  = array_merge($this->LIST_NUMBER, $this->services->vertical_merge( $data,  $_list ) );
                // $this->LIST_NUMBER  = array_merge($this->LIST_NUMBER, $_list);
            }
            else
            {
                $this->LIST  = array_merge($this->LIST, $this->services->set_bold( $this->services->get_header( $data )[0] ) );
                $this->LIST  = array_merge($this->LIST, $_list);

                $this->LIST_NUMBER  = array_merge($this->LIST_NUMBER, $this->services->get_header( $data ) );
                $this->LIST_NUMBER  = array_merge($this->LIST_NUMBER, $_list);
            }
            $this->get_data( $data->child );
        }
    }

    protected function sync(  )
    {
        $_list = array();
        foreach( $this->budget_plan_sums as $budget_plan )
        {   
            $_list[]=$this->merge( $budget_plan );
        }
        return ( $_list ) ;
        // return array_merge( $_list, $this->services->get_sum( $_list ) ) ;
        
    }
    protected function merge( $budget_plan )
    {
        $_item = (object) array(
            'account_code' => $budget_plan->account_code,
            'account_description' => $budget_plan->account_description,
            'dpa_budget' => $budget_plan->nominal,
            'last_month' => 0,
            'this_month' => 0,
            'until_this_month' => 0,
            'difference' => 0,
        );
        foreach( $this->until_last_month_sums as  $ind => $until_last_month )
        {   
            if( $budget_plan->account_id == $until_last_month->account_id )
            {
                $_item->last_month = $until_last_month->nominal;
                unset( $this->until_last_month_sums[ $ind ] );
                break;
            }
        }
        foreach( $this->this_month_sums as  $ind => $this_month )
        {   
            if( $budget_plan->account_id == $this_month->account_id )
            {
                $_item->this_month = $this_month->nominal;
                unset( $this->this_month[ $ind ] );
                break;
            }
        }
        $_item->until_this_month = $_item->last_month+$_item->this_month;
        $_item->difference = abs(  $_item->dpa_budget - $_item->until_this_month );

        return $_item;
    }
    #############################################################################################################################
    #############################################################################################################################

    // public function date(){
    //     $date_start =  $this->input->post('date_start');
    //     $date_end = $this->input->post('date_end');

    //     echo strtotime($date_start)."<br/>";
    //     echo strtotime($date_end)."<br/>";


    // }

    public function date( $year = 2019, $month = NULL )
    {
        // date POST
        $date_start =  $this->input->post('date_start');
        $date_end = $this->input->post('date_end');

        $dateStart = strtotime($date_start);
        $dateEnd = strtotime($date_end);
        /////////////////////////////////

        if( !isset( $month ) ) 
		{
			$month = (int) date('m') ;
		}	
        $this->LIST = array();
        $this->MONTH = $month;
        $this->YEAR = $year;

        $this->get_data( [ $this->base_data[0] ]   );//pendapatan
        ########################################################################################################################################################################################################
        $pendapatan_sum = $this->services->get_api_sum_perdate( $this->base_data[0]->account,  $this->YEAR, $this->MONTH, "Jumlah" ,$dateStart, $dateEnd);
        $belanja_sum = $this->services->get_api_sum_perdate( $this->base_data[1]->account,  $this->YEAR, $this->MONTH, "(Belanja Pegawai + Belanja Barang Jasa )", $dateStart, $dateEnd);
        $surplus_defisit = $this->services->subtract( $pendapatan_sum, $belanja_sum );

        $penerimaan = $this->services->get_api_sum_perdate( $this->base_data[2]->child[0]->account ,  $this->YEAR, $this->MONTH, "Jumlah",$dateStart, $dateEnd );
        $pengeluaran = $this->services->get_api_sum_perdate( $this->base_data[2]->child[1]->account ,  $this->YEAR, $this->MONTH, "Jumlah" ,$dateStart, $dateEnd);
        $pembiayaan_netto = $this->services->subtract( $penerimaan, $pengeluaran, "Pembiayaan Netto" );
        ########################################################################################################################################################################################################

        $this->LIST  = array_merge($this->LIST, $this->services->set_bold( $pendapatan_sum[0] ) ) ;
        $this->LIST_NUMBER  = array_merge($this->LIST_NUMBER, ( $pendapatan_sum ) ) ;

        $this->get_data( [ $this->base_data[1] ]   );
        
        $this->LIST  = array_merge($this->LIST, $this->services->set_bold( $belanja_sum[0] ) ) ;
        $this->LIST_NUMBER  = array_merge($this->LIST_NUMBER, ( $belanja_sum ) ) ;
        
        $this->LIST  = array_merge($this->LIST, $this->services->set_bold( $surplus_defisit[0] ) ) ;
        $this->LIST_NUMBER  = array_merge($this->LIST_NUMBER, ( $surplus_defisit ) ) ;

        $this->get_data( [ $this->base_data[2] ]   );
        $this->LIST  = array_merge($this->LIST, $this->services->set_bold( $pembiayaan_netto[0] ) ) ;
        $this->LIST_NUMBER  = array_merge($this->LIST_NUMBER, ( $pembiayaan_netto ) ) ;

        $this->load->library('services/Lra_services');
        $this->services = new Lra_services;
        $table = $this->services->get_monthly_table_config( $this->current_page );
        $table[ "rows" ] = $this->LIST;
        $this->data[ "table" ] = $this->load->view('templates/tables/rba_table', $table, true);        

		########################################################################################################################################################################################################
		########################################################################################################################################################################################################
        $alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "LAPORAN REALISASI ANGGARAN TAHUN ".$year." Sampai Bulan ".$this->_month[ $month ];
		$this->data["header"] = "LAPORAN REALISASI ANGGARAN TAHUN ".$year." Sampai Bulan ".$this->_month[ $month ];
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
        ########################################################################################################################################################################################################
		$print_link = array(
			"name" => "Print LRA",
			"modal_id" => "print_link_",
			"button_color" => "primary",
			"url" => site_url( $this->current_page. "month/".$year."/".$month."?print=true"),
			"form_data" => array(
				"date" => array(
					'type' => 'date',
					'label' => "Tanggal",
					'value' => date("m/d/Y"),
				),
			),	
			"param"=>"",
		);
		$print_link["data"] = NULL;
		$print_link = $this->load->view('templates/actions/modal_form_blank', $print_link, true );  
		$this->data[ "modal_footer" ] = $print_link; 
		########################################################################################################################################################################################################
        $this->data[ "modal_form_add" ] = ''; 
        ########################################################################################################################################################################################################
		if( $this->input->get('print', FALSE) )
		{
			if( !($_POST) ) redirect( site_url( $this->current_page. "year/".$year."/".$month."" ) );
			$_data = (object) array(
				"date" => strtotime( $this->input->post("date") ),
			);
			$rows =  $this->LIST_NUMBER;

			$this->generate_pdf( $rows, $_data );
			return;
		}
		########################################################################################################################################################################################################
        
        $this->render( "finance/report/report_6/content");
    }


    public function month( $year = 2019, $month = NULL )
    {
        if( !isset( $month ) ) 
		{
			$month = (int) date('m') ;
		}	
        $this->LIST = array();
        $this->MONTH = $month;
        $this->YEAR = $year;

        $this->get_data( [ $this->base_data[0] ]   );//pendapatan
        ########################################################################################################################################################################################################
        $pendapatan_sum = $this->services->get_api_sum( $this->base_data[0]->account,  $this->YEAR, $this->MONTH );
        $belanja_sum = $this->services->get_api_sum( $this->base_data[1]->account,  $this->YEAR, $this->MONTH, "(Belanja Pegawai + Belanja Barang Jasa )" );
        $surplus_defisit = $this->services->subtract( $pendapatan_sum, $belanja_sum );

        $penerimaan = $this->services->get_api_sum( $this->base_data[2]->child[0]->account ,  $this->YEAR, $this->MONTH );
        $pengeluaran = $this->services->get_api_sum( $this->base_data[2]->child[1]->account ,  $this->YEAR, $this->MONTH);
        $pembiayaan_netto = $this->services->subtract( $penerimaan, $pengeluaran, "Pembiayaan Netto" );
        ########################################################################################################################################################################################################

        $this->LIST  = array_merge($this->LIST, $this->services->set_bold( $pendapatan_sum[0] ) ) ;
        $this->LIST_NUMBER  = array_merge($this->LIST_NUMBER, ( $pendapatan_sum ) ) ;

        $this->get_data( [ $this->base_data[1] ]   );
        
        $this->LIST  = array_merge($this->LIST, $this->services->set_bold( $belanja_sum[0] ) ) ;
        $this->LIST_NUMBER  = array_merge($this->LIST_NUMBER, ( $belanja_sum ) ) ;
        
        $this->LIST  = array_merge($this->LIST, $this->services->set_bold( $surplus_defisit[0] ) ) ;
        $this->LIST_NUMBER  = array_merge($this->LIST_NUMBER, ( $surplus_defisit ) ) ;

        $this->get_data( [ $this->base_data[2] ]   );
        $this->LIST  = array_merge($this->LIST, $this->services->set_bold( $pembiayaan_netto[0] ) ) ;
        $this->LIST_NUMBER  = array_merge($this->LIST_NUMBER, ( $pembiayaan_netto ) ) ;

        $this->load->library('services/Lra_services');
        $this->services = new Lra_services;
        $table = $this->services->get_monthly_table_config( $this->current_page );
        $table[ "rows" ] = $this->LIST;
        $this->data[ "table" ] = $this->load->view('templates/tables/rba_table', $table, true);        

		########################################################################################################################################################################################################
		########################################################################################################################################################################################################
        $alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "LAPORAN REALISASI ANGGARAN TAHUN ".$year." Sampai Bulan ".$this->_month[ $month ];
		$this->data["header"] = "LAPORAN REALISASI ANGGARAN TAHUN ".$year." Sampai Bulan ".$this->_month[ $month ];
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
        ########################################################################################################################################################################################################
		$print_link = array(
			"name" => "Print LRA",
			"modal_id" => "print_link_",
			"button_color" => "primary",
			"url" => site_url( $this->current_page. "month/".$year."/".$month."?print=true"),
			"form_data" => array(
				"date" => array(
					'type' => 'date',
					'label' => "Tanggal",
					'value' => date("m/d/Y"),
				),
			),	
			"param"=>"",
		);
		$print_link["data"] = NULL;
		$print_link = $this->load->view('templates/actions/modal_form_blank', $print_link, true );  
		$this->data[ "modal_footer" ] = $print_link; 
		########################################################################################################################################################################################################
        $this->data[ "modal_form_add" ] = ''; 
        ########################################################################################################################################################################################################
		if( $this->input->get('print', FALSE) )
		{
			if( !($_POST) ) redirect( site_url( $this->current_page. "year/".$year."/".$month."" ) );
			$_data = (object) array(
				"date" => strtotime( $this->input->post("date") ),
			);
			$rows =  $this->LIST_NUMBER;

			$this->generate_pdf( $rows, $_data );
			return;
		}
		########################################################################################################################################################################################################
        
        $this->render( "finance/report/report_6/content");
    }

    public function semester( $year = 2019, $semester = 1 )
    {
        $this->is_semester = TRUE;
        $this->SEMESTER = $semester;
        $month = $semester;

        $this->LIST = array();
        $this->MONTH = $month;
        $this->YEAR = $year;
        ########################################################################################################################################################################################################
        $pendapatan_sum = $this->services->get_semester_api_sum( $this->base_data[0]->account,  $this->YEAR, $this->SEMESTER );
        $belanja_sum = $this->services->get_semester_api_sum( $this->base_data[1]->account,  $this->YEAR, $this->SEMESTER, "Jumlah" );
        $surplus_defisit = $this->services->subtract( $pendapatan_sum, $belanja_sum );

        $penerimaan = $this->services->get_semester_api_sum( $this->base_data[2]->child[0]->account ,  $this->YEAR, $this->SEMESTER );
        $pengeluaran = $this->services->get_semester_api_sum( $this->base_data[2]->child[1]->account ,  $this->YEAR, $this->SEMESTER);
        $pembiayaan_netto = $this->services->subtract( $penerimaan, $pengeluaran, "Pembiayaan Netto" );
        ########################################################################################################################################################################################################
        $this->get_data( [ $this->semester_data[0] ]   );
        $this->LIST  = array_merge($this->LIST, $this->services->set_bold( $pendapatan_sum[0] ) ) ;
        $this->LIST_NUMBER  = array_merge($this->LIST_NUMBER, ( $pendapatan_sum ) ) ;

        $this->get_data( [ $this->semester_data[1] ]   );
        $this->LIST  = array_merge($this->LIST, $this->services->set_bold( $belanja_sum[0] ) ) ;
        $this->LIST_NUMBER  = array_merge($this->LIST_NUMBER, ( $belanja_sum ) ) ;

        $this->LIST  = array_merge($this->LIST, $this->services->set_bold( $surplus_defisit[0] ) ) ;
        $this->LIST_NUMBER  = array_merge($this->LIST_NUMBER, ( $surplus_defisit ) ) ;


        $this->get_data( [ $this->semester_data[2] ]   );
        $this->LIST  = array_merge($this->LIST, $this->services->set_bold( $pembiayaan_netto[0] ) ) ;
        $this->LIST_NUMBER  = array_merge($this->LIST_NUMBER, ( $pembiayaan_netto ) ) ;

        $this->load->library('services/Lra_services');
        $this->services = new Lra_services;

		$table = $this->services->get_semester_table_config( $this->current_page );
        $table[ "rows" ] = $this->LIST;
        $this->data[ "table" ] = $this->load->view('templates/tables/monthly_LRA_table', $table, true);
        ########################################################################################################################################################################################################
		########################################################################################################################################################################################################
		########################################################################################################################################################################################################
		########################################################################################################################################################################################################
        $alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "LAPORAN REALISASI ANGGARAN TAHUN ".$year." Semester Ke ".$this->SEMESTER;
		$this->data["header"] = "LAPORAN REALISASI ANGGARAN TAHUN ".$year." Semester Ke ".$this->SEMESTER;
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
        ########################################################################################################################################################################################################
        $print_link = array(
            "name" => "Print LRA",
            "modal_id" => "print_link_",
            "button_color" => "primary",
            "url" => site_url( $this->current_page. "semester/".$year."/".$month."?print=true"),
            "form_data" => array(
                "date" => array(
                    'type' => 'date',
                    'label' => "Tanggal",
                    'value' => date("m/d/Y"),
                ),
            ),	
            "param"=>"",
        );
        $print_link["data"] = NULL;
        $print_link = $this->load->view('templates/actions/modal_form_blank', $print_link, true );  
        $this->data[ "modal_footer" ] = $print_link; 
        ########################################################################################################################################################################################################
        $this->data[ "modal_form_add" ] = ''; 
        ########################################################################################################################################################################################################
        if( $this->input->get('print', FALSE) )
        {
            if( !($_POST) ) redirect( site_url( $this->current_page. "year/".$year."/".$month."" ) );
            $_data = (object) array(
                "date" => strtotime( $this->input->post("date") ),
            );
            $rows =  $this->LIST_NUMBER;

            $this->generate_pdf( $rows, $_data );
            return;
        }
        ########################################################################################################################################################################################################

        $this->render( "finance/report/report_6/content");
    }

    protected function generate_pdf( $rows, $_data )
	{
        $this->load->library('services/Lra_services');
        $this->services = new Lra_services;
        $this->data = $this->services->get_monthly_table_config( $this->current_page );
		$this->data['rows'] = $rows;
		$this->data['date'] = ( $_data->date ) ;
		$this->data['day'] = date('d', $_data->date ) ;
		$this->data['month'] =  $this->_month[ (int) date('m', $_data->date )  ]  ;
		$this->data['year'] =  date('Y', $_data->date ) ;
		
		$this->load->library('pdf');
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);

		$pdf->SetTitle( "LRA ".date('d-m-Y', $this->data['date']) );
		
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		 
		$pdf->SetTopMargin(10);
		$pdf->SetLeftMargin(10);
		$pdf->SetRightMargin(10);
		$pdf->SetAutoPageBreak(true);
		$pdf->SetAuthor('BLUD');
		$pdf->SetDisplayMode('real', 'default');
		$pdf->AddPage( 'L' );
		$pdf->SetFont('times', NULL, 9);

        
        if( $this->is_semester )
        {
            $this->data['semester'] = $this->SEMESTER;
            $html = $this->load->view('templates/report/semester_lra_report', $this->data, true);	
        }
        else
        {
            $html = $this->load->view('templates/report/lra_report', $this->data, true);	
        }

        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->Image( site_url( WATERMARK ) , 10, 180, 30, 5 );

		$pdf->Output("LRA ".date('d-m-Y', $this->data['date']).".pdf",'I');
	}
    
}
