<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Journal_services{
  protected $id;
	protected $account_id;
	protected $nominal;
	protected $date;
	protected $user;
	protected $party;
    function __construct()
    {
      $this->load->model('m_journal');
      $this->id		          = 0;
      $this->account_id	    = 0 ;
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
          'year' => 'Tahun Anggaran',
        );
        $table["number"] = $start_number;
        $table[ "action" ] = array(
          array(
            "name" => "Detail",
            "type" => "link",
            "url" => site_url( $_page."year/"),
            "button_color" => "primary",
            "param" => "year",
          ),
      );
      return $table;
    }

    public function get_journal_table_config_no_action(  )
    {
      $table["header"] = array(
        'date' => 'Tanggal',
        'month' => 'Bulan',
        'account_code' => 'No Rekening',
        'account_description' => 'Keterangan',
        'nominal' => 'Nominal',
        'user' => 'Nama',
        'party' => 'Kelompok',
      );
      return $table;
    }
    public function get_journal_table_config( $_page, $start_number = 1 )
    {
        $table["header"] = array(
          'date' => 'Tanggal',
          'account_code' => 'No Rekening',
          'account_description' => 'Keterangan',
          'nominal' => 'Nominal',
          'user' => 'Nama',
          'party' => 'Kelompok',
        );
        $table["number"] = $start_number;
        $table[ "action" ] = array(
          array(
            "name" => "Pilihan",
            "type" => "button_dropdowns",
            "links" => array(
                // array( 
                //     "name" => "Detail",
                //     'url' => site_url( $_page."detail/"),
                // ),
                array( 
                  "name" => "Edit",
                  'url' => site_url( "finance/dataentry/journal/edit/"),
                ),
            ),
            "param" => "id",
          ),
          array(
            "name" => 'X',
            "type" => "modal_delete",
            "modal_id" => "delete_planing_",
            "url" => site_url( "finance/dataentry/journal/delete/"),
            "button_color" => "danger",
            "param" => "id",
            "form_data" => array(
              "id" => array(
                'type' => 'hidden',
                'label' => "id",
              ),
              "year" => array(
                'type' => 'hidden',
                'label' => "year",
              ),
            ),
            "title" => "Jurnal",
            "data_name" => "account_description",
          ),
        );
      return $table;
    }
    


    public function validation_config( ){
      $config = array(
        array(
          'field' => 'account_id',
           'label' => 'account_id',
           'rules' =>  'trim|required',
        ),
        array(
          'field' => 'nominal',
           'label' => 'nominal',
           'rules' =>  'trim|required',
        ),
        array(
          'field' => 'date',
           'label' => 'Tanggal',
           'rules' =>  'trim|required',
        ),
        array(
          'field' => 'year',
           'label' => 'Tahun',
           'rules' =>  'trim|required',
        ),
        
       
      );
      
      return $config;
    }
    public function form_data( $journal_id = NULL,  $accounts = NULL )
    {
      if( isset( $journal_id )  )
      {
         $journal 				= $this->m_journal->journal( $journal_id )->row();
         $this->id		          = $journal->id;
         $this->account_id	    = $journal->account_id;
         $this->nominal		    = $journal->nominal;
         $this->date	          = $journal->date;
         $this->user	          = $journal->user;
         $this->party	        = $journal->party;
      }

        $this->load->model('m_account');
        if( !isset( $accounts ) )
        {
          $accounts = $this->m_account->get_end_point();          
        }

        $account_select = array();
        foreach( $accounts as $account )
        {
          $account_select[ $account->id ] = $account->name ." (". $account->description .") ";
        }

        $form_data = array(
          "date" => array(
            'type' => 'date',
            'label' => "Tanggal ( Bulan/Tanggal/Tahun )",
            'value' => ( $this->date ) ? date('m/d/Y', $this->date ) : date('m/d/Y')  ,
          ),
          "account_id" => array(
            'type' => 'select_search',
            'label' => "No Rekening",
            'options' => $account_select,
            'selected' => $this->account_id ,
          ),
          "nominal" => array(
            'type' => 'number',
            'label' => "Nominal",
            'value' => $this->nominal,
          ),
          
          "user" => array(
            'type' => 'text',
            'label' => "Nama",
            'value' => $this->user,
          ),
          "party" => array(
            'type' => 'text',
            'label' => "Kelompok",
            'value' => $this->party,
          ),
          "id" => array(
            'type' => 'hidden',
            'label' => "id",
            'value' => $this->id,
          ),
        
        );
        return $form_data;
    }
}
?>
