<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Generalcash_services{
	protected $id;
	protected $account_id;
	protected $description;
	protected $nominal;
	protected $date;
	protected $user;
	protected $party;
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
        $this->load->model('m_budget_plan');
        
        $this->id		          ='';
        $this->account_id	    ="";
        $this->description	  ="";
        $this->nominal		    ="";
        $this->date	          ="";
        $this->user	          ="-";
        $this->party	        ="-";

    }

    public function __get($var)
  	{
  		return get_instance()->$var;
  	}
    public function get_table_config( $_page, $start_number = 1 )
    {
        $table["header"] = array(
          'serial_number' => 'No BKU',
          'account_code' => 'Kode Rekening',
          'description' => 'Deskripsi',
          'nominal' => 'Nominal',
          'date' => 'Tanggal',
        );
        $table["number"] = $start_number;
        $table[ "action" ] = array(
            array(
              "name" => 'Kuitansi',
              "type" => "modal_form",
              "modal_id" => "verification_",
              "url" => site_url( $_page."generate_receipt/"),
              "button_color" => "warning",
              "param" => "id",
              "form_data" => array(
                "id" => array(
                  'type' => 'hidden',
                  'label' => "id",
                ),
                "date" => array(
                  'type' => 'hidden',
                  'label' => "Tanggal",
                ),
                "description" => array(
                  'type' => 'hidden',
                  'label' => "Deskripsi",
                ),
                "nominal" => array(
                  'type' => 'hidden',
                  'label' => "Nominal",
                ),
                "account_code" => array(
                  'type' => 'hidden',
                  'label' => "Nominal",
                ),
                "id" => array(
                  'type' => 'hidden',
                  'label' => "id",
                ),
                "spelled_out" => array(
                  'type' => 'text',
                  'label' => "Nominal Terbilang",
                ),
              ),
              "title" => "RBA",
              "data_name" => "description",
            ),
            array(
              "name" => "Pilihan",
              "type" => "button_dropdowns",
              "links" => array(
                  array( 
                      "name" => "Detail",
                      'url' => site_url( $_page."detail/"),
                  ),
                  array( 
                    "name" => "Edit",
                    'url' => site_url( $_page."edit/"),
                  ),
              ),
              "param" => "id",
            ),
            array(
              "name" => 'X',
              "type" => "modal_delete",
              "modal_id" => "delete_planing_",
              "url" => site_url( $_page."delete/"),
              "button_color" => "danger",
              "param" => "id",
              "form_data" => array(
                "id" => array(
                  'type' => 'hidden',
                  'label' => "id",
                ),
                "date" => array(
                  'type' => 'hidden',
                  'label' => "date",
                ),
              ),
              "title" => "RBA",
              "data_name" => "description",
            ),
        );
      
      return $table;
    }

    public function validation_config( )
    {
      $config = array(
        array(
          'field' => 'account_id',
           'label' => 'No Rekening',
           'rules' =>  'trim|required',
        ),
        array(
          'field' => 'description',
           'label' => 'Deskripsi',
           'rules' =>  'trim|required',
        ),
        array(
          'field' => 'nominal',
           'label' => 'Nominal',
           'rules' =>  'trim|required',
        ),
        array(
          'field' => 'date',
           'label' =>('Tanggal'),
           'rules' =>  'trim|required',
        ),
        array(
          'field' => 'spelled_out',
           'label' =>('Terbilang'),
           'rules' =>  'trim|required',
        ),
      );
      return $config;
    }

    public function form_data( $general_cash_id = -1 )
    {
      if( $general_cash_id != -1 )
      {
        $generalcash 				= $this->m_generalcash->general_cash( $general_cash_id )->row();
        $this->id		          = $generalcash->id ;
        $this->account_id	    = $generalcash->account_id ;
        $this->description	  = $generalcash->description ;
        $this->nominal		    = $generalcash->nominal ;
        $this->date	          = $generalcash->date ;
      }
        $form_data = array(
          "date" => array(
            'type' => 'date',
            'label' => "Tanggal ( Bulan/Tanggal/Tahun ) ",
            // 'value' => ( $this->date ) ? date('Y-m-d', $this->date ) : date('Y-m-d')  ,
            'value' => ( $this->date ) ? date('m/d/Y', $this->date ) : date('m/d/Y')  ,
          ),
          "description" => array(
            'type' => 'text',
            'label' => "Deskripsi",
            'value' => $this->description,
          ),
          "nominal" => array(
            'type' => 'number',
            'label' => "Nominal",
            'value' => $this->nominal,
          ),
          "spelled_out" => array(
            'type' => 'hidden',
            'label' => "Terbilang",
            'value' => '--',
          ),
          "id" => array(
            'type' => 'hidden',
            'label' => "id",
            'value' => $this->id,
          ),
        );
        return $form_data;
    }

    ################################################################################################################################
    ################################################################################################################################
    ################################################################################################################################
    ################################################################################################################################
    ################################################################################################################################
    ################################################################################################################################
    ################################################################################################################################
    ################################################################################################################################
    // PERBAIKAN
    public function get_montly_table_config( $_page, $start_number = 1 )
    {
        $table["header"] = array(
          'month'           =>  'Bulan' ,
        );
        $table["number"] = $start_number;
        $table[ "action" ] = array(
            array(
              "name" => "Lihat",
              "type" => "link",
              "url" => site_url( $_page),
              "button_color" => "primary",
              "param" => "month",
            ),
        );
      
      return $table;
    }
    public function get_general_cashes_table_config( $_page,$year, $month,  $start_number=1 )
    {
        $table["header"] = array(
          // 'nominal'  => 'No Urut',
          'date'          => 'Tanggal',
          'account_code'  => 'Kode Rekening',
          'description'   => 'Deskripsi',
          'debit'         => 'Penerimaan',
          'credit'        => 'Pengeluaran',
        );
        $table["number"] = $start_number;
        $table[ "action" ] = array(
          array(
            "name" => 'Kuitansi',
            "type" => "modal_form_blank",
            "modal_id" => "receipt_",
            "url" => site_url( $_page."generate_receipt/".$year."/".$month  ),
            "button_color" => "warning",
            "param" => "account_id",
            "form_data" => array(
              "id" => array(
                'type' => 'hidden',
                'label' => "id",
              ),
              "date" => array(
                'type' => 'hidden',
                'label' => "Tanggal",
              ),
              "description" => array(
                'type' => 'hidden',
                'label' => "Deskripsi",
              ),
              "nominal" => array(
                'type' => 'hidden',
                'label' => "Nominal",
              ),
              "account_code" => array(
                'type' => 'hidden',
                'label' => "Nominal",
              ),
              "id" => array(
                'type' => 'hidden',
                'label' => "id",
              ),
              "spelled_out" => array(
                'type' => 'text',
                'label' => "Nominal Terbilang",
              ),
            ),
            "title" => "RBA",
            "data_name" => "account_code",
          ),
        );
      
      return $table;
    }

    public function convert2debit( $rows )
    {
      $month_year = ( !empty( $rows ) )? date("M Y", $rows[0]->date ) : '' ;
     
      $list = array();
      foreach( $rows as $row )
      {
          $_item = (object) array(
            'nominal'  => $row->nominal,
            'account_id'  => $row->account_id,
            'serial_number' => '',
            'date'          => "",//date("m/d/Y", $row->date),
            'account_code'  => $row->account_code,
            'description'   => $row->account_description,
            'debit'         => $row->nominal,
            'credit'        => 0,
          );
          if( trim( $row->account_code ) == '7.1.6' )
          {
              $_item->description = "* Pokok"; //id dari penerimaan dana bergulir
          }
          if( trim( $row->account_code ) == '4.1.4' )
          {
            $_item->description = "* Jasa Giro : - ".date("M Y", $row->date );// PAD
          }
          $list []= $_item;
      }
      return array_merge(
        $list
      ); 
    }
    public function convert2kredit( $rows )
    {
      $list = array();
      foreach( $rows as $row )
      {
          $_item = (object) array(
            'nominal'  => $row->nominal,
            'account_id'  => $row->account_id,
            'serial_number' => '',
            'date'          => date("m/d/Y", $row->date),
            'account_code'  => $row->account_code,
            'description'   => $row->account_description,
            'debit'         => 0,
            'credit'        => $row->nominal,
          );
         
          $list []= $_item;
      }
      return $list;
    }

    public function get_sum( $rows, $title = 'Jumlah Bulan Ini' )
    {
      $item = (object) array(
        'nominal'  => '',
        'account_id'  => '',
        'serial_number'  => '',
        'account_code'  => '',
        'description'   => $title,
        'date'          => '',
        'debit'         => 0,
        'credit'        => 0,
      );
        foreach( $rows as $row )
        {
          // $item->account_id = $row->account_id;
          $item->debit += $row->debit;
          $item->credit += $row->credit;
        }
      
      return $item;
    }
    public function get_leftover( $row, $title = 'Sisa' )
    {
      $item = (object) array(
        'nominal'  => '',
        'account_id'  => '',
        'serial_number'  => '',
        'account_code'  => '',
        'description'   => $title,
        'date'          => '',
        'debit'         => 0,
        'credit'        => $row->debit - $row->credit,
      );
      
      return $item;
    }
    public function get_delimiter( 
        $serial_number = '',
        $account_code = "",
        $description = "",
        $date = "",
        $debit = "",
        $credit = ""
      )
    {
      
      $item = (object) array(
        'nominal'  => '',
        'account_id'  => '',
        'serial_number'  => $serial_number,
        'account_code'  => $account_code,
        'description'   => $description,
        'date'          => $date,
        'debit'         => $debit,
        'credit'        => $credit,
      );
      
      return [ $item ];
    }
    public function get_delimiter_debit( $rows, $year, $month )
    {
      $description   = 'Terima Strn Angsuran dari Nasabah Bln '.$this->_month[ $month ]." ".$year;
      return $this->get_delimiter( '', '', $description );
    }

    public function get_footer( $leftover , $_data )
    {
      $year = (int) date('Y', $_data->date );
      $month = (int) date('m', $_data->date );
      $day = (int) date('d', $_data->date );
        $footer = array(  
          (object) array(
            'nominal'  => '',
            'account_id'  => '',
            'serial_number'  => '',
            'description'  => 'Pada Hari ini Tanggal '.$day." ".$this->_month[ $month ]." ".$year,
            'account_code'   => '',
            'date'          => '',
            'debit'         => '',
            'credit'        => '',
          ),
          (object) array(
            'nominal'  => '',
            'account_id'  => '',
            'serial_number'  => '',
            'description'  => 'Buku Kas Umum Di Tutup Dengan Saldo Rp.'.number_format( $_data->closing_balance ) ,
            'account_code'   => '',
            'date'          => '',
            'debit'         => '',
            'credit'        => '',
          ),
          (object) array(
            'nominal'  => '',
            'account_id'  => '',
            'serial_number'  => '',
            'description'  => 'Yang Terdiri Dari : ',
            'account_code'   => '',
            'date'          => '',
            'debit'         => '',
            'credit'        => '',
          ),
          (object) array(
            'nominal'  => '',
            'account_id'  => '',
            'serial_number'  => '',
            'description'  => 'a. Uang Tunai : Rp.'.number_format( $_data->cash ) ,
            'account_code'   => '',
            'date'          => '',
            'debit'         => '',
            'credit'        => '',
          ),
          (object) array(
            'nominal'  => '',
            'account_id'  => '',
            'serial_number'  => '',
            'description'  => 'b. Saldo Bank : Rp.'.number_format( $_data->bank_balance ) ,
            'account_code'   => '',
            'date'          => '',
            'debit'         => '',
            'credit'        => '',
          ),
          (object) array(
            'nominal'  => '',
            'account_id'  => '',
            'serial_number'  => '',
            'description'  => 'c. Lain-lain : Rp.'.number_format( $_data->other ) ,
            'account_code'   => '',
            'date'          => '',
            'debit'         => '',
            'credit'        => '',
          ),
        );
      
      return $footer;
    }
      
}
?>
