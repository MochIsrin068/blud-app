<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Report_LRA_services{
    protected $pendapatan_budget_plans = array();
    protected $pegawai_budget_plans = array();
    protected $barang_budget_plans = array();
    protected $start_month = NULL;
    protected $end_month = NULL;
    protected $MONTHLY_LRA = array();
    function __construct(  ){
        $this->load->model(array( 
          'm_account' ,
          'm_budget_plan' ,
          'm_generalcash' ,
          'm_budget_realization' ,
          'm_realization' ,
        ));
    }

    public function __get($var)
  	{
  		return get_instance()->$var;
  	}
    public function get_lra( $realization_id , $month  )
    {
          $this->m_account->get_pendapatan_end_point(); //jalankan ini dlulu
          $account_ids = $this->m_account->get_account_ids_list();
          $pendapatan_realization = $this->m_budget_realization->budget_realization( $account_ids, $realization_id , $month )->result();

          $this->m_account->get_belanja_end_point( ['5.2.1'], 2 );//jalankan ini dlulu
          $account_ids = $this->m_account->get_account_ids_list();
          $pegawai_realization = $this->m_budget_realization->budget_realization( $account_ids, $realization_id , $month )->result();

          $this->m_account->get_belanja_end_point( ['5.2.2', '5.1.2' ], 2 );//jalankan ini dlulu
          $account_ids = $this->m_account->get_account_ids_list();
          $barang_realization = $this->m_budget_realization->budget_realization( $account_ids, $realization_id , $month )->result();

          $this->m_account->get_belanja_end_point( ['7'], 2 );//jalankan ini dlulu
          $account_ids = $this->m_account->get_account_ids_list();
          $penerimaan_realization = $this->m_budget_realization->budget_realization( $account_ids, $realization_id , $month )->result();

          $defisit = $this->m_budget_realization->budget_realization( [1000], $realization_id , $month )->row();
          if( $defisit == NULL ){
            return FALSE;
          }
          $defisit->difference = 0;
          $defisit->account_code = '';
          $data = (object) array(
            'pendapatan' => $pendapatan_realization,
            'pegawai' => $pegawai_realization,
            'barang' => $barang_realization,
            'penerimaan' => $penerimaan_realization,
            'defisit' => $defisit,
          );

          return $data;
    }
    public function get_lra_table_config()
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
    public function get_defisit( $pendapatan, $belanja, $realization_id,  $month )
    {
      $item = (object) array(
        "account_id" => 1000,
        "realization_id" => $realization_id,
        "account_description" => 'SURPLUS/ (DEFISIT)',
        "account_code" => '',
        "dpa_budget" =>  0,
        "last_month" => $pendapatan->last_month - $belanja->last_month ,
        "this_month" => $pendapatan->this_month - $belanja->this_month ,
        "until_this_month" => $pendapatan->until_this_month - $belanja->until_this_month ,
        "difference" => 0 ,
        "month" => $month ,
      );
      
      return $item;
    }

    public function get_penerimaan_dagur( $lra_rows )
    {
      foreach( $lra_rows as $row )
      {
        if( $row->account_id == 181 ) return $row; //151 = dari penerimaan dana bergulir
      }
      return NULL;
    }
    public function get_pembiayaan_netto( $lra_rows ) //pembiayaan dagur
    {
      // $item = (object) array(
      //   "account_id" => '',
      //   "account_description" => 'Pembiayaan Netto',
      //   "account_code" => '',
      //   "dpa_budget" =>  0 ,
      //   "last_month" => $lra_rows[0]->last_month - $lra_rows[1]->last_month ,
      //   "this_month" => $lra_rows[0]->this_month - $lra_rows[1]->this_month ,
      //   "until_this_month" => $lra_rows[0]->until_this_month - $lra_rows[1]->until_this_month  ,
      //   "difference" => 0,
      // );
        $_penerimaan_pinjaman = array();
        $_pengeluaran__pinjaman = array();
        foreach( $lra_rows as $row )
        {
          if( $row->account_id == 181 ) //Penerimaan Kembali Pinjaman Dana Bergulir
            $_penerimaan_pinjaman []= $row;
          if( $row->account_id == 183 ) 
            $_pengeluaran__pinjaman []= $row;
          // $item->last_month -= $row->last_month;
          // $item->this_month -= $row->this_month;
          // $item->until_this_month -= $row->until_this_month;
        }
        // $item->last_month = abs( $item->last_month );
        // $item->this_month = abs($item->this_month);
        // $item->until_this_month = abs($item->until_this_month);
        $_penerimaan_pinjaman = $this->get_sum( $_penerimaan_pinjaman );
        $_pengeluaran__pinjaman = $this->get_sum( $_pengeluaran__pinjaman );

        $item = (object) array(
          "account_id" => '',
          "account_description" => 'Pembiayaan Netto',
          "account_code" => '',
          "dpa_budget" =>  0 ,
          "last_month" => $_penerimaan_pinjaman->last_month - $_pengeluaran__pinjaman->last_month,
          "this_month" => $_penerimaan_pinjaman->this_month - $_pengeluaran__pinjaman->this_month ,
          "until_this_month" => $_penerimaan_pinjaman->until_this_month - $_pengeluaran__pinjaman->until_this_month ,
          "difference" => 0,
        );
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
    
    public function get_pendapatan_block( $bold = TRUE )
    {
        $item = array(
            (object) array(
              "account_id" => '',
              "account_code" => '4',
              "account_description" => 'Pendapatan',
              "dpa_budget" =>  '',
              "last_month" => '',
              "this_month" => '',
              "until_this_month" => '' ,
              "difference" => '',
            )
        );
        if( $bold )
        {
          $item[0]->account_code = '<b>4</b>';
          $item[0]->account_description = '<b>Pendapatan</b>';
        }

      
        return $item;
    }
    public function get_pegawai_block( $bold = TRUE )
    {
        $item = array(
            (object) array(
              "account_id" => '',
              "account_code" => '5',
              "account_description" => 'Belanja',
              "dpa_budget" =>  '',
              "last_month" => '',
              "this_month" => '',
              "until_this_month" => '' ,
              "difference" => '',
            ),
            (object) array(
              "account_id" => '',
              "account_code" => '5.2.1',
              "account_description" => 'Belanja Pegawai',
              "dpa_budget" =>  '',
              "last_month" => '',
              "this_month" => '',
              "until_this_month" => '' ,
              "difference" => '',
            ),
        );
        if( $bold )
        {
          $item[0]->account_code = '<b>5 </b>';
          $item[0]->account_description = '<b> Belanja </b> ';

          $item[1]->account_code = '<b>5.2.1 </b>';
          $item[1]->account_description = '<b> Belanja Pegawai </b> ';
        }
        return $item;
    }
    public function get_belanja_block( $bold = TRUE )
    {
        $item = array(
            (object) array(
              "account_id" => '',
              "account_code" => '',
              "account_description" => 'Belanja Barang Jasa',
              "dpa_budget" =>  '',
              "last_month" => '',
              "this_month" => '',
              "until_this_month" => '' ,
              "difference" => '',
            )
        );
        if( $bold )
        {
          $item[0]->account_description = '<b> Belanja Barang Jasa </b> ';
        }
        return $item;
    }

    public function get_penerimaan_block( $bold = TRUE )
    {
        $item = array(
            (object) array(
              "account_id" => '',
              "account_code" => '',
              "account_description" => 'Pembiayaan',
              "dpa_budget" =>  '',
              "last_month" => '',
              "this_month" => '',
              "until_this_month" => '' ,
              "difference" => '',
            )
        );
        if( $bold )
        {
          $item[0]->account_description = '<b> Pembiayaan </b> ';
        }
        return $item;
    }

    public function get_pendapatan_budget_plans( $plan_id )
    {
        $this->m_account->get_pendapatan_end_point(); //jalankan ini dlulu
        $pendapatan_ids = $this->m_account->get_account_ids_list();
        return (object) array(
            "account_ids" => $pendapatan_ids,
            "budget_plans" => $this->m_budget_plan->budget_plans_total_price( $plan_id, $pendapatan_ids )->result() ,
        );
    }

    public function get_pegawai_budget_plans( $plan_id )
    {
        $this->m_account->get_belanja_end_point( ['5.2.1'], 2 );//jalankan ini dlulu
        $belanja_ids = $this->m_account->get_account_ids_list();
        return (object) array(
          "account_ids" => $belanja_ids,
          "budget_plans" => $this->m_budget_plan->budget_plans_total_price( $plan_id, $belanja_ids )->result(),
        );
    }

    public function get_barang_budget_plans( $plan_id )
    {
        $this->m_account->get_belanja_end_point( ['5.2.2', '5.1.2' ], 2 );//jalankan ini dlulu
        $barang_ids = $this->m_account->get_account_ids_list();
        return (object) array(
          "account_ids" => $barang_ids,
          "budget_plans" => $this->m_budget_plan->budget_plans_total_price( $plan_id, $barang_ids )->result(),
        );
    }

    public function get_penerimaan_budget_plans( $plan_id )
    {
        $this->m_account->get_belanja_end_point( ['7'], 2 );//jalankan ini dlulu
        $_ids = $this->m_account->get_account_ids_list();
        return (object) array(
          "account_ids" => $_ids,
          "budget_plans" => $this->m_budget_plan->budget_plans_total_price( $plan_id, $_ids )->result(),
        );
    }

    public function generate_lra( $realization_id , $month )
    {
      $DATA_LIST = array();
      // $realization = $this->m_realization->realization_by_plan_id( $plan_id )->row();
      $realization = $this->m_realization->realization( $realization_id )->row();

      if( $realization->id == NULL ) return FALSE;
      // if( ! $this->m_budget_realization->is_monthly_exist( $month - 1, $realization->id )  &&  $month != 1 )
      // {
      //     return FALSE;
      // }
      
      $start_date = strtotime( $month."/1/".$realization->year." 00:00:01" );
      $end_date = strtotime( $month."/31/".$realization->year." 23:59:00");
      
      // pendapatan
      $pendapatan_budget_plans = $this->get_pendapatan_budget_plans( $realization->plan_id );
      $pendapatan_generalcash =  $this->m_generalcash->general_cashes_account_ids( $pendapatan_budget_plans->account_ids ,$start_date, $end_date  )->result() ;
      
      $pendapatan_rows = $this->sync_realization( $pendapatan_budget_plans->budget_plans , $pendapatan_generalcash, $month, $realization->id );
      $sum_pendapatan = $this->get_sum($pendapatan_rows);
      ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      $pegawai_budget_plans = $this->get_pegawai_budget_plans( $realization->plan_id );
      $pegawai_generalcash =  $this->m_generalcash->general_cashes_account_ids( $pegawai_budget_plans->account_ids ,$start_date, $end_date  )->result() ;

      $pegawai_rows = $this->sync_realization( $pegawai_budget_plans->budget_plans , $pegawai_generalcash, $month, $realization->id );
      $sum_pegawai = $this->get_sum($pegawai_rows);
      ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      $barang_budget_plans = $this->get_barang_budget_plans( $realization->plan_id );
      $barang_generalcash =  $this->m_generalcash->general_cashes_account_ids( $barang_budget_plans->account_ids ,$start_date, $end_date  )->result() ;
      
      $barang_rows = $this->sync_realization( $barang_budget_plans->budget_plans , $barang_generalcash, $month, $realization->id );
      $sum_barang = $this->get_sum($barang_rows );
      ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      $pembiayaan_budget_plans = $this->get_penerimaan_budget_plans( $realization->plan_id );
      $pembiayaan_generalcash =  $this->m_generalcash->general_cashes_account_ids( $pembiayaan_budget_plans->account_ids ,$start_date, $end_date  )->result() ;
      
      $pembiayaan_rows = $this->sync_realization( $pembiayaan_budget_plans->budget_plans , $pembiayaan_generalcash, $month, $realization->id );
      ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      $defisit = $this->get_defisit( $sum_pendapatan, $this->get_sum( array( $sum_pegawai, $sum_barang ) ) , $realization->id,  $month );
      ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

      $DATA_LIST = array_merge( 
        $DATA_LIST, 
        $pendapatan_rows, 
        $pegawai_rows, 
        $barang_rows, 
        $pembiayaan_rows, 
        [$defisit] );
      return $DATA_LIST;
    }

    public function sync_realization( $budget_plans, $generalcashes, $month = NULL, $realization_id = NULL )
    {
      $report = array();
      if( count( $generalcashes ) > count( $budget_plans ) )
      {
          foreach( $generalcashes as $generalcash )
          {
            $report[] = $this->generalcash_to_budget_plan( $generalcash, $budget_plans, $month, $realization_id  );
          }
      }
      else
      {
          foreach( $budget_plans as $budget_plan )
          {
            $report[] = $this->merge( $budget_plan, $generalcashes, $month, $realization_id  );
          }
      }
      return $report;
    }

    protected function generalcash_to_budget_plan( $generalcash, $budget_plans , $month = NULL, $realization_id = NULL )
    {
      $last_month = 0;
      $last_budget_realization = $this->m_budget_realization->budget_realization( $generalcash->account_id,  $realization_id , $month - 1  )->row();
      if( $last_budget_realization != NULL )
      {
        $last_month  = $last_budget_realization->until_this_month;
      }
      foreach( $budget_plans as $budget_plan )
      {
        if( $budget_plan->account_id == $generalcash->account_id )
        {
          $item = (object) array(
            "account_id" => $budget_plan->account_id,
            "realization_id" => $realization_id,
            "account_description" => $budget_plan->account_description,
            "account_code" => $budget_plan->account_code,
            "dpa_budget" =>  (double)  $budget_plan->total_price,
            "last_month" => $last_month,
            "this_month" => (double) $generalcash->total_nominal ,
            "until_this_month" => (double) $generalcash->total_nominal + $last_month ,
            "difference" => (double) $budget_plan->total_price - $generalcash->total_nominal - $last_month ,
            "month" => $month ,
            "date" => $generalcash->date ,
          );
          return $item;
        }
      }

      $item = (object) array(
        "account_id" => $generalcash->account_id,
        "realization_id" => $realization_id,
        "account_description" => $generalcash->account_description,
        "account_code" => $generalcash->account_code,
        "dpa_budget" =>  0,
        "last_month" => $last_month,
        "this_month" => (double) $generalcash->total_nominal ,
        "until_this_month" => (double) $generalcash->total_nominal + $last_month ,
        "difference" => (double) abs( $generalcash->total_nominal + $last_month  ) ,
        "month" => $month ,
        "date" => $generalcash->date ,
      );
      return $item;
    }

    protected function merge( $budget_plan, $generalcashes , $month = NULL, $realization_id = NULL )
    {
      
      $last_month = 0;
      $last_budget_realization = $this->m_budget_realization->budget_realization( $budget_plan->account_id,  $realization_id , $month - 1  )->row();
      if( $last_budget_realization != NULL )
      {
        $last_month  = $last_budget_realization->until_this_month;
      }
      foreach( $generalcashes as $generalcash )
      {
        if( $budget_plan->account_id == $generalcash->account_id )
        {
          $item = (object) array(
            "account_id" => $budget_plan->account_id,
            "realization_id" => $realization_id,
            "account_description" => $budget_plan->account_description,
            "account_code" => $budget_plan->account_code,
            "dpa_budget" =>  (double)  $budget_plan->total_price,
            "last_month" => $last_month,
            "this_month" => (double) $generalcash->total_nominal ,
            "until_this_month" => (double) $generalcash->total_nominal + $last_month ,
            "difference" => (double) $budget_plan->total_price - $generalcash->total_nominal - $last_month ,
            "month" => $month ,
            "date" => $generalcash->date ,            
          );
          return $item;
        }
      }
      $realization =  $this->m_realization->realization( $realization_id )->row();

      $item = (object) array(
        "account_id" => $budget_plan->account_id,
        "realization_id" => $realization_id,
        "account_description" => $budget_plan->account_description,
        "account_code" => $budget_plan->account_code,
        "dpa_budget" =>  (double) $budget_plan->total_price,
        "last_month" => $last_month,
        "this_month" => 0 ,
        "until_this_month" => $last_month,
        "difference" => (double) $budget_plan->total_price - $last_month ,
        "month" => $month ,
        "date" => strtotime( $month."/30/".$realization->year." 00:00:01")  ,  
      );
      return $item;
    }

}
?>
