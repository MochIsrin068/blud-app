<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application extends Member_Controller {

    private $services = null;
    private $name = null;
    private $parent_page = 'member';
    private $current_page = 'member/application';
    private $form_data = null;

    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('services/Application_member_services');
        $this->services = new Application_member_services;
        $this->name = $this->services->name;
        $this->form_data = $this->services->form_data();
        $this->load->model(array(
          'm_member_application',
          'm_fund_cluster',
        ));
    }


    public function index(){
        //basic variable
        $key = $this->input->get('key');
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;
        $tabel_cell = ['id','name','date','status','info'];

        $fetch['select'] = ['*'];
        $fetch['select_join'] = 
        [
          'table_party.name',
          'table_party.id as prtId'
        ];

        $fetch['join'] = 
        [
          array('table'=>'table_party','id'=>'party_id','join'=>'left'),
          array('table'=>'table_user_party','id'=>'table_party.id','join'=>'right'),
        ];

        $sessUser = $this->session->userdata('user_id');
        $prtId = $this->m_member_application->getWhere('user_id="'.$sessUser.'"', 'table_user_party');
        foreach($prtId as $idp){
          $fetch['where'] = array("id" => "table_party.id=".$idp->party_id);
        }
        $for_table = $this->m_member_application->fetch($fetch);


        $p['user_id'] = $sessUser;
        $this->load->model('m_fund_applicant');
        $status = $this->m_fund_applicant->getWhere($p);


        //get flashdata
        $alert = $this->session->flashdata('alert');
        $this->data["key"] = ($key!=null) ? $key : false;
        $this->data["alert"] = (isset($alert)) ? $alert : NULL ;
        $this->data["for_table"] = $for_table;
        $this->data["table_header"] = $this->services->tabel_header($tabel_cell);
        $this->data["number"] = 1;
        $this->data["status"] = $status;
        $this->data["current_page"] = $this->current_page;
        $this->data["block_header"] = "Permohonan Management";
        $this->data["header"] = "TABLE PERMOHONAN";
        $this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

        $this->render( "member/application/content");
    }

    public function create( $party_id ){
        
        if( ! $this->m_fund_cluster->is_complete_party( $party_id ) )
        {
          $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, "Anggota kelompok Belum Lengkap" ) );
            // redirect( site_url( $current_page ) ,'refresh');
            redirect($this->current_page);
        }
        if($this->input->post()!=null){
          $this->form_validation->set_rules( $this->services->validation_config());
          if($this->form_validation->run() === TRUE){
              $input_data['party_id'] = $this->input->post('party_id');
              $input_data['date'] = $this->input->post('date');
              $input_data['status'] = 1;
              $input_data['info'] = $this->input->post('info');
              $insert = $this->insert($input_data);
              if($insert){
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Input data berhasil'));
                redirect($this->current_page);
              }else{
                $form = $this->services->form_data(null, null, $party_id);
              }
          }else {
            $alert = $this->errorValidation(validation_errors());
            $this->data['alert'] = $this->alert->set_alert(Alert::WARNING, $alert);
            $form = $this->services->form_data(null, null, $party_id);
          }
        }else{
          $form = $this->services->form_data(null, null, $party_id);
        }

      $this->data['form_data'] = $form;
      $this->data['form_action'] = site_url($this->current_page.'/create/'.$party_id);
      $this->data['name'] = $this->name;
      $this->data['parent_page'] = $this->parent_page.'/cluster';
      $this->data["block_header"] = "Permohonan Management";
      $this->data["header"] = "Tambah Permohonan";
      $this->data["sub_header"] = 'Tekan Tombol Simpan Ketika Selesai Mengisi Form';
      $this->render( "member/application/create");
    }

    public function insert($data) {
        $insert = $this->m_member_application->add($data);
        return $insert;
    }

  
    public function detail($id=null){
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $form_value = $this->m_member_application->getWhere($w);
      if($form_value==false){
        redirect($this->current_page);
      }else{
        $form_value = $form_value[0];
      }

      $fetch['select'] = ['*'];
      $fetch['select_join'] = 
      [
        'table_user_party.status',
        'table_users.first_name',
        'table_users.last_name',
      ];

      $fetch['join'] = [array('table'=>'table_users','id'=>'user_id','join'=>'left'), array('table'=>'table_party','id'=>'party_id','join'=>'left')];
      $fetch['order'] = array("field"=>"id","type"=>"ASC");
      $p['id'] = $id;

      $partyV = $this->m_member_application->getWhere($p);
      foreach($partyV as $idp){
        $idPerty = $idp->party_id;
        $fetch['where'] = array("table_party.id"=> $idPerty);
        $prtID = $idp->party_id;
      }
      $myTable = 'table_user_party';
      $member = $this->m_member_application->fetchMember($fetch, $myTable);

      $param = 'detail';
      $this->data['form_data'] = $this->services->form_data($form_value, $param);
      $this->data['anggota'] = $member;
      $this->data['parent_page'] = $this->current_page;
      $this->data['name'] = $this->name;
      $this->data['detail'] = true;
      $this->data["block_header"] = "Permohonan Management";
      $this->data["header"] = "Detail Permohonan";
      $this->data["sub_header"] = 'Halaman Ini Hanya Berisi Informasi Detail Dari Data';
      $this->render("member/application/detail");
    }

    public function delete() {
      $id = $this->input->post('id');
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $delete = $this->m_member_application->delete($w);
      if($delete!=false){
        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Delete data berhasil'));
        redirect($this->current_page);
      }else{
        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::WARNING, 'Terjadi Kesalahan'));
        redirect($this->current_page);
      }

    }

}
