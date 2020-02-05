<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Report_6_services{
    function __construct(  ){
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
        $table["range"] = array(
            array(
                  "name" => 'Hari / Tanggal',
                  "type" => "modal_form",
                  "modal_id" => "verification_",
                  "url" => site_url( $_page."date/"),
                  "button_color" => "primary",
                  "param" => "id",
                  "form_data" => array(
                    "id" => array(
                      'type' => 'hidden',
                      'label' => "id",
                    ),
                    "date_start" => array(
                      'type' => 'date',
                      'label' => "Tanggal Mulai",
                      'value' => date('m/d/Y'),
                      'name' => "tgl_awal"
                    ),
                    "date_end" => array(
                      'type' => 'date',
                      'label' => "Tanggal Akhir",
                      'value' => date('m/d/Y'),
                      'name' => "tgl_akhir"
                    ),
                  ),
                  "title" => "RBA",
                  "data_name" => "description",
              ),
        );
        $table[ "action" ] = array(
          array(
            "name" => "Pilihan",
            "type" => "button_dropdowns",
            "links" => array(
                array( 
                    "name" => "Bulanan",
                    'url' => site_url( $_page."month/"),
                ),
                array( 
                  "name" => "Semester",
                  'url' => site_url( $_page."semester/"),
                ),
            ),
            "param" => "year",
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
