<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Neraca_saldo_services{
    function __construct(  ){
        $this->load->model(array( 
          'm_account' ,
          'm_budget_plan' ,
          'm_generalcash' ,
          'm_budget_realization' ,
        ));
    }

    public function __get($var)
  	{
  		return get_instance()->$var;
  	}

    public function get_table_config( $_page, $start_number = 1 )
    {
        $table["header"] = array(
          // 'account_code'  => 'No rekening',
          'account_description'   => 'Nama Akun',
          'debit'         => 'Debet',
          'credit'        => 'Kredit',
        );
        $table["number"] = $start_number;

      return $table;
    }
    public function add(  $row_1,  $row_2 )
    {
      $_item = (object) array(
        'account_code'  => '',
        'account_description'   => '',
        'nominal'       => 0,
      );
      
      if(  count( $row_1 ) == 1 && count( $row_2 ) == 1 )
      {
          $_item->account_code        = $row_1[0]->account_code;
          $_item->account_description = $row_1[0]->account_description;
          $_item->nominal             = $row_1[0]->nominal + $row_2[0]->nominal;
      }
      if(  count( $row_1 ) == 0 )
      {
          $_item->account_code        = $row_2[0]->account_code;
          $_item->account_description = $row_2[0]->account_description;
          $_item->nominal             = $row_2[0]->nominal;
      }
      else if(  count( $row_2 ) == 0 )
      {
          $_item->account_code        = $row_1[0]->account_code;
          $_item->account_description = $row_1[0]->account_description;
          $_item->nominal             = $row_1[0]->nominal;
      }
      return [ $_item ]; 
    }

    public function subtract(  $row_1,  $row_2 )
    {
      $_item = (object) array(
        'account_code'  => '',
        'account_description'   => '',
        'nominal'       => 0,
      );
      
      if(  count( $row_1 ) == 1 && count( $row_2 ) == 1 )
      {
          $_item->account_code        = $row_1[0]->account_code;
          $_item->account_description = $row_1[0]->account_description;
          $_item->nominal             = $row_1[0]->nominal - $row_2[0]->nominal;
      }
      if(  count( $row_1 ) == 0 )
      {
          $_item->account_code        = $row_2[0]->account_code;
          $_item->account_description = $row_2[0]->account_description;
          $_item->nominal             = $row_2[0]->nominal;
      }
      else if(  count( $row_2 ) == 0 )
      {
          $_item->account_code        = $row_1[0]->account_code;
          $_item->account_description = $row_1[0]->account_description;
          $_item->nominal             = $row_1[0]->nominal;
      }
      return [ $_item ]; 
    }

    public function convert2debit( $rows )
    {
      $list = array();
      foreach( $rows as $row )
      {
          $_item = (object) array(
            'account_code'          =>'',// $row->account_code,
            'account_description'   => $row->account_description,
            'debit'                 => $row->nominal,
            'credit'                => 0,
          );
         
          $list []= $_item;
      }
      return array_merge(
        $list
      ); 
    }
    
    public function convert2krebit( $rows )
    {
      $list = array();
      foreach( $rows as $row )
      {
          $_item = (object) array(
            'account_code'  => '',//$row->account_code,
            'account_description'   => $row->account_description,
            'debit'         => 0,
            'credit'        => $row->nominal,
          );
         
          $list []= $_item;
      }
      return $list;
    }

    public function sum( $rows, $title = 'Jumlah Bulan Ini' )
    {
      $item = (object) array(
        'account_code'         => '',
        'account_description'  => $title ,
        'debit'                => 0 ,
        'credit'               => 0 ,
      );

      foreach( $rows as $row )
      {
        $item->debit += $row->debit;
        $item->credit += $row->credit;
      }
      
      return $item;
    }
    ########################################################################################################################################################################################################
		########################################################################################################################################################################################################
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
}
?>
