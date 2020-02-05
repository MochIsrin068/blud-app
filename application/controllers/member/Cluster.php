<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cluster extends Member_Controller{

    private $parent_page = 'member';
    private $current_page = 'member/cluster';
    private $name = null;


    public function __construct(){
        parent::__construct();
        $this->load->library('services/Cluster_services');
        $this->services = new Cluster_services;
        $this->name = $this->services->name;
        $this->form_data = $this->services->form_data();
        $this->load->model(
          array(
            'm_fund_cluster',
            'm_fund_applicant',
            )
        );
    }

    // public function index(){
		//     $this->data['kelompok'] = $this->m_fund_cluster->get_total();
    //     $this->data['page_title'] = "Cluster Change Page"; 
    //     $this->render("member/cluster/change");
    // }

    public function index(){
      $sessUser = $this->session->userdata('user_id');
      $p['user_id'] = $sessUser;
      $this->load->model('m_fund_applicant');
      $status = $this->m_fund_applicant->getWhere($p);

      $id = $status[0]->party_id;

      $party_id = $id;

      $w['id'] = $id;
      $form_value = $this->m_fund_cluster->getWhere($w);
      if($form_value==false){
        redirect($this->current_page);
      }else{
        $form_value = $form_value[0];
      }

      $fetch['select'] = ['*'];
      $fetch['select_join'] = 
      [
        'table_user_party.party_id',
        'table_user_party.status',
      ];

      $fetch['join'] = [array('table'=>'table_user_party','id'=>'user_id','join'=>'left')];
      $fetch['order'] = array("field"=>"table_user_party.status","type"=>"ASC");
      $fetch['where'] = array("table_user_party.party_id"=> $id);
      $myTable = 'table_users';
      $member = $this->m_fund_cluster->fetchMember($fetch, $myTable);
      // echo var_dump( $member );
      // echo var_dump($this->m_fund_cluster->db);
      // return ;
      $ketua_user_id = null;
      if($member){
        $ketua_user_id = $member[0]->id;
      }
      $this->data['party_id'] = $party_id;
      $this->data['user_id'] = $ketua_user_id;
      $this->data['form_data'] = $this->services->form_data($form_value);
      $this->data['parent_page'] = $this->current_page;
      $this->data['anggota'] = $member;
      $this->data['name'] = $this->name;
      $this->data['detail'] = true;
      $this->data["block_header"] = "Kelompok Management";
      $this->data["header"] = "Detail Kelompok";
      $this->data["sub_header"] = 'Halaman Ini Hanya Berisi Informasi Detail Dari Data';
      $this->render("member/cluster/detail");
    }


    public function create_aplicants( $party_id )
    { 
        // echo $party_id;
        $alert = $this->session->flashdata('alert');
        $this->data["alert"] = (isset($alert)) ? $alert : NULL ;
        $cluster = $this->m_fund_cluster->getWhere("id='".$party_id."'");
        $this->data['party_id'] = $party_id;
        $this->data['district'] = $cluster[0]->name;
        $this->data['form_data'] = '';//$form;
        $this->data['form_action'] = site_url($this->current_page.'/create');
        $this->data['name'] = '';// $this->name;
        $this->data['parent_page'] = $this->current_page;
        $this->data["block_header"] = "Kelompok Management";
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

        // Pemohon
        $this->load->library('services/Applicant_services');
        $this->applicant_services = new Applicant_services;
        $form_data = $this->applicant_services->get_form_data();
        $this->data[ "form_add_1" ] = $this->load->view('templates/form/bsb_form', $form_data , TRUE );

        // Document
        $this->load->library('services/Document_applicant_services');
        $this->document_applicant_services = new Document_applicant_services;
        $form_data = $this->document_applicant_services->get_form_data();
        $this->data[ "form_add_2" ] = $this->load->view('templates/form/bsb_form', $form_data , TRUE );

        $this->render( "member/cluster/create");
        
    }

    private function uploads($id, $data){
        $config['upload_path']          = './uploads/dokumen';
        $config['allowed_types']        = 'jpg|png|pdf|doc|docx|jpeg';
        $config['max_size']             = 5024;
        // $config['max_width']            = 1024;
        // $config['max_height']           = 768;
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
        // echo $party_id;
        $party_id = $this->input->post('party_id');
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
          $iscomplete = $this->m_fund_cluster->is_full_party($party_id);

          if($iscomplete){
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, 'Kelompok Full'));
            redirect( site_url( $this->current_page."/create_aplicants/".$party_id ) ,'refresh');                        
          }else{

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
              $input_data['nominal'] = 500000;//$this->input->post('nominal');
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
                    redirect( site_url( $this->current_page) ,'refresh');
                  }
              }

            }else{
              $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, "Terjasi Kesalahan" ) );
              redirect( site_url( $this->current_page."/create_aplicants/".$party_id ) ,'refresh');
            }
          }

        }else{
          $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
          if(  !empty( validation_errors() ) || $this->ion_auth->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );

          redirect( site_url( $this->current_page."/create_aplicants/".$party_id ) ,'refresh');
        }
        
    }

}
