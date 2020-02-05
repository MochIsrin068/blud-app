<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserParty extends Fund_Controller {

    private $services = null;
    private $name = null;
    private $parent_page = 'fund';
    private $cluster_page = 'fund/cluster';
    private $current_page = 'fund/userParty';
    private $form_data = null;

    public function __construct(){
        parent::__construct();
        $this->load->library('services/UserParty_services');
        $this->services = new UserParty_services;
        $this->name = $this->services->name;
        $this->form_data = $this->services->form_data();
        // $this->load->model(array('m_fund_applicant'));
        $this->load->model(
          array(
            'm_fund_cluster',
            'm_fund_applicant',
            )
        );
    }

    public function create_member( $party_id, $user_id=null ){

        $alert = $this->session->flashdata('alert');
        $this->data["alert"] = (isset($alert)) ? $alert : NULL ;
        $cluster = $this->m_fund_cluster->getWhere("id='".$party_id."'");
        $this->data['party_id'] = $party_id;
        $this->data['ketua_id'] = $user_id;
        $this->data['form_data'] = '';//$form;
        $this->data['form_action'] = site_url($this->current_page.'/create');
        $this->data['name'] = '';// $this->name;
        $this->data['parent_page'] = $this->current_page ;
        // $this->data['current_page'] = $this->current_page ;
        $this->data["block_header"] = "Tambah Anggota";
        $this->data["header"] = "Kelompok";
        $this->data["sub_header"] = 'Tekan Tombol Simpan Ketika Selesai Mengisi Form';

        $form_data["form_data"] = array(
          "name" => array(
            'type' => 'text',
            'label' => "Nama Kelompok",
            'value' => $cluster[0]->name.'-'.$party_id,
          ),
        );
        $form_data["data"] = NULL;
        $this->data[ "form_cluster" ] = $this->load->view('templates/form/bsb_form', $form_data , TRUE );

        // KELOMPOK
        $this->load->library('services/Applicant_services');
        $this->applicant_services = new Applicant_services;
        $form_data = $this->applicant_services->get_form_data();
        $this->data[ "form_add_1" ] = $this->load->view('templates/form/bsb_form', $form_data , TRUE );

        // Document
        $this->load->library('services/Document_applicant_services');
        $this->document_applicant_services = new Document_applicant_services;
        $form_data = $this->document_applicant_services->get_form_data();
        $this->data[ "form_add_2" ] = $this->load->view('templates/form/bsb_form', $form_data , TRUE );

        // return;
        $this->render( "admin_fund/cluster/createAnggota");

        // echo $party_id;
        // echo $user_id;
    }

    private function uploads($id, $data){
        $config['upload_path']          = './uploads/dokumen';
        $config['allowed_types']        = 'jpg|png|pdf|doc|docx|jpeg';
        $config['max_size']             = 5024;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        $config['file_name'] = $data.'-'.$id;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($data))
        {
            $this->$data = 'nothing' ;  
        }
        else
        {
            $myData = $this->upload->data();
            $this->$data = $myData['file_name'];
        }
    }

    public function create_aplicantly(  ) // ketua
    { 
        $party_id = $this->input->post('party_id');
        $status =  $this->input->post('group4');
        $ketua_id =  $this->input->post('ketua_id');
        if( $this->m_fund_applicant->is_position_exist( $party_id,  $status )  )
        {
          $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, "Posisi Sudah Ada!" ) );
            redirect( site_url( $this->current_page."/create_member/".$party_id."/".$ketua_id ) ,'refresh');
        }
        if( $this->m_fund_applicant->is_full( $party_id )  )
        {
          $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, "Anggota Full" ) );
            redirect( site_url( $this->current_page."/create_member/".$party_id."/".$ketua_id ) ,'refresh');
        }
        // return;
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

          $insertProfile =  $this->m_fund_applicant->addProfiles($forProfiles);
          if($insertProfile){
            $this->uploads($user_id, 'identity_card');//REQUIRE
            $this->uploads($user_id, 'property_tax');
            $this->uploads($user_id, 'electricity_bills');
            $this->uploads($user_id, 'water_bills');
            $this->uploads($user_id, 'letter_of_recommendation');

            $this->load->model('m_document_applicant');
            $w['user_id'] = $user_id;
            $form_value = $this->m_document_applicant->getWhere($w);

            $input_data['user_id'] = $user_id;
            $input_data['date'] = date('Y-m-d');
            $input_data['nominal'] = $this->input->post('nominal');
            $input_data['status'] = 1;
            $input_data['identity_card'] = $this->identity_card;
            $input_data['property_tax'] = $this->property_tax;
            $input_data['electricity_bills'] = $this->electricity_bills;
            $input_data['water_bills'] = $this->water_bills;
            $input_data['letter_of_recommendation'] = $this->letter_of_recommendation;

            if(!empty($form_value)){

                $update_data['user_id'] = $user_id;

                if($this->input->post('date')){
                    $update_data['date'] = $this->input->post('date');
                }

                if($this->input->post('nominal')){
                    $update_data['nominal'] = $this->input->post('nominal');
                }

                if($this->input->post('status')){
                    $update_data['status'] = $this->input->post('status');
                }

                if($this->identity_card != 'nothing'){
                    $update_data['identity_card'] =  $this->identity_card;
                }

                if($this->property_tax  != 'nothing'){
                    $update_data['property_tax'] = $this->property_tax;
                }

                if($this->electricity_bills  != 'nothing'){
                    $update_data['electricity_bills'] = $this->electricity_bills;
                }

                if($this->water_bills  != 'nothing'){
                    $update_data['water_bills'] = $this->water_bills;
                }

                if($this->letter_of_recommendation  != 'nothing'){
                    $update_data['letter_of_recommendation'] = $this->letter_of_recommendation;
                }

                $update = $this->update($id, $update_data);
            
                
            }else{
                $insertDoc = $this->m_document_applicant->add($input_data);
                if($insertDoc!=false){
                  $_data['party_id'] = $party_id;
                  $_data['user_id'] = $user_id;
                  $_data['status'] = $this->input->post('group4');

                  $cluster = $this->m_fund_cluster->getWhere("id='".$party_id."'");
                  $party_data['name'] = $cluster[0]->name;

                  $this->m_fund_cluster->update($party_id,  $party_data );

                  $this->m_fund_applicant->add( $_data );
                  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->ion_auth->messages() ) );
                  redirect( site_url( $this->current_page."/detail/".$party_id ) ,'refresh');
                }
            }

            
          }else{
            $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, "Terjasi Kesalahan" ) );
            redirect( site_url( $this->current_page."/create_member/".$party_id."/".$ketua_id ) ,'refresh');
          }

        }else{
          $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
          if(  !empty( validation_errors() ) || $this->ion_auth->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );

          redirect( site_url( $this->current_page."/create_member/".$party_id."/".$ketua_id ) ,'refresh');
        }
        
    }


    public function index(){
        redirect('fund/cluster');
    }

    public function create($id=null){
      if($id==null){
        redirect($this->current_page);
      }

      if($this->input->post()!=null){
        // $this->form_validation->set_rules($this->services->validation_config());
        // if($this->form_validation->run() === TRUE){
            $input_data['status'] = $this->input->post('group4');
            $input_data['user_id'] = $this->input->post('user_id');
            $input_data['party_id'] = $id;
            $update = $this->insert($input_data);
            if($update){
              $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Tambah data berhasil'));
              redirect($this->current_page);
            }else{
              $form = $this->services->form_data();
            }
        // }else{
        //   $alert = $this->errorValidation(validation_errors());
        //   $this->data['alert'] = $this->alert->set_alert(Alert::WARNING, $alert);
        //   $form = $this->services->form_data();
        // }
      }else{
        $form = $this->services->form_data();
      }
      $this->data['form_data'] = $form;
      $this->data['form_action'] = site_url($this->current_page.'/create/'.$id);
      $this->data['name'] = $this->name;
      $this->data['parent_page'] = $this->current_page;
      $this->data["block_header"] = "Anggota Management";
      $this->data["header"] = "Tambah Anggota";
      $this->data["sub_header"] = 'Silahkan ubah data yang ingin anda ganti';
      $this->render( "admin_fund/member/create");
    }


    public function insert($data) {
      $insert = $this->m_fund_applicant->add($data);
      return $insert;
    }

}
