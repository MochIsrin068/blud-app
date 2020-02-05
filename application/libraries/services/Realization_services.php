<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Realization_services{
  protected $id;
	protected $title;
	protected $description;
  protected $year;
  protected $status;
  protected $note;
  
    function __construct(){
        $this->load->model('m_plan');
          
        $this->id		          ='';
        $this->title	        ="";
        $this->description	  ="";
        $this->year	          ="";
        $this->status	        = -2 ;
        $this->note	          ="";

    }

    public function __get($var)
  	{
  		return get_instance()->$var;
  	}

    public function get_table_config( $_page, $start_number = 1 )
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
              "url" => site_url( $_page."detail_realization/"),
              // "url" => site_url( $_page."detail_budget_realization/"),
              "button_color" => "primary",
              "param" => "id",
            ),
        );
      
      return $table;
    }

    public function get_montly_table_config( $_page, $realization_id )
    {
        $table["header"] = array(
          'month' => 'Bulan',
        );
        $table[ "action" ] = array(
            array(
              "name" => "Detail",
              "type" => "link",
              // "url" => site_url( $_page."detail_budget_realization/"  ),
              "url" => site_url( $_page."detail_budget_realization/".$realization_id."/"  ),
              "button_color" => "primary",
              "param" => "month",
            ),
        );
      
      return $table;
    }

}
?>
