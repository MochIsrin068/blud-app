<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class LPSAL_services{
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

    public function year_table_config( $_page, $start_number = 1 )
    {
        $table["header"] = array(
          'year' => 'Tahun Anggaran',
        );
        $table["number"] = $start_number;
        $table[ "action" ] = array(
            array(
              "name" => 'Generate LPSAL',
              "type" => "modal_form",
              "modal_id" => "verification_",
              "url" => site_url( $_page."generate/"),
              "button_color" => "primary",
              "param" => "id",
              "form_data" => array(
                "id" => array(
                  'type' => 'hidden',
                  'label' => "id",
                ),
                "date" => array(
                  'type' => 'date',
                  'label' => "Sampai Bulan",
                  'value' => date('m/d/Y')
                )
              ),
              "title" => "RBA",
              "data_name" => "description",
            ),
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
            "name" => 'Lihat LPSAL',
            "type" => "modal_form",
            "modal_id" => "verification_",
            "url" => site_url( $_page."generate/"),
            "button_color" => "primary",
            "param" => "id",
            "form_data" => array(
              "id" => array(
                'type' => 'hidden',
                'label' => "id",
              ),
              "date" => array(
                'type' => 'date',
                'label' => "Sampai Bulan",
                'value' => date('m/d/Y')
              )
            ),
            "title" => "RBA",
            "data_name" => "description",
          ),
    );

      return $table;
    }
}
?>
