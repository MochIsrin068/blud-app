<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Admin_Controller {

    private $services = null;
    private $name = null;
    private $parent_page = 'settings';
    private $current_page = 'setting/user/';
    private $form_data = null;

    public function __construct(){
        parent::__construct();
        $this->load->library('services/User_services');
        $this->services = new User_services;
        $this->name = $this->services->name;
        $this->form_data = $this->services->form_data();
        $this->load->model(array('m_user'));
    }

    public function index()
    {
        // 
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;
        //pagination parameter
        $pagination['base_url'] = base_url($this->current_page) .'/index';
        $pagination['total_records'] = $this->ion_auth->record_count() ;
        $pagination['limit_per_page'] = 10;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4;
        //set pagination
        if ($pagination['total_records']>0) $this->data['links'] = $this->setPagination($pagination);
      // table
      $table["header"] = array(
        'username' => 'username',
        'group_name' => 'Group',
        'user_fullname' => 'Nama Lengkap',
        'phone' => 'No Telepon',
        'email' => 'Email',
      );
      $table["number"] = $pagination['start_record'] + 1;
      $table[ "action" ] = array(
        array(
          "name" => "Detail",
          "type" => "link",
          "url" => site_url("setting/user/detail/"),
          "button_color" => "primary",
          "param" => "id",
        ),
        array(
          "name" => "Edit",
          "type" => "link",
          "url" => site_url("setting/user/edit/"),
          "button_color" => "primary",
          "param" => "id",
        ),
        array(
          "name" => 'X',
          "type" => "modal_delete",
          "modal_id" => "delete_category_",
          "url" => site_url("setting/user/delete/"),
          "button_color" => "danger",
          "param" => "id",
          "form_data" => array(
            "id" => array(
              'type' => 'hidden',
              'label' => "id",
            ),
          ),
          "title" => "User",
          "data_name" => "user_fullname",
        ),
      );
      $table[ "rows" ] = $this->ion_auth->users_limit( $pagination['limit_per_page'], $pagination['start_record']  )->result();
      $this->data[ "table" ] = $this->load->view('templates/tables/plain_table', $table, true);
      
      //get flashdata
      $alert = $this->session->flashdata('alert');
      $this->data["key"] = $this->input->get('key', FALSE);
      $this->data["alert"] = (isset($alert)) ? $alert : NULL ;
      $this->data["current_page"] = $this->current_page;
      $this->data["block_header"] = "User Management";
      $this->data["header"] = "TABLE USER";
      $this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
      

      $this->render( "admin/user/content");
    }

    public function create()
    {
        $tables = $this->config->item('tables', 'ion_auth');
        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->form_validation->set_rules( $this->ion_auth->get_validation_config() );
        $this->form_validation->set_rules('phone', "No Telepon", 'trim|required');

        if ( $this->form_validation->run() === TRUE )
        {
          // return;
          $group_id = $this->input->post('group_id');

          $email = $this->input->post('email') ;
          $identity = $email;
          $password = substr( $email, 0, strpos( $identity, "@" ) ) ;
          //$this->input->post('password');


          $additional_data = array(
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            // 'address' => $this->input->post('address')
          );

          // echo var_dump( $password );return;

        }
        if ($this->form_validation->run() === TRUE && ( $user_id =  $this->ion_auth->register($identity, $password, $email,$additional_data, $group_id) ) )
        // if ( FALSE )
        {
            // echo var_dump( $$user_id );return;

            $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->ion_auth->messages() ) );
            // $group = $this->ion_auth->get_users_groups( $user_id )->row();
            redirect( site_url($this->current_page)  );
        }
        else
        {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            if(  !empty( validation_errors() ) || $this->ion_auth->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );

            $this->data['form_action'] = site_url($this->current_page.'/create');
            $this->data['name'] = $this->name;
            $this->data['parent_page'] = $this->current_page;
            $this->data["block_header"] = "User Management";
            $this->data["header"] = "Tambah User";
            $this->data["sub_header"] = 'Tekan Tombol Simpan Ketika Selesai Mengisi Form';

            $form_data = $this->ion_auth->get_form_data();

            $this->data[ "form_add_1" ] = $this->load->view('templates/form/bsb_form', $form_data , TRUE );  
            
            $this->render( "admin/user/create");
        }
    }

    public function edit( $user_id = NULL ) 
    {	
        // echo var_dump( $this->data['menu'] );return;
        if( $user_id == NULL ) redirect(site_url('admin'));  
        $this->form_validation->set_rules( $this->ion_auth->get_validation_config() );
        if ( $this->input->post('password') )
        {
            $this->form_validation->set_rules( 'password',"Kata Sandi",'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]' );            
            $this->form_validation->set_rules( 'password_confirm',"konfirmasi Kata Sandi",'trim|required'); 

        }
        if ( $this->form_validation->run() === TRUE )
        {
            $user_id = $this->input->post('user_id');
      
            $data = array(
              'first_name' => $this->input->post('first_name'),
              'last_name' => $this->input->post('last_name'),
              'email' => $this->input->post('email'),
              'phone' => $this->input->post('phone'),
              'group_id' => $this->input->post('group_id'),
            );
      
            if ( $this->input->post('password') )
            {
              $data['password'] = $this->input->post('password');
            }
            // check to see if we are updating the user
            if ( $this->ion_auth->update( $user_id, $data ) )
            {
              // redirect them back to the admin page if admin, or to the base url if non admin
              $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->ion_auth->messages() ) );
              redirect( site_url($this->current_page)  );
            }
            else
            {
              // redirect them back to the admin page if admin, or to the base url if non admin
              $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->ion_auth->errors() ) );
              redirect( site_url($this->current_page)."edit/".$user_id  );
            }
        }
        else
        {
          $user = $this->ion_auth->user( $user_id )->row();
          
          $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
          if(  !empty( validation_errors() ) || $this->ion_auth->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
          
          // $this->data['form_action'] = site_url($this->current_page.'/edit/'.$id);
          $this->data['name'] = $this->name;
          $this->data['parent_page'] = $this->current_page;
          $this->data["block_header"] = "User Management";
          $this->data["header"] = "Ubah User";
          $this->data["sub_header"] = 'Silahkan ubah data yang ingin anda ganti';

          $form_data = $this->ion_auth->get_form_data( $user->user_id ); 
          $this->data[ "form_1" ] = $this->load->view('templates/form/bsb_form', $form_data , TRUE );  
          $form_password[ 'form_data' ] = array(
              "password" => array(
                'type' => 'password',
                'label' => "Password",
              ),
              "password_confirm" => array(
                'type' => 'password',
                'label' => "Konfirmasi Password",
              ),
          );
          $this->data[ "form_2" ] = $this->load->view('templates/form/bsb_form', $form_password , TRUE );  

          $this->render( "admin/user/edit");
        }

    }
    public function detail($id=null){
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $form_value = $this->m_user->getWhere($w);
      if($form_value==false){
        redirect($this->current_page);
      }else{
        $form_value = $form_value[0];
      }

      $this->data['form_data'] = $this->services->form_data($form_value);
      $this->data['parent_page'] = $this->current_page;
      $this->data['name'] = $this->name;
      $this->data['detail'] = true;
      $this->data["block_header"] = "User Management";
      $this->data["header"] = "Detail User";
      $this->data["sub_header"] = 'Halaman Ini Hanya Berisi Informasi Detail Dari Data';
      $this->render("admin/user/detail");
    }

    public function delete(  ) {
      if( !($_POST) ) redirect( site_url($this->current_page) );

      $id_user = $this->input->post('id');
      if( $this->ion_auth->delete_user( $id_user ) ){
        $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->ion_auth->messages() ) );
      }else{
        $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->ion_auth->errors() ) );
      }
      redirect( site_url($this->current_page) );
    }

}
