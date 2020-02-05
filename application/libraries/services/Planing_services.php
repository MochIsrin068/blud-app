<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Planing_services{
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

    public function validation_config( ){
      $config = array(
        array(
          'field' => 'title',
           'label' => 'Judul',
           'rules' =>  'trim|required',
        ),
        array(
          'field' => 'description',
           'label' => 'Deskripsi',
           'rules' =>  'trim|required',
        ),
        array(
          'field' => 'year',
           'label' =>('Tahun'),
           'rules' =>  'trim|required',
        ),
      );
      
      return $config;
    }
    public function get_table_config( $_page, $start_number = 1 )
    {
        $table["header"] = array(
          'title' => 'Judul',
          'description' => 'Deskripsi',
          'year' => 'Tahun',
        );
        $table["number"] = $start_number;
        $table[ "action" ] = array(
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
              ),
              "title" => "RBA",
              "data_name" => "title",
            ),
        );
      
      return $table;
    }
    public function form_data( $plan_id = -1 )
    {
      if( $plan_id != -1 )
      {
        $plan          				= $this->m_plan->plan( $plan_id )->row();
        $this->id		          = $plan->id ;
        $this->title	        = $plan->title ;
        $this->description	  = $plan->description ;
        // $this->status	        = ( $plan->status == -1 )? 99 : 0 ;
        $this->year	          = $plan->year ;
        $this->status	        = $plan->status ;
        $this->note	          = $plan->note ;
      }
        $form_data = array(
          "title" => array(
            'type' => 'text',
            'label' => "Judul",
            'value' => $this->title,
          ),
          "description" => array(
            'type' => 'text',
            'label' => "Deskripsi",
            'value' => $this->description,
          ),
          "year" => array(
            'type' => 'number',
            'label' => "Tahun",
            // 'value' => date('Y'),
            'value' => ( $this->year != '' ) ? $this->year : date('Y'),
          ),
          "status" => array(
            'type' => 'hidden',
            'label' => "status",
            'value' => $this->status,
          ),
          "note" => array(
            'type' => 'hidden',
            'label' => "note",
            'value' => $this->note,            
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
