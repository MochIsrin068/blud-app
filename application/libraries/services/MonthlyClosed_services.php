<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MonthlyClosed_services{
    private $leftovers =0;
    private $last_balance =0;
    private $acumulation =0;
      private $_month = array(
        '',
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
    );

    function __construct(){
        $this->load->model(
          array(
            'm_account' ,
            'm_plan',
            'm_bookkeeping',
          ));

    }

    public function __get($var)
  	{
  		return get_instance()->$var;
  	}

    public function get_dpa_table_config( $_page, $start_number = 1 )
    {
        $table["header"] = array(
          // 'title' => 'Judul',
          // 'description' => 'Deskripsi',
          'year' => 'Tahun Angaran',
        );
        $table["number"] = $start_number;
        $table[ "action" ] = array(
            array(
              "name" => "Detail",
              "type" => "link",
              "url" => site_url( $_page."monthly_detail/"),
              "button_color" => "primary",
              "param" => "id",
            ),
        );
      
      return $table;
    }
    public function get_montly_table_config( $_page, $start_number = 1 )
    {
        $table["header"] = array(
          'month'           =>  'Bulan' ,
          'closing_balance' =>  'Saldo Tutup' ,
          // 'cash'            =>  'Uang Tunai' ,
          // 'bank_balance'    =>  'Saldo Bank' ,
          // 'date'            =>  'Tanggal' ,
        );
        $table["number"] = $start_number;
        $table[ "action" ] = array(
            array(
              "name" => "Lihat",
              "type" => "link",
              "url" => site_url( $_page."detail_report/"),
              "button_color" => "primary",
              "param" => "id",
            ),
        );
      
      return $table;
    }

    public function convert2report( $rows, $last_lra = NULL )
    {
      $this->leftovers = 0;
      $this->m_account->get_pendapatan_end_point(); //jalankan ini dlulu
      $pendapatan_account_ids = $this->m_account->get_account_ids_list();
      $this->m_account->get_belanja_end_point( ['7'], 2 );//jalankan ini dlulu
      $pembiayaan_account_ids = $this->m_account->get_account_ids_list();

      $month_year = ( !empty( $rows ) )? date("M Y", $rows[0]->date ) : '' ;
      $time = ( !empty( $rows ) )? $rows[0]->date  : 0 ;
      $_list = array();
      $_list_2 = array();
      $pokok = NULL;
      $delimiter = (object) array(
        'serial_number'  => '',
        'account_code'  => '',
        'description'   => 'Terima Strn Angsuran dari Nasabah Bln '.$month_year,
        'date'          => '',
        'debit'         => '',
        'credit'        => '',
      );

      foreach( $rows as $row )
      {
          $_item = (object) array(
            'serial_number'  => $row->serial_number,
            'account_code'  => $row->account_code,
            'description'   => $row->description,
            'date'          => $row->date,
            'debit'         => 0,
            'credit'        => 0,
          );
          if( in_array( $row->account_id, array_merge( $pendapatan_account_ids, $pembiayaan_account_ids ) ) )
          {
            if( $row->account_id == 181 || $row->account_id == 189  )
            {
                $_item->description = "* Pokok"; //id dari penerimaan dana bergulir
            }
            // if( in_array( $row->account_id, $pendapatan_account_ids ) ) $_item->description = "* Pokok"; //151 = id dari 4,1,4
            // if( $row->account_id == 181 ) $_item->description = "*Jasa ";
            if( $row->account_id == 151  )
            {
                $_item->description = "* Jasa Giro : - ".date("M Y", $row->date );// PAD
            } 
            // if( $row->account_id == 183 )
            // {
            //     $_item->credit = $row->nominal;
            //     $_list []= $_item;
            // }
            // else
            // {
              $_item->debit = $row->nominal;
              $_list_2 []= $_item;
            // } 
          }
          else
          {
            $_item->credit = $row->nominal;
            $_list []= $_item;
          }

      }
      $_list_2 []= (object) array(
        'serial_number'  => '',
        'account_code'  => '',
        'description'   => '* Jasa',
        'date'          => ' ',
        'debit'         => 0,
        'credit'        => 0,
      );
      $this_month_sum = $this->get_sum( array_merge( $_list, $_list_2 ) );
      if( $last_lra != NULL ) //LRA bulan lalu kalau NULL berarti dia bulan satu dan mengambil sisa bku tahun lalu bulan 12
      {
        $this->load->library('services/Report_LRA_services' );
        $LRA_services = new Report_LRA_services;
        $penerimaan = $LRA_services->get_penerimaan_dagur($last_lra->penerimaan  );//pembiayaan dagur
        if( $penerimaan != NULL )
          $sum_pendapatan = $LRA_services->get_sum(  array_merge( $last_lra->pendapatan, [ $penerimaan ] )  );
        else
          $sum_pendapatan = $LRA_services->get_sum(   $last_lra->pendapatan  );

        $sum_pengeluaran = $LRA_services->get_sum(  array_merge( $last_lra->pegawai, $last_lra->barang )  );

        $month = (int) date('m', $time );
        $year = (int) date('Y', $time );
        $month --;
        $time = strtotime( $month."/15/".$year." 00:00:01" );
        $bookkeeping = $this->m_bookkeeping->bookkeeping_by_time( $time )->row();

        $_item = (object) array(
          'serial_number'  => '',
          'account_code'  => '',
          'description'   => 'Jumlah S/D Bulan Lalu',
          'date'          => '',
          'debit'         =>   $bookkeeping->debit ,
          'credit'        =>  $sum_pengeluaran->until_this_month ,
        );
        $until_last_month_sum = $_item;
      }
      else
      {
        $month = 12;
        $year = (int) date('Y', $time );
        $year --;
        $time = strtotime( $month."/15/".$year." 00:00:01" );
        $bookkeeping = $this->m_bookkeeping->bookkeeping_by_time( $time )->row();
        $closing_balance = ( $bookkeeping != NULL ) ? $bookkeeping->closing_balance : 0 ;
        $until_last_month_sum = (object) array(
          'serial_number'  => '',
          'account_code'  => '',
          'description'   => 'Jumlah Sisa Tahun '.$year,
          'date'          => '',
          'debit'         =>  $closing_balance ,
          'credit'        =>  0 ,
        );
      }
      $this->last_balance = $until_last_month_sum->debit;

      $until_this_month_sum = $this->get_sum( array_merge( [ $this_month_sum ], [ $until_last_month_sum ] ) , 'Jumlah S/D Bulan ini' );
      $this->acumulation = ( $until_this_month_sum );
      $this->leftovers = $this->get_leftovers( $until_this_month_sum );
      $delimiter_2 = (object) array(
        'serial_number'  => '',
        'account_code'  => '',
        'description'   => '',
        'date'          => '',
        'debit'         => '',
        'credit'        => '',
      );
      return array_merge(
        $_list,
        [$delimiter], 
        $_list_2, 
        [$delimiter_2],
        [ $this_month_sum ],
        [ $until_last_month_sum ],
        [ $until_this_month_sum ],
        [ $this->leftovers ],
        [$delimiter_2],
        [$delimiter_2]
       );
    }
    public function get_last_balance( )
    {
        return $this->last_balance ;
    }
    public function get_acumulation( )
    {
        return $this->acumulation;
    }
    public function get_footer( $bookkeeping )
    {
      $year = (int) date('Y', $bookkeeping->date );
      $month = (int) date('m', $bookkeeping->date );
      $day = (int) date('d', $bookkeeping->date );
        $footer = array(  
          (object) array(
            'serial_number'  => '',
            'description'  => 'Pada Hari ini Tanggal '.$day." ".$this->_month[ $month ]." ".$year,
            'account_code'   => '',
            'date'          => '',
            'debit'         => '',
            'credit'        => '',
          ),
          (object) array(
            'serial_number'  => '',
            'description'  => 'Buku Kas Umum Di Tutup Dengan Saldo Rp.'.number_format( $bookkeeping->closing_balance ) ,
            'account_code'   => '',
            'date'          => '',
            'debit'         => '',
            'credit'        => '',
          ),
          (object) array(
            'serial_number'  => '',
            'description'  => 'Yang Terdiri Dari : ',
            'account_code'   => '',
            'date'          => '',
            'debit'         => '',
            'credit'        => '',
          ),
          (object) array(
            'serial_number'  => '',
            'description'  => 'a. Uang Tunai : Rp.'.number_format( $bookkeeping->cash ) ,
            'account_code'   => '',
            'date'          => '',
            'debit'         => '',
            'credit'        => '',
          ),
          (object) array(
            'serial_number'  => '',
            'description'  => 'b. Saldo Bank : Rp.'.number_format( $bookkeeping->bank_balance ) ,
            'account_code'   => '',
            'date'          => '',
            'debit'         => '',
            'credit'        => '',
          ),
          (object) array(
            'serial_number'  => '',
            'description'  => 'c. Lain-lain : Rp.'.number_format( $bookkeeping->other ) ,
            'account_code'   => '',
            'date'          => '',
            'debit'         => '',
            'credit'        => '',
          ),
        );
      
      return $footer;
    }
    public function get_general_cashes_table_config( $_page, $start_number = 1 )
    {
        $table["header"] = array(
          'serial_number'  => 'No Urut',
          'date'          => 'Tanggal',
          'account_code'  => 'Kode Rekening',
          'description'   => 'Deskripsi',
          'debit'         => 'Penerimaan',
          'credit'        => 'Pengeluaran',
        );
        $table["number"] = $start_number;
      
      return $table;
    }
    public function get_closed_report_config( $_page, $start_number = 1 )
    {
        $table["header"] = array(
          'name' =>  'Bulan' ,
        );
        $table["number"] = $start_number;
      
      return $table;
    }
    public function get_monthly_rows( $plan_id )
    {
        $month_list = array();
        for( $i = 1; $i <= 12; $i++ )
        {
          $month_list[] = (object) array(
              'id' => $plan_id.'/'.$i,
              'name' =>  $this->_month[ $i ] ,
          );
        }
        
      
      return $month_list;
    }

    public function get_sum( $rows, $title = 'Jumlah Bulan Ini' )
    {
      $item = (object) array(
        'serial_number'  => '',
        'account_code'  => '',
        'description'   => $title,
        'date'          => '',
        'debit'         => 0,
        'credit'        => 0,
      );
        foreach( $rows as $row )
        {
          $item->debit += $row->debit;
          $item->credit += $row->credit;
        }
      
      return $item;
    }

    public function get_leftovers( $row, $title = 'Sisa' )
    {
      $item = (object) array(
        'serial_number'  => '',
        'account_code'  => '',
        'description'   => $title,
        'date'          => '',
        'debit'         => 0,
        'credit'        => $row->debit - $row->credit,
      );
      return $item;
    }

}
?>
