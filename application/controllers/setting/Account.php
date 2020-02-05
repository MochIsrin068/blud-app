<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends Admin_Controller {

    private $services = null;
    private $name = null;
    private $parent_page = 'setting';
    private $current_page = 'setting/account/';
    private $form_data = null;

    public function __construct(){
        parent::__construct();
        $this->load->library('services/Account_services');
        $this->services = new Account_services;
        $this->load->model(array( 'm_account' ));
    }

    public function index()
    {
      $tree = $this->m_account->tree();
      $this->data[ "tree" ] = $tree;
      $this->data[ "accounts" ] = $this->m_account->accounts()->result();
      // echo json_encode( $tree );
      // return;
      $model_form_add = array(
        "name" => "Tambah Rekening",
        "modal_id" => "add_account_",
        "button_color" => "primary",
        "url" => site_url("setting/account/add/"),
        "form_data" => array(
          "code" => array(
            'type' => 'text',
            'label' => "Kode",
          ),
          "name" => array(
            'type' => 'text',
            'label' => "Nama",
          ),
          "description" => array(
            'type' => 'textarea',
            'label' => "Deskripsi",
          ),
          "account_id" => array(
            'type' => 'hidden',
            'label' => " ",
            'value' => isset( $account_id )? $account_id : 0,
          ),
        ),
      );
      $this->data[ "model_form_add" ] = $this->load->view('templates/actions/modal_form', $model_form_add, true ); 
      
      //get flashdata
      $alert = $this->session->flashdata('alert');
      $this->data["key"] = $this->input->get('key', FALSE);
      $this->data["alert"] = (isset($alert)) ? $alert : NULL ;
      $this->data["current_page"] = $this->current_page;
      $this->data["block_header"] = "Rekening";
      $this->data["header"] = "Rekening";
      $this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
      
      $this->render( "admin/account/content");
    }
    public function add(  )
    {
        if( !($_POST) )	redirect( site_url($this->current_page)  );
    
        $this->form_validation->set_rules( $this->services->validation_config() );
        if ($this->form_validation->run() === TRUE )
        {
          $data['code'] 			= $this->input->post('code');
          $data['name'] 			= $this->input->post('name');
          $data['description'] 	= $this->input->post('description');
          $data['account_id'] 	= $this->input->post('account_id');

          if( $this->m_account->create( $data  ) )
          {
              $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->m_account->messages() ) );
          }
          else
          {
              $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->m_account->errors() ) );
          }
          redirect( site_url($this->current_page)  );
        }
        else
        {
          $this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->m_account->errors() : $this->session->flashdata('message')));
          if(  validation_errors() || $this->m_account->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
          
          redirect( site_url($this->current_page)  );
        }
    }

    public function edit(  )
    {
        if( !($_POST) )	redirect( site_url($this->current_page)  );
    
        $this->form_validation->set_rules( $this->services->validation_config() );
        $this->form_validation->set_rules( 'id',"id",'trim|required'); 
        if ($this->form_validation->run() === TRUE )
        {
          $data['code'] 			        = $this->input->post('code');
          $data['name'] 			        = $this->input->post('name');
          $data['description'] 	      = $this->input->post('description');
          // $data['account_id'] 	= $this->input->post('account_id');

          $data_param['id'] 	= $this->input->post('id');

          if( $this->m_account->update( $data, $data_param  ) )
          {
              $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->m_account->messages() ) );
          }
          else
          {
              $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->m_account->errors() ) );
          }
          redirect( site_url($this->current_page)  );
        }
        else
        {
          $this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->m_account->errors() : $this->session->flashdata('message')));
          if(  validation_errors() || $this->m_account->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
          
          redirect( site_url($this->current_page)  );
        }
    }

    public function delete(  ) {
      if( !($_POST) ) redirect( site_url($this->current_page) );
    
      $data_param['id'] 	= $this->input->post('id');
      
      if( $this->m_account->delete( $data_param ) ){
        $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->m_account->messages() ) );
      }else{
        $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->m_account->errors() ) );
      }
      redirect( site_url($this->current_page) );
    }

}
