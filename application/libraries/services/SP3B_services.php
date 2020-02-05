<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class SP3B_services{
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
            $sp3b_item->pendapatan_sum = $pendapatan[ $i ]->nominal;
         }
         if( $i < $list[ 1 ] ) //pendapatan
         {
            $sp3b_item->belanja_account_code = $belanja[ $i ]->account_code;
            $sp3b_item->belanja_sum = $belanja[ $i ]->nominal;
         }
         if( $i < $list[ 2 ] ) //pendapatan
         {
            $sp3b_item->pembiayaan_account_code = $pembiayaan[ $i ]->account_code;
            $sp3b_item->pembiayaan_sum = $pembiayaan[ $i ]->nominal;
         }
         array_push( $list_sp3b, $sp3b_item );
      }
    
      return $list_sp3b;
    }

    public function get_sp3b_sum( array $pendapatan,array  $belanja,array  $pembiayaan )
    {
      $list_sp3b = array();
     
      $sp3b_item = (object) array(
        'pendapatan_account_code' => 'Jumlah Pendapatan',
        'pendapatan_sum' => ( !empty( $pendapatan ) ) ? $pendapatan[0]->nominal : 0,
        'belanja_account_code' =>  'Jumlah Belanja',
        'belanja_sum' =>  ( !empty( $belanja ) ) ? $belanja[0]->nominal : 0,
        'pembiayaan_account_code' => 'Jumlah Pembiayaan',
        'pembiayaan_sum' => ( !empty( $pembiayaan ) ) ? $pembiayaan[0]->nominal : 0,
      );
      array_push( $list_sp3b, $sp3b_item );
      return $list_sp3b;
    }

}
?>
