<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Lra_services{
    function __construct(  ){
    }

    public function __get($var)
  	{
  		return get_instance()->$var;
  	}

    public function get_monthly_table_config( $_page, $start_number = 1 )
    {
      $table["header"] = array(
        'account_code' => 'Kode Rekening',
        'account_description' => 'Uraian',
        'dpa_budget' => 'Jumlah Anggaran',
        'last_month' => 'Realisasi Sampai Dengan Bulan Lalu (Rp)',
        'this_month' => 'Realisasi Bulan Ini (Rp)',
        'until_this_month' => 'Realisasi Sampai Dengan Bulan Ini (Rp)',
        'difference' => 'Selisih /(Kurang) (Rp)',
      );
    
      return $table;
    }

    public function get_header( $item )
    {
      $_item =(object) array(
        'account_code' =>  $item->account,
        'account_description' => $item->header_name,
        'dpa_budget' => '',
        'last_month' => '',
        'this_month' => '',
        'until_this_month' => '',
        'difference' => '',
      );
    
      return [ $_item ];
    }
    public function set_bold( $item )
    {
      $item->dpa_budget = is_numeric( $item->dpa_budget ) ? number_format( $item->dpa_budget ) : '';
      $item->last_month = is_numeric( $item->last_month ) ? number_format( $item->last_month ) : '';
      $item->this_month = is_numeric( $item->this_month ) ? number_format( $item->this_month ) : '';
      $item->until_this_month = is_numeric( $item->until_this_month ) ? number_format( $item->until_this_month ) : '';
      $item->difference = is_numeric( $item->difference ) ? number_format( $item->difference ) : '';

      $_item =(object) array(
        'account_code' => '<b>'.$item->account_code.'</b>',
        'account_description' => '<b>'.$item->account_description.'</b>',
        'dpa_budget' => '<b>'. ( $item->dpa_budget ).'</b>',
        'last_month' => '<b>'.( $item->last_month ).'</b>',
        'this_month' => '<b>'.( $item->this_month ).'</b>',
        'until_this_month' => '<b>'.( $item->until_this_month ).'</b>',
        'difference' => '<b>'.( $item->difference ).'</b>',
      );
    
      return [ $_item ];
    }

    public function get_sum( $rows, $title = "Jumlah" )
    {
      $_item =(object) array(
        'account_code' => '',
        'account_description' => $title,
        'dpa_budget' => 0,
        'last_month' => 0,
        'this_month' => 0,
        'until_this_month' => 0,
        'difference' => 0,
      );
      foreach( $rows as $row )
      {   
        $_item->dpa_budget += $row->dpa_budget;
        $_item->last_month += $row->last_month;
        $_item->this_month += $row->this_month;
        $_item->until_this_month += $row->until_this_month;
        $_item->difference += $row->difference;
      }
    
      return [ $_item ];
    }

    public function get_semester_table_config( $_page, $start_number = 1 )
    {
      $table["header"] = array(
        'account_code' => 'Kode Rekening',
        'account_description' => 'Uraian',
        'dpa_budget' => 'Jumlah Anggaran',
        'last_month' => 'Realisasi Semester Lalu  (Rp)',
        'this_month' => 'Realisasi Semester Ini (Rp)',
        'until_this_month' => 'Realisasi Sampai Dengan Semester Ini (Rp)',
        'difference' => 'Selisih /(Kurang) (Rp)',
      );
    
      return $table;
    }

    public function view_table_config( $_page, $start_number = 1 )
    {
        $table["header"] = array(
          'year' => 'Tahun Anggaran',
        );
        $table["number"] = $start_number;
        $table[ "action" ] = array(
              array(
                "name" => "Lihat",
                "type" => "link",
                "url" => site_url( $_page."detail/"),
                "button_color" => "primary",
                "param" => "year",
              ),
      );

      return $table;
    }


    public function get_api_sum_perdate( $account, $year, $month, $title = "Jumlah", $date_start = 1546383600, $date_end = 546815600)
    {
      $budget_plan = json_decode( file_get_contents( site_url( "api/budget_plan/accumulation?&order=account_prefix|asc&account=".$account."&year=".$year) ) );
      $budget_plan = ( !empty( $budget_plan ) ) ? $budget_plan[0]->nominal : 0 ;
      $until_last_month = [];
      if( $month != 1 )
      {
          $until_last_month =  json_decode( file_get_contents( site_url( "api/journal/accumulationPerdate?&order=account_prefix|asc&account=".$account."&year=".$year."&month=1-".( $month - 1 )."&date_start=".$date_start."&date_end=".$date_end ) ) );
      }
      $until_last_month = ( !empty( $until_last_month ) ) ? $until_last_month[0]->nominal : 0 ;
      $this_month =  json_decode( file_get_contents( site_url( "api/journal/accumulationPerdate?&order=account_prefix|asc&account=".$account."&year=".$year."&month=".$month."&date_start=".$date_start."&date_end=".$date_end ) ) );
      $this_month = ( !empty( $this_month ) ) ? $this_month[0]->nominal : 0 ;

      $_item =(object) array(
        'account_code' => '',
        'account_description' => $title,
        'dpa_budget' => ( $budget_plan ),
        'last_month' => ( $until_last_month ) ,
        'this_month' => ( $this_month ),
        'until_this_month' => 0,
        'difference' => 0,
      );

      $_item->until_this_month = $_item->last_month+$_item->this_month;
      $_item->difference = abs(  $_item->dpa_budget - $_item->until_this_month );
    
      return [ $_item ];
    }


    public function get_api_sum( $account, $year, $month, $title = "Jumlah", $date_start = 1546383600, $date_end = 546815600)
    {
      $budget_plan = json_decode( file_get_contents( site_url( "api/budget_plan/accumulation?&order=account_prefix|asc&account=".$account."&year=".$year ) ) );
      $budget_plan = ( !empty( $budget_plan ) ) ? $budget_plan[0]->nominal : 0 ;
      $until_last_month = [];
      if( $month != 1 )
      {
          $until_last_month =  json_decode( file_get_contents( site_url( "api/journal/accumulation?&order=account_prefix|asc&account=".$account."&year=".$year."&month=1-".( $month - 1 ) ) ) );
      }
      $until_last_month = ( !empty( $until_last_month ) ) ? $until_last_month[0]->nominal : 0 ;
      $this_month =  json_decode( file_get_contents( site_url( "api/journal/accumulation?&order=account_prefix|asc&account=".$account."&year=".$year."&month=".$month) ) );
      $this_month = ( !empty( $this_month ) ) ? $this_month[0]->nominal : 0 ;

      $_item =(object) array(
        'account_code' => '',
        'account_description' => $title,
        'dpa_budget' => ( $budget_plan ),
        'last_month' => ( $until_last_month ) ,
        'this_month' => ( $this_month ),
        'until_this_month' => 0,
        'difference' => 0,
      );

      $_item->until_this_month = $_item->last_month+$_item->this_month;
      $_item->difference = abs(  $_item->dpa_budget - $_item->until_this_month );
    
      return [ $_item ];
    }

    public function get_semester_api_sum( $account, $year, $semester, $title = "Jumlah" )
    {
      $budget_plan = json_decode( file_get_contents( site_url( "api/budget_plan/accumulation?&order=account_prefix|asc&account=".$account."&year=".$year ) ) );
      $budget_plan = ( !empty( $budget_plan ) ) ? $budget_plan[0]->nominal : 0 ;
      $until_last_month = [];
      if( $semester != 1 )
      {
          $until_last_month =  json_decode( file_get_contents( site_url( "api/journal/accumulation?&order=account_prefix|asc&account=".$account."&year=".$year."&month=1-".( ( $semester -1 ) *6 ) ) ) );
      }
      $until_last_month = ( !empty( $until_last_month ) ) ? $until_last_month[0]->nominal : 0 ;
      $this_month =  json_decode( file_get_contents( site_url( "api/journal/accumulation?&order=account_prefix|asc&account=".$account."&year=".$year."&month=".( ( $semester - 1 ) * 6 + 1 )."-".( $semester * 6 ) ) ) );
      $this_month = ( !empty( $this_month ) ) ? $this_month[0]->nominal : 0 ;

      $_item =(object) array(
        'account_code' => '',
        'account_description' => $title,
        'dpa_budget' => ( $budget_plan ),
        'last_month' => ( $until_last_month ) ,
        'this_month' => ( $this_month ),
        'until_this_month' => 0,
        'difference' => 0,
      );

      $_item->until_this_month = $_item->last_month+$_item->this_month;
      $_item->difference = abs(  $_item->dpa_budget - $_item->until_this_month );
    
      return [ $_item ];
    }

    public function subtract(  $row_1,  $row_2, $title = "Surplus/Defisit" )
    {
      $_item =(object) array(
        'account_code' => '',
        'account_description' => $title,
        'dpa_budget' => 0,
        'last_month' => 0 ,
        'this_month' => 0,
        'until_this_month' => 0,
        'difference' => 0,
      );
      
      if(  count( $row_1 ) == 1 && count( $row_2 ) == 1 )
      {
          $_item->last_month             = $row_1[0]->last_month - $row_2[0]->last_month;
          $_item->this_month             = $row_1[0]->this_month - $row_2[0]->this_month;
          $_item->until_this_month       = $row_1[0]->until_this_month - $row_2[0]->until_this_month;
      }
      if(  count( $row_1 ) == 0 )
      {
        $_item->last_month             =  $row_2[0]->last_month;
        $_item->this_month             =  $row_2[0]->this_month;
        $_item->until_this_month       =  $row_2[0]->until_this_month;
      }
      else if(  count( $row_2 ) == 0 )
      {
        $_item->last_month             =  $row_1[0]->last_month;
        $_item->this_month             =  $row_1[0]->this_month;
        $_item->until_this_month       =  $row_1[0]->until_this_month;
      }
      return [ $_item ]; 
    }

    public function vertical_merge( $data, $rows )
    {
      $_item =(object) array(
        'account_code' => $data->account,
        'account_description' => $data->header_name,
        'dpa_budget' => 0,
        'last_month' => 0,
        'this_month' => 0,
        'until_this_month' => 0,
        'difference' => 0,
      );
      foreach( $rows as $row )
      {   
        $_item->dpa_budget += $row->dpa_budget;
        $_item->last_month += $row->last_month;
        $_item->this_month += $row->this_month;
        $_item->until_this_month += $row->until_this_month;
        $_item->difference += $row->difference;
      }
    
      return [ $_item ];
    }
}
?>
