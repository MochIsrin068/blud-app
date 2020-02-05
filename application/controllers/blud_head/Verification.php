<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verification extends BludHead_Controller {

    private $services = null;
    private $name = null;
    private $parent_page = 'blud_head';
    private $current_page = 'blud_head/verification';
    private $form_data = null;

    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('services/Verification_services');
        $this->services = new Verification_services;
        $this->name = $this->services->name;
        $this->form_data = $this->services->form_data();
        $this->load->model(array('m_verification'));
    }


    public function index(){
        //basic variable
        $key = $this->input->get('key');
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;
        $tabel_cell = ['id','name','date','status','info'];
        //pagination parameter
        $pagination['base_url'] = base_url($this->current_page) .'/index';
        $pagination['total_records'] = (isset($key)) ? $this->m_verification->search_count($key, $this->name) : $this->m_verification->get_total();
        $pagination['limit_per_page'] = 10;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4;
        //set pagination
        if ($pagination['total_records']>0) $this->data['links'] = $this->setPagination($pagination);


        //fetch data from database
        $fetch['select'] = ['*'];
        $fetch['select_join'] = 
        [
          'table_party.name',
          'table_party.id as idParty'
        ];

        $fetch['join'] = [array('table'=>'table_party','id'=>'party_id','join'=>'left')];
        $fetch['start'] = $pagination['start_record'];
        $fetch['limit'] = $pagination['limit_per_page'];
        $fetch['like'] = ($key!=null) ? array("name" => $this->name, "key" => $key) : null;
        $fetch['order'] = array("field"=>"id","type"=>"ASC");
        $for_table = $this->m_verification->fetch($fetch);

        //get flashdata
        $alert = $this->session->flashdata('alert');
        $this->data["key"] = ($key!=null) ? $key : false;
        $this->data["alert"] = (isset($alert)) ? $alert : NULL ;
        $this->data["for_table"] = $for_table;
        $this->data["table_header"] = $this->services->tabel_header($tabel_cell);
        $this->data["number"] = $pagination['start_record'];
        $this->data["current_page"] = $this->current_page;
        $this->data["block_header"] = "Kelayakan Permohonan";
        $this->data["header"] = "TABLE PERMOHONAN";
        $this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

        $this->render( "blud_head/verification/content");
    }


    public function detail($id=null){
      $memberId = $this->input->post('idMember');
      if($memberId !== false){
        $fetch3['select'] = ['*'];
        $fetch3['select_join'] = 
        [
          'table_users.first_name',
          'table_users.last_name',
          'table_users.phone',
          'table_users.username',
        ];
        $fetch3['join'] = [array('table'=>'table_users','id'=>'user_id','join'=>'left')];
        $fetch3['order'] = array("field"=>"id","type"=>"ASC");
        $fetch3['where'] = array("table_user_party.id"=> $memberId);
        $tbl = 'table_user_party';
        $members = $this->m_verification->fetchMember($fetch3, $tbl);

        $tblDocument = 'table_document';
        $usersId = $this->input->post('idUser');
        $sd['user_id'] = $usersId;
        $memberDocument = $this->m_verification->getDocument($sd, $tblDocument);
      }

      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $form_value = $this->m_verification->getWhere($w);
      if($form_value==false){
        redirect($this->current_page);
      }else{
        $form_value = $form_value[0];
      }

      $fetch['select'] = ['*'];
      $fetch['select_join'] = 
      [
        'table_users.first_name',
        'table_users.last_name',
      ];

      $fetch['join'] = [array('table'=>'table_users','id'=>'user_id','join'=>'left'), array('table'=>'table_party','id'=>'party_id','join'=>'left')];
      $fetch['order'] = array("field"=>"id","type"=>"ASC");
      $p['id'] = $id;
      $partyV = $this->m_verification->getWhere($p);
      foreach($partyV as $pid){
        $fetch['where'] = array("table_party.id"=> $pid->party_id);
      }
      $myTable = 'table_user_party';
      $member = $this->m_verification->fetchMember($fetch, $myTable);

      $this->load->model('m_userprofiles');

      $userProfile['user_id'] = $usersId; 
      $alamat = $this->m_userprofiles->getWhere($userProfile);
      $almt = null;
      foreach($alamat as $alm){
        $almt = $alm->address;
      }

      $memberNominal = null;
      if(!empty($memberDocument)){
        $memberNominal = $memberDocument[0]->nominal;
      }

      $param = 'detail';
      $this->data['form_data'] = $this->services->form_data($form_value, $param);
      $this->data['anggota'] = $member;
      $this->data['parent_page'] = $this->current_page;
      $this->data['reqId'] = $id;
      $this->data['memberDetail'] = $members;
      $this->data['memberDocument'] = $memberDocument;
      $this->data['alamat'] = $almt;
      $this->data['nominal'] = $memberNominal;
      $this->data['name'] = $this->name;
      $this->data['myid'] = $id;
      $this->data['detail'] = true;
      $this->data["block_header"] = "Kelayakan Permohonan";
      $this->data["header"] = "Detail Permohonan";
      $this->data["sub_header"] = 'Halaman Ini Hanya Berisi Informasi Detail Dari Data';
      $this->render("blud_head/verification/detail");
    }

    public function edit(){
      $id = $this->input->post('id');
      $input_data['info'] = $this->input->post('info');
      $input_data['status'] = 0;
      $update = $this->update($id, $input_data);
      if($update){
        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Update data berhasil'));
        redirect($this->current_page);
      }else{
        $form = $this->services->form_data();
        redirect($this->current_page);
      }
    }

    // public function tolak(){
    //   $insert = $this->m_verification->tolak($id, $data);
    //   return $insert;
    // }

    public function approve(){
      $id = $this->input->post('id');
      $input_data['status'] = 3;
      $update = $this->update($id, $input_data);
      if($update){
        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Update data berhasil'));
        redirect($this->current_page);
      }else{
        $form = $this->services->form_data();
        redirect($this->current_page);
      }
    }

    public function update($id, $data) {
      $insert = $this->m_verification->update($id, $data);
      return $insert;
    }
}
