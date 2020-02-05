<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant extends Fund_Controller {

    private $services = null;
    private $parent_page = 'fund/';
    private $current_page = 'fund/applicant/';
    private $form_data = null;

    public function __construct(){
        parent::__construct();
        $this->load->library('services/Applicant_services');
        $this->services = new Applicant_services;
        $this->load->model(
          array('m_fund_applicant')
        );
    }

    public function index()
    {
        // 
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;
        //pagination parameter
        $pagination['base_url'] = base_url($this->current_page) .'/index';
        $pagination['total_records'] = $this->ion_auth->members_count() ;
        $pagination['limit_per_page'] = 10;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4;
        //set pagination
        if ($pagination['total_records']>0) $this->data['links'] = $this->setPagination($pagination);
        // table
        $table = $this->services->get_table_config( $this->current_page, $pagination['start_record'] + 1 );
        $rows = $this->ion_auth->member_users_limit( $pagination['limit_per_page'], $pagination['start_record']  )->result();

        // $rows = $this->m_fund_applicant->getUserProfiles();

        $key = $this->input->get('key', FALSE);
        if( $key ) $rows = $this->ion_auth->search_member( $key )->result();

        $table[ "rows" ] = $rows;
        $this->data[ "table" ] = $this->load->view('templates/tables/aplicant_table', $table, true);
        
        //get flashdata
        $alert = $this->session->flashdata('alert');
        $this->data["key"] = $this->input->get('key', FALSE);
        $this->data["alert"] = (isset($alert)) ? $alert : NULL ;
        $this->data["current_page"] = $this->current_page;
        $this->data["block_header"] = "Pemohon";
        $this->data["header"] = "TABLE Pemohon";
        $this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
        

        $this->render( "admin_fund/applicant/content");
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

          $email = $this->input->post('phone') ;
          $identity = $email;
          // $password = substr( $email, 0, strpos( $identity, "@" ) ) ;
          $password =  $this->input->post('phone');

          $additional_data = array(
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'email' => $this->input->post('phone'),
            'phone' => $this->input->post('phone'),
            // 'address' => $this->input->post('address')
          );


        }
        if ($this->form_validation->run() === TRUE && ( $user_id =  $this->ion_auth->register($identity, $password, $email,$additional_data, $group_id) ) )
        {
            // echo var_dump( $$user_id );return;
          $forProfiles = array(
            'surename' => $this->input->post('first_name').' '.$this->input->post('last_name'),
            'birthplace' => $this->input->post('birthplace'),
            'sex' => $this->input->post('sex'),
            'phone' => $this->input->post('phone'),
            'address' => $this->input->post('address'),
            'district' => $this->input->post('district'),
            'identity_card_number' => $this->input->post('identity_card_number'),
            'rt' => $this->input->post('rt'),
            'rw' => $this->input->post('rw'),
            'user_id' => $user_id,
          );

          $insertProfile = $this->insert($forProfiles);
          if($insertProfile){
            $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->ion_auth->messages() ) );
            redirect( site_url($this->current_page)  );
          }else{
            $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, "Terjasi Kesalahan" ) );
            redirect( site_url($this->current_page)  );
          }

        }
        else
        {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            if(  !empty( validation_errors() ) || $this->ion_auth->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );

            $this->data['form_action'] = site_url($this->current_page.'/create');
            $this->data['parent_page'] = $this->current_page;
            $this->data["block_header"] = "Pemohon";
            $this->data["header"] = "Tambah Pemohon";
            $this->data["sub_header"] = 'Tekan Tombol Simpan Ketika Selesai Mengisi Form';

            $form_data = $this->services->get_form_data();

            $this->data[ "form_add_1" ] = $this->load->view('templates/form/bsb_form', $form_data , TRUE );  
            
            $this->render( "admin_fund/applicant/create");
        }
    }

    public function edit( $user_id = NULL )
    {
        if( $user_id == NULL ) redirect( site_url($this->current_page)  );

        $tables = $this->config->item('tables', 'ion_auth');
        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->form_validation->set_rules( $this->ion_auth->get_validation_config() );
        $this->form_validation->set_rules('phone', "No Telepon", 'trim|required');
        $this->form_validation->set_rules('id', "ID", 'trim|required');
        if ( $this->input->post('password') )
        {
            $this->form_validation->set_rules( 'password',"Kata Sandi",'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]' );            
            $this->form_validation->set_rules( 'password_confirm',"konfirmasi Kata Sandi",'trim|required'); 

        }

        if ( $this->form_validation->run() === TRUE )
        {
            $user_id = $this->input->post('id');
      
            $data = array(
              'first_name' => $this->input->post('first_name'),
              'last_name' => $this->input->post('last_name'),
              'email' => $this->input->post('email'),
              'phone' => $this->input->post('phone'),
            );
      
            if ( $this->input->post('password') )
            {
              $data['password'] = $this->input->post('password');
            }
            // echo var_dump( $data );return;
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
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            if(  !empty( validation_errors() ) || $this->ion_auth->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );

            $this->data['form_action'] = site_url($this->current_page.'/create');
            $this->data['parent_page'] = $this->current_page;
            $this->data["block_header"] = "Pemohon";
            $this->data["header"] = "Edit Pemohon";
            $this->data["sub_header"] = 'Tekan Tombol Simpan Ketika Selesai Mengisi Form';

            $form_data = $this->services->get_form_data( $user_id );
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
            $form_data[ 'form_data' ] = array_merge( $form_data[ 'form_data' ] , $form_password[ 'form_data' ] );

            $this->data[ "form_add_1" ] = $this->load->view('templates/form/bsb_form', $form_data , TRUE );  
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
            // $this->data[ "form_2" ] = $this->load->view('templates/form/bsb_form', $form_password , TRUE );  
            
            $this->render( "admin_fund/applicant/create");
        }
    }

    public function detail( $user_id = NULL )
    {
        if( $user_id == NULL ) redirect( site_url($this->current_page)  );

            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            if(  !empty( validation_errors() ) || $this->ion_auth->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );

            $this->data['form_action'] = site_url($this->current_page.'/create');
            $this->data['parent_page'] = $this->current_page;
            $this->data["block_header"] = "Pemohon";
            $this->data["header"] = "Detail Pemohon";
            $this->data["sub_header"] = 'Tekan Tombol Simpan Ketika Selesai Mengisi Form';

            $form_data = $this->services->_view_get_form_data( $user_id );
            // echo 'user_id'.$user_id;
            // return;
            $this->data[ "form_add_1" ] = $this->load->view('templates/form/bsb_form', $form_data , TRUE );  
            
            $this->render( "admin_fund/applicant/detail");
    }

    public function delete(  ) 
    {
        if( !($_POST) ) redirect( site_url($this->current_page) );

        $id_user = $this->input->post('id');

        $this->load->model('m_document_applicant');
        $this->load->model('m_fund_applicant');
        $this->load->model('m_userprofile');
        $this->load->model('m_dept_agreement');
        
        $d['user_id'] = $id_user;

        $dataDoc = $this->m_document_applicant->getWhere($d);
        foreach($dataDoc as $dt){
          unlink('./uploads/dokumen/'.$dt->identity_card);
          unlink('./uploads/dokumen/'.$dt->property_tax);
          unlink('./uploads/dokumen/'.$dt->electricity_bills);
          unlink('./uploads/dokumen/'.$dt->water_bills);
          unlink('./uploads/dokumen/'.$dt->letter_of_recommendation);
        }

        $this->m_dept_agreement->delete($d);
        $this->m_document_applicant->delete($d);

        $d['user_id'] = $id_user;
        $this->m_userprofile->delete($d);
        $this->m_fund_applicant->delete($d);

        if( $this->ion_auth->delete_user( $id_user ) ){
          $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->ion_auth->messages() ) );
        }else{
          $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->ion_auth->errors() ) );
        }
        redirect( site_url($this->current_page) );
    }

    public function insert($data) {
      $insert = $this->m_fund_applicant->addProfiles($data);
      return $insert;
    }


}
