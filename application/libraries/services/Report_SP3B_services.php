<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Report_SP3B_services{
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

    public function get_sp3b_table_config()
    {
        $table["header"] = array(
          'pendapatan_account_code' => 'Kode Rekening',
          'pendapatan_sum' => 'Jumlah',
          'belanja_account_code' => 'Kode Rekening',
          'belanja_sum' => 'Jumlah',
          'pembiayaan_account_code' => 'Kode Rekening',
          'pembiayaan_sum' => 'Jumlah',
        );
      
      return $table;
    }
    public function get_sp3b( $pendapatan, $belanja, $pembiayaan )
    {
      $list =  [ count( $pendapatan ), count( $belanja ), count( $pembiayaan ) ];
      $max = $list[0];
      foreach( $list as $item )
      {
        if( $item > $max ) $max = $item;
      }
      $list_sp3b = array();
      for( $i =0 ; $i < $max; $i++ )
      {
         $sp3b_item = (object) array(
            'pendapatan_account_code' => '',
            'pendapatan_sum' => 0,
            'belanja_account_code' => '',
            'belanja_sum' => 0,
            'pembiayaan_account_code' => '',
            'pembiayaan_sum' => 0,
        );
         if( $i < $list[0] ) //pendapatan
         {
            $sp3b_item->pendapatan_account_code = $pendapatan[ $i ]->account_code;
            $sp3b_item->pendapatan_sum = $pendapatan[ $i ]->this_month;
         }
         if( $i < $list[ 1 ] ) //pendapatan
         {
            $sp3b_item->belanja_account_code = $belanja[ $i ]->account_code;
            $sp3b_item->belanja_sum = $belanja[ $i ]->this_month;
         }
         if( $i < $list[ 2 ] ) //pendapatan
         {
            $sp3b_item->pembiayaan_account_code = $pembiayaan[ $i ]->account_code;
            $sp3b_item->pembiayaan_sum = $pembiayaan[ $i ]->this_month;
         }
         array_push( $list_sp3b, $sp3b_item );
      }
      $sp3b_item = (object) array(
        'pendapatan_account_code' => 'Jumlah Pendapatan',
        'pendapatan_sum' => $this->get_sum( $pendapatan )->this_month ,
        'belanja_account_code' => 'Jumlah Belanja',
        'belanja_sum' => $this->get_sum( $belanja )->this_month ,
        'pembiayaan_account_code' => 'Jumlah Pembiayaan',
        'pembiayaan_sum' => $this->get_sum( $pembiayaan )->this_month ,
      );
      array_push( $list_sp3b, $sp3b_item );
      return $list_sp3b;
    }

    public function get_sum( $lra_rows, $title = 'Jumlah' )
    {
      $item = (object) array(
        "account_id" => '',
        "account_description" => $title,
        "account_code" => '',
        "dpa_budget" =>  0,
        "last_month" => 0,
        "this_month" => 0,
        "until_this_month" => 0 ,
        "difference" => 0 ,
      );
        foreach( $lra_rows as $row )
        {
          $item->dpa_budget += $row->dpa_budget;
          $item->last_month += $row->last_month;
          $item->this_month += $row->this_month;
          $item->until_this_month += $row->until_this_month;
          $item->difference += $row->difference;
        }
      
      return $item;
    }


    public function bold( $lra_row )
    {

      $item = (object) array(
        "account_id" => '<b>'. ( $lra_row->account_id ) .'</b>',
        "account_description" => '<b>'. ( $lra_row->account_description ) .'</b>',
        "account_code" => '<b>'. ( $lra_row->account_code ) .'</b>',
        "dpa_budget" =>  '<b>'. number_format( $lra_row->dpa_budget ) .'</b>',
        "last_month" => '<b>'. number_format( $lra_row->last_month ) .'</b>',
        "this_month" => '<b>'. number_format( $lra_row->this_month ) .'</b>',
        "until_this_month" => '<b>'. number_format( $lra_row->until_this_month ) .'</b>',
        "difference" => '<b>'. number_format( $lra_row->difference ) .'</b>',
      );
      
      return $item;
    }

}
?>
