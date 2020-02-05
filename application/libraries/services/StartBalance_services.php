<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class StartBalance_services{
    function __construct(){

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

    public function get_type_table_config( $_page, $start_number = 1 )
    {
        $table["header"] = array(
          'name' => 'Jenis',
        );
        $table["number"] = $start_number;
        $table[ "action" ] = array(
          array(
            "name" => "Isi Saldo",
            "type" => "link",
            "url" => site_url( $_page),
            "button_color" => "primary",
            "param" => "type",
          ),
        );
      return $table;
    }
    public function get_type_rows()
    {
      $rows = array(
          (object) array(
            'type' => "aktiva",
            'name' => "Aktiva",
          ),
          (object) array(
            'type' => "aset_tetap",
            'name' => "Aset Tetap",
          ),
          (object) array(
            'type' => "kewajiban",
            'name' => "Kewajiban",
          ),
          (object) array(
            'type' => "pendapatan",
            'name' => "Pendapatan",
          ),
          (object) array(
            'type' => "belanja",
            'name' => "Belanja",
          ),
      );
      return $rows;
    }

    public function get_balance_table_config_no_action(  )
    {
      $table["header"] = array(
        'account_code' => 'No Rekening',
        'account_description' => 'Keterangan',
        'nominal' => 'Nominal',
        'position' => 'Posisi',
      );
      return $table;
    }
    public function get_balance_table_config( $_page, $start_number = 1, $accounts = NULL, $type = '' )
    {
      if( !isset( $accounts ) )
      {
        $accounts = $this->m_account->get_end_point();          
      }
      $account_select = array();
      foreach( $accounts as $account )
      {
        $account_select[ $account->id ] = $account->name ." (". $account->description .") ";
      }
      $position[ 0 ] = "Debet";
      $position[ 1 ] = "Kredit";

        $table["header"] = array(
          'account_code' => 'No Rekening',
          'account_description' => 'Keterangan',
          'nominal' => 'Nominal',
          'position' => 'Posisi',
        );
        $table["number"] = $start_number;
        $table[ "action" ] = array(
          array(
            "name" => "Edit",
            "type" => "modal_form",
            "modal_id" => "edit_",
            "url" => site_url( $_page."edit/"),
            "button_color" => "primary",
            "param" => "id",
            "title" => "Produk",
            "data_name" => "name",
            "form_data" => array(
              "type" => array(
                'type' => 'hidden',
                'label' => "No Rekening",
                'readonly' => "",
                'value' => $type,
              ),  
              "account_code" => array(
                  'type' => 'text',
                  'label' => "No Rekening",
                  'readonly' => "",
                ),
                "nominal" => array(
                  'type' => 'text',
                  'label' => "nominal",
                ),
                "position" => array(
                  'type' => 'select',
                  'label' => "Posisi",
                  'options' => $position,
                ),
                "year" => array(
                  'type' => 'hidden',
                  'label' => "Tahun",
                  'readonly' => "",
                ),
                "id" => array(
                  'type' => 'hidden',
                  'label' => "id",
                  'readonly' => "",
                ),
            ),
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
                "type" => array(
                  'type' => 'hidden',
                  'label' => "No Rekening",
                  'readonly' => "",
                  'value' => $type,
                ), 
                "year" => array(
                  'type' => 'hidden',
                  'label' => "Tahun",
                  'readonly' => "",
                ),
            ),
            "title" => "RBA",
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
       
      );
      
      return $config;
    }
    public function form_data( $accounts = NULL )
    {
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
      $position[ 0 ] = "Debet";
      $position[ 1 ] = "Kredit";

        $form_data = array(
          "account_id" => array(
            'type' => 'select_search',
            'label' => "No Rekening",
            'options' => $account_select,
          ),
          "nominal" => array(
            'type' => 'number',
            'label' => "Nominal",
          ),
          "position" => array(
            'type' => 'select',
            'label' => "Posisi",
            'options' => $position,
          ),
        );
        return $form_data;
    }

}
?>
