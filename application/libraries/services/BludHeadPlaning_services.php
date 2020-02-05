<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BludHeadPlaning_services{
  protected $id;
	protected $title;
	protected $description;
  protected $year;
  
    function __construct(){
        $this->load->model('m_plan');
          
        $this->id		          ='';
        $this->title	        ="";
        $this->description	  ="";
        $this->year	          ="";

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
            // array(
            //   "name" => "Detail",
            //   "type" => "link",
            //   "url" => site_url( $_page."detail/"),
            //   "button_color" => "primary",
            //   "param" => "id",
            // ),
        );
      
      return $table;
    }

    public function get_bludHead_table_config( $_page, $start_number = 1 )
    {
        $table["header"] = array(
          'title' => 'Judul',
          'description' => 'Deskripsi',
          'year' => 'Tahun',
        );
        $table["number"] = $start_number;
        $table[ "action" ] = array(
            array(
              "name" => "Detail",
              "type" => "link",
              "url" => site_url( $_page."detail/"),
              "button_color" => "primary",
              "param" => "id",
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
        $this->year	          = $plan->year ;
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
