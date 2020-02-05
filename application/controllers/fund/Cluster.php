<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cluster extends Fund_Controller{

    private $services = null;
    private $name = null;
    private $parent_page = 'fund';
    private $current_page = 'fund/cluster';
    private $current_page2 = 'fund/userParty';
    private $form_data = null;

    // Handle Dat Upload
    private $identity_card = null;
    private $property_tax = null;
    private $electricity_bills = null;
    private $water_bills = null;
    private $letter_of_recommendation = null;

    public function __construct(){
        parent::__construct();
        $this->load->library('services/Cluster_services');
        $this->services = new Cluster_services;
        $this->name = $this->services->name;
      //  $this->form_data = $this->services->form_data();
        $this->load->model(
          array(
            'm_fund_cluster',
            'm_fund_applicant',
            'm_dept_agreement',
            )
        );
    }


    public function index(){
        //basic variable
        $key = $this->input->get('key');
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;

        $tabel_cell = ['id','name'];
        $tabel_cell_select = ['id','name'];
        //pagination parameter
        $pagination['base_url'] = base_url($this->current_page) .'/index';
        $pagination['total_records'] = (isset($key)) ? $this->m_fund_cluster->search_count($key, $this->name) : $this->m_fund_cluster->get_total();
        $pagination['limit_per_page'] = 10;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4;
        //set pagination
        if ($pagination['total_records']>0) $this->data['links'] = $this->setPagination($pagination);


        //fetch data from database
        $fetch['select'] = ['*'];
        $fetch['start'] = $pagination['start_record'];
        $fetch['limit'] = $pagination['limit_per_page'];
        $fetch['like'] = ($key!=null) ? array("name" => $this->name, "key" => $key) : null;
        $fetch['order'] = array("field"=>"id","type"=>"DESC");
        $for_table = $this->m_fund_cluster->fetch($fetch);


        //get flashdata
        $alert = $this->session->flashdata('alert');
        $this->data["key"] = ($key!=null) ? $key : false;
        $this->data["alert"] = (isset($alert)) ? $alert : NULL ;
        $this->data["for_table"] = $for_table;

        // $this->data["table"] = $this->load->view('templates/tables/cluster_table', $for_table, true);

        $this->data["table_header"] = $this->services->tabel_header($tabel_cell);
        $this->data["number"] = $pagination['start_record'];
        $this->data["current_page"] = $this->current_page;
        $this->data["current_page2"] = $this->current_page2;
        $this->data["block_header"] = "Kelompok Management";
        $this->data["header"] = "TABLE KELOMPOK";
        $this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $array_kecamatan = array(
          "kendari" => "kendari",
          "Kendari Barat" => "Kendari Barat",
          "Poasia" => "Poasia",
          "Abeli" => "Abeli",
          "Kambu" => "Kambu",
          "Mandonga" => "Mandonga",
          "Wua-wua" => "Wua-wua",
          "Puuwatu" => "Puuwatu",
          "Kadia" => "Kadia",
          "Baruga" => "Baruga",
        );
        $model_form_add = array(
          "name" => "Tambah",
          "modal_id" => "add_party_",
          "button_color" => "primary",
          "url" => site_url( $this->current_page."/create_party/" ),
          "form_data" => array(
            "name" => array(
              'type' => 'select_search',
              'label' => "Kecamatan",
              'options' => $array_kecamatan,
            ),
          ),
          'data'=> NULL,
        );
        $this->data[ "model_form_add" ] = $this->load->view('templates/actions/modal_form', $model_form_add, true );
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // return;


        $this->render( "admin_fund/cluster/content");
    }

    public function create_party()
    {
        // $date = date('is');
        $name = $this->input->post('name');
        // $data['name'] = $name.'-'.$date;
        $data['name'] = $name;
        $id = $this->m_fund_cluster->add( $data );

        redirect( site_url( $this->current_page."/create_aplicants/".$id ) ,'refresh');

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

        $this->render( "admin_fund/cluster/create");

    }

    private function uploads($id, $data){
        $config['upload_path']          = './uploads/dokumen';
        $config['allowed_types']        = 'JPG|jpg|png|pdf|doc|docx|jpeg';
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


            $this->uploads($user_id, 'identity_card');//REQUIRE
            $this->uploads($user_id, 'property_tax');
            $this->uploads($user_id, 'electricity_bills');
            $this->uploads($user_id, 'water_bills');
            $this->uploads($user_id, 'letter_of_recommendation');

            $this->load->model('m_document_applicant');
            $w['user_id'] = $user_id;
            $form_value = $this->m_document_applicant->getWhere($w);

            $manipulate = 'nothing';
            // && $this->property_tax !== $manipulate || $this->identity_card !== $manipulate || $this->property_tax !== $manipulate || $this->electricity_bills !== $manipulate || $this->water_bills !== $manipulate
            if( ($this->identity_card !== $manipulate && $this->property_tax !== $manipulate) && ($this->electricity_bills !== $manipulate || $this->water_bills !== $manipulate) ){

              $memberStatus =  $this->input->post('group4');
              $s['status']  = $memberStatus;
              $s['party_id']  = $party_id;
              $getStatus = $this->m_fund_applicant->getWhere($s);

              if(($memberStatus == 1 && !empty($getStatus)) || ($memberStatus == 3 && !empty($getStatus)) || ($memberStatus == 4 && !empty($getStatus))){
                $alert = $getStatus[0]->status.' Sudah Ada!';
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::WARNING, $alert));
                redirect( site_url( $this->current_page."/create_aplicants/".$party_id ) ,'refresh');

              }else if(($memberStatus == 2 && !empty($getStatus)) && (count($getStatus) == 2) ){
                $alert = 'Anggota Kelompok Tidak Boleh Melebihi Dua!';
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::WARNING, $alert));
                redirect( site_url( $this->current_page."/create_aplicants/".$party_id ) ,'refresh');
              }else{
                $insertProfile =  $this->m_fund_applicant->addProfiles($forProfiles);
                if($insertProfile){

                  $input_data['user_id'] = $user_id;
                  $input_data['date'] = date('Y-m-d');
                  $input_data['nominal'] = 500000;
                  $input_data['status'] = 1;
                  $input_data['identity_card'] = $this->identity_card;
                  $input_data['property_tax'] = $this->property_tax;
                  $input_data['electricity_bills'] = $this->electricity_bills;
                  $input_data['water_bills'] = $this->water_bills;
                  $input_data['letter_of_recommendation'] = $this->letter_of_recommendation;

                  $insertDoc = $this->m_document_applicant->add($input_data);

                  $input_agreement['user_id'] = $user_id;
                  $input_agreement['nominal'] = 500000;

                  if($insertDoc!=false){
                    $this->m_dept_agreement->add($input_agreement);

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

                }else{
                  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, "Terjasi Kesalahan" ) );
                  redirect( site_url( $this->current_page."/create_aplicants/".$party_id ) ,'refresh');
                }
              }

            }else{
                $this->ion_auth->delete_user( $user_id );
                $alert = 'Document Belum Lengkap, Silahkan Upload Ulang';
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::WARNING, $alert));
                redirect( site_url( $this->current_page."/create_aplicants/".$party_id ) ,'refresh');
            }
          }

        }else{

          $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
          if(  !empty( validation_errors() ) || $this->ion_auth->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );

          redirect( site_url( $this->current_page."/create_aplicants/".$party_id ) ,'refresh');
        }

    }

    public function create(){
      if($this->input->post()!=null){
        $this->form_validation->set_rules($this->services->validation_config());
        if($this->form_validation->run() === TRUE){
            $input_data['id'] = $this->input->post('id');
            $clusterName = $this->input->post('name');
            $input_data['name'] = $clusterName;
            $insert = $this->insert($input_data);

            if($insert){
              $getIdCluster = $this->m_fund_cluster->getWhere("name='".$clusterName."'");
              if($getIdCluster!==false){
                foreach($getIdCluster as $vCluster){
                  $input_data2['party_id'] = $vCluster->id;
                  $input_data2['status'] = 1;
                  $input_data2['user_id'] =$this->input->post('user_id');

                  $this->insert2($input_data2);
                  $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Input data berhasil'));
                  redirect($this->current_page);
                }
              }
            }else{
              redirect($this->current_page);
            }

        }else {
          $alert = $this->errorValidation(validation_errors());
          $this->data['alert'] = $this->alert->set_alert(Alert::WARNING, $alert);
          $form = $this->services->form_data();
        }
      }else{
        $form = $this->form_data;
      }
      $this->load->library('services/Applicant_services');
      $this->aplicant_services = new Applicant_services;
      $form_data = $this->aplicant_services->get_form_data();
      $this->data[ "form_add_1" ] = $this->load->view('templates/form/bsb_form', $form_data , TRUE );

      $this->data['form_data'] = $form;
      $this->data['form_action'] = site_url($this->current_page.'/create');
      $this->data['name'] = $this->name;
      $this->data['parent_page'] = $this->current_page;
      $this->data["block_header"] = "Kelompok Management";
      $this->data["header"] = "Tambah Kelompok";
      $this->data["sub_header"] = 'Tekan Tombol Simpan Ketika Selesai Mengisi Form';
      $this->render( "admin_fund/cluster/create");
    }


    public function insert($data) {
      $insert = $this->m_fund_cluster->add($data);
      return $insert;
    }

    public function insert2($data) {
      $insert = $this->m_fund_applicant->add($data);
      return $insert;
    }

    public function edit($id=null){
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $form_value = $this->m_fund_cluster->getWhere($w);
      if($form_value==false){
        redirect($this->current_page);
      }else{
        $form_value = $form_value[0];
      }

      if($this->input->post()!=null){
        $this->form_validation->set_rules($this->services->validation_config());
        if($this->form_validation->run() === TRUE){
            $input_data['name'] = $this->input->post('name');
            $input_data['id'] = $this->input->post('user_id');
            $update = $this->update($id, $input_data);
            if($update){
              $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Update data berhasil'));
              redirect($this->current_page);
            }else{
              $form = $this->services->form_data();
            }
        }else{
          $alert = $this->errorValidation(validation_errors());
          $this->data['alert'] = $this->alert->set_alert(Alert::WARNING, $alert);
          $form = $this->services->form_data();
        }
      }else{
        $form = $this->services->form_data($form_value);
      }

      $fetch['select'] = ['*'];
      $fetch['select_join'] =
      [
        'table_user_party.party_id',
        'table_user_party.status',
      ];

      $fetch['join'] = [array('table'=>'table_user_party','id'=>'user_id','join'=>'left')];
      $fetch['order'] = array("field"=>"id","type"=>"ASC");
      $fetch['where'] = array("table_user_party.party_id"=> $id);
      $myTable = 'table_users';
      $member = $this->m_fund_cluster->fetchMember($fetch, $myTable);

      $this->data['form_data'] = $form;
      $this->data['partyId'] = $id;
      $this->data['anggota'] = $member;
      $this->data['form_action'] = site_url($this->current_page.'/edit/'.$id);
      $this->data['name'] = $this->name;
      $this->data['parent_page'] = $this->current_page;
      $this->data["block_header"] = "Kelompok Management";
      $this->data["header"] = "Ubah Kelompok";
      $this->data["sub_header"] = 'Silahkan ubah data yang ingin anda ganti';
      $this->render( "admin_fund/cluster/edit");
    }

    public function update($id, $data) {
      $insert = $this->m_fund_cluster->update($id, $data);
      return $insert;
    }


    public function detail( $id=null){
      $party_id = $id;
      if($id==null){
        redirect($this->current_page);
      }
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
      $this->render("admin_fund/cluster/detail");
    }

    public function delete() {
      $id = $this->input->post('id');
      if($id==null){
        redirect($this->current_page);
      }

      $this->load->model('m_fund_applicant');
      $du['party_id'] = $id;
      $deleteUser = $this->m_fund_applicant->delete($du);

      $this->load->model('m_fund_application');
      $pid['party_id'] = $id;
      $this->m_fund_application->delete($pid);

      $w['id'] = $id;
      $delete = $this->m_fund_cluster->delete($w);

      if($delete!=false){
        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Delete data berhasil'));
        redirect($this->current_page);
      }else{
        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::WARNING, 'Terjadi Kesalahan'));
        redirect($this->current_page);
      }

    }

    public function deleteMembers($id) {
      if($id==null){
        redirect($this->current_page);
      }

      $partyId = $this->input->get('partyId', false);

      $this->load->model('m_fund_applicant');
      $du['user_id'] = $id;
      $deleteUser = $this->m_fund_applicant->delete($du);

      if($deleteUser){
        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Delete data berhasil'));
        redirect('fund/cluster/edit/'.$partyId);
      }else{
        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::WARNING, 'Terjadi Kesalahan'));
        redirect('fund/cluster/edit/'.$partyId);
      }

    }

    public function getKecamatan(){
      $get = $this->input->get('kab');
      $kab = isset($get) ? $get : "";
      if($kab!=""){
        $lead = $this->m_fund_cluster->getLead2($kab);
        $date = date('is');
        echo '<input type="hidden"  name="name" value="'.$kab.'-'.$date.'"/>';
        echo '<select name="user_id" id="user_id" class="form-control">';
        if(!empty($lead)){
          foreach($lead as $l)
          {
            echo '<option value="'.$l->user_id.'">'.$l->surename.'</option>';
          }
        }else{
          echo '<option value="0">Tidak Ada</option>';
        }
        echo '</select>';
      }
    }

}
