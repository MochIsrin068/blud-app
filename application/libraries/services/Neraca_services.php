<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Neraca_services
{
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
        $semester = array(
          1 => " Pertama ",
          2 => " Kedua ",
        );
        $table["header"] = array(
          'year' => 'Tahun Anggaran',
        );
        $table["number"] = $start_number;
        $table[ "action" ] = array(
              array(
                "name" => 'Generate Neraca',
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
                    'label' => "Tanggal Menyetujui",
                    'value' => date('m/d/Y')
                  ),
                  "semester" => array(
                    'type' => 'select',
                    'label' => "Semester",
                    'options' => $semester,
                  ),
                ),
                "title" => "RBA",
                "data_name" => "description",
              ),
      );

      return $table;
    }
    public function view_table_config( $_page, $start_number = 1 )
    {
        $semester = array(
          1 => " Pertama ",
          2 => " Kedua ",
        );
        $table["header"] = array(
          'year' => 'Tahun Anggaran',
        );
        $table["number"] = $start_number;
        $table[ "action" ] = array(
              array(
                "name" => 'Lihat Neraca',
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
                    'type' => 'hidden',
                    'label' => "Tanggal Menyetujui",
                    'value' => date('m/d/Y')
                  ),
                  "semester" => array(
                    'type' => 'select',
                    'label' => "Semester",
                    'options' => $semester,
                  ),
                ),
                "title" => "RBA",
                "data_name" => "description",
              ),
      );

      return $table;
    }
}
?>
