<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BudgetPlan_services{
	protected $plan_id;
	protected $account_id;
	protected $account_code;
	protected $description;
	protected $unit;
	protected $quantity;
	protected $price;
	protected $budget_plan_id;
    function __construct(){
        $this->load->model('m_budget_plan');
        
        $this->plan_id		        ='';
        $this->account_id	        ="";
        $this->account_code	        ="";
        $this->description	      ="";
        $this->unit		            ="";
        $this->quantity	        	="";
        $this->price		          ="";
        $this->budget_plan_id		  = '';

    }

    public function __get($var)
  	{
  		return get_instance()->$var;
  	}
    public function get_table_config( $_page, $start_number = 1, $url_return, $accounts = NULL )
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
        $table["header"] = array(
          'account_code' => 'Kode Rekening',
          'account_description' => 'Deskripsi',
          'description' => 'Uraian',
          'unit' => 'Satuan',
          'quantity' => 'Volume',
          'price' => 'Harga',
        );
        $table["number"] = $start_number;
        $table[ "action" ] = array(
            // array(
            //   "name" => "Edit",
            //   "type" => "link",
            //   "url" => site_url( $_page."edit/"),
            //   "button_color" => "primary",
            //   "param" => "id",
            // ),
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
                  "account_id" => array(
                    'type' => 'select_search',
                    'label' => "No Rekening",
                    'options' => $account_select,
                  ),
                  "description" => array(
                    'type' => 'text',
                    'label' => "Uraian",
                  ),
                  "unit" => array(
                    'type' => 'text',
                    'label' => "Satuan",
                  ),
                  "quantity" => array(
                    'type' => 'number',
                    'label' => "Volume",
                  ),
                  "price" => array(
                    'type' => 'number',
                    'label' => "Harga",
                  ),
                  "id" => array(
                    'type' => 'hidden',
                    'label' => "id",
                  ),
                  "plan_id" => array(
                    'type' => 'hidden',
                    'label' => "id",
                  ),
                  "url_return" => array(
                    'type' => 'hidden',
                    'label' => "url_return",
                    'value' => $url_return ,
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
                  "plan_id" => array(
                    'type' => 'hidden',
                    'label' => "plan_id",
                  ),
                  "url_return" => array(
                    'type' => 'hidden',
                    'label' => "url_return",
                    'value' => $url_return ,
                  ),
              ),
              "title" => "RBA",
              "data_name" => "description",
            ),
        );
      
      return $table;
    }
    public function get_table_no_action( $_page, $start_number = 1 )
    {
        $table["header"] = array(
          'account_code' => 'Kode Rekening',
          // 'account_description' => 'Deskripsi',          
          'description' => 'Uraian',          
          'unit' => 'Satuan',
          'quantity' => 'Volume',
          'price' => 'Harga',
        );
        $table["number"] = $start_number;
        $table["action"] = NULL;
      
      return $table;
    }

    public function validation_config( ){
      $config = array(
        array(
          'field' => 'account_id[]',
           'label' => 'No Rekening',
           'rules' =>  'trim|required',
        ),
        array(
          'field' => '_description[]',
           'label' => 'Deskripsi_',
           'rules' =>  'trim|required',
        ),
        array(
          'field' => 'unit[]',
           'label' => 'Satuan',
           'rules' =>  'trim|required',
        ),
        array(
          'field' => 'quantity[]',
           'label' =>('Volume'),
           'rules' =>  'trim|required',
        ),
        array(
          'field' => 'price[]',
           'label' =>('Harga'),
           'rules' =>  'trim|required',
        ),
      );
      return $config;
    }
    public function single_validation_config( ){
      $config = array(
        array(
          'field' => 'account_id',
           'label' => 'No Rekening',
           'rules' =>  'trim|required',
        ),
        // array(
        //   'field' => 'description',
        //    'label' => 'Deskripsi_',
        //    'rules' =>  'trim|required',
        // ),
        array(
          'field' => 'unit',
           'label' => 'Satuan',
           'rules' =>  'trim|required',
        ),
        array(
          'field' => 'quantity',
           'label' =>('Volume'),
           'rules' =>  'trim|required',
        ),
        array(
          'field' => 'price',
           'label' =>('Harga'),
           'rules' =>  'trim|required',
        ),
      );
      return $config;
    }

    public function form_data( $budget_plan_id = -1, $accounts = NULL )
    {
      if( $budget_plan_id != -1 )
      {
        $budget_plan 				= $this->m_budget_plan->budget_plan( $budget_plan_id )->row();
        $this->plan_id		        = $budget_plan->plan_id;
        $this->account_id	        = $budget_plan->account_id;
        $this->account_code	        = $budget_plan->account_code;
        $this->description	      = $budget_plan->description;
        $this->unit		            = $budget_plan->unit;
        $this->quantity	        	= $budget_plan->quantity;
        $this->price		          = $budget_plan->price;
        $this->budget_plan_id		  = $budget_plan->id;
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
          "account_id" => array(
            'type' => 'select_search',
            'label' => "No Rekening",
            'options' => $account_select,
          ),
          "description" => array(
            'type' => 'text',
            'label' => "Uraian",
            'value' => $this->description,
          ),
          "unit" => array(
            'type' => 'text',
            'label' => "Satuan",
            'value' => $this->unit,
          ),
          "quantity" => array(
            'type' => 'number',
            'label' => "Volume",
            'value' => $this->quantity,
          ),
          "price" => array(
            'type' => 'number',
            'label' => "Harga",
            'value' => $this->price,
          ),
          "id" => array(
            'type' => 'hidden',
            'label' => "id",
            'value' => $this->budget_plan_id,
          ),
          "plan_id" => array(
            'type' => 'hidden',
            'label' => "id",
            'value' => $this->plan_id,
          ),
        );
        return $form_data;
    }

    public function set_row( 
        $budget_plans, 
        $account_code ='',  
        $description ='' ,
        $unit ='' ,
        $quantity ='' 
    )
    {
        $_item = (object) array(
          'account_code' => $account_code,
          'description' => $description, 
          'unit' => $unit,
          'quantity' => $quantity,
          'price' => 0,
        );
        $list = array();
        foreach( $budget_plans as $budget_plan )
        {
            $_item->price = $budget_plan->price;
            $list [] = $_item;
        }

        return $list;
    }

  public function set_bold( $budget_plans  )
  { 
      $_item = (object) array(
        'account_code' => '',
        'description' => '', 
        'unit' => '',
        'quantity' => '',
        'price' => 0,
      );
      $list = array();
      foreach( $budget_plans as $budget_plan )
      {
          $_item->account_code = '<b>'.$budget_plan->account_code.'<b>';
          $_item->description = '<b>'.$budget_plan->description.'<b>';
          $_item->unit = '<b>'.$budget_plan->unit.'<b>';
          $_item->quantity = '<b>'.$budget_plan->quantity.'<b>';
          $_item->price = '<b>'.number_format( $budget_plan->price ).'<b>';

          $list [] = $_item;
      }

      return $list;
  }

    public function get_delimiter(   )
  {
      $_item = (object) array(
        'account_code' => '',
        'description' => '', 
        'unit' => '',
        'quantity' => '',
        'price' => '',
      );
      

      return [ $_item ];
  }

}
?>
