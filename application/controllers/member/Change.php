<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Change extends Member_Controller{
    private $services = null;
    private $name = null;
    private $parent_page = 'member';
    private $current_page = 'member/change';
    private $form_data = null;

    public function __construct(){
        parent::__construct();
        $this->load->library('services/Cluster_member_services');
        $this->services = new Cluster_member_services;
        $this->name = $this->services->name;
        $this->form_data = $this->services->form_data();
        $this->load->model(array('m_member_cluster', 'm_fund_applicant'));
    }


    public function index(){
        //basic variable
        $key = $this->input->get('key');
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;
        $tabel_cell = ['id','name'];
        //pagination parameter
        $pagination['base_url'] = base_url($this->current_page) .'/index';
        $pagination['total_records'] = (isset($key)) ? $this->m_member_cluster->search_count($key, $this->name) : $this->m_member_cluster->get_total();
        $pagination['limit_per_page'] = 10;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4;
        //set pagination
        if ($pagination['total_records']>0) $this->data['links'] = $this->setPagination($pagination);


        //fetch data from database
        $fetch['select'] = $tabel_cell;
        $fetch['start'] = $pagination['start_record'];
        $fetch['limit'] = $pagination['limit_per_page'];
        $fetch['like'] = ($key!=null) ? array("name" => $this->name, "key" => $key) : null;
        $fetch['order'] = array("field"=>"id","type"=>"ASC");
        $for_table = $this->m_member_cluster->fetch($fetch);

        $fetch2['select'] = $tabel_cell;
        $fetch2['select_join'] = ['table_user_party.user_id', 'table_user_party.party_id'];
        $fetch2['join'] = [array('table'=>'table_user_party','id'=>'party_id','join'=>'right')];
        $fetch2['where'] = ['user_id'=> $this->session->userdata('user_id')];
        $fetch2['start'] = 0;
        $fetch2['limit'] = 1;
        $for_table2 = $this->m_member_cluster->fetch($fetch2);

        //get flashdata
        $alert = $this->session->flashdata('alert');
        $this->data["key"] = ($key!=null) ? $key : false;
        $this->data["alert"] = (isset($alert)) ? $alert : NULL ;
        $this->data["for_table"] = $for_table;
        $this->data["for_table2"] = $for_table2;
        $this->data["table_header"] = $this->services->tabel_header($tabel_cell);
        $this->data["number"] = $pagination['start_record'];
        $this->data["current_page"] = $this->current_page;
        $this->data["block_header"] = "Kelompok Management";
        $this->data["header"] = "TABLE KELOMPOK";
        $this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

        $this->render( "member/cluster/content");
    }


    public function create(){ 
      $getId = $this->m_fund_applicant->getWhere('user_id='.$this->session->userdata('user_id'));
      if($getId!=false){
        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, 'Kelompok Sudah Ada, Silahkan Pilih Menu di sebelah kiri Untuk Menajemen Kelompok'));
        redirect($this->parent_page.'/cluster');
      }else{
        if($this->input->post()!=null){
        $this->form_validation->set_rules($this->services->validation_config());
          if($this->form_validation->run() === TRUE){
              $input_data = $this->input->post();
              $insert = $this->insert($input_data);
              if($insert){
                $getClusterId = $this->m_member_cluster->getWhere("name='".$this->input->post('name')."'");
                if($getClusterId!=false){
                  $field['user_id'] = $this->session->userdata('user_id');
                  foreach($getClusterId as $vParty){
                    $field['party_id'] = $vParty->id;
                    $field['status'] = 1;
                    $this->m_fund_applicant->add($field);
                    $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Input data berhasil'));
                    redirect($this->parent_page.'/cluster');
                  }
                }
              }else{
                $form = $this->services->form_data();
              }
          }else {
            $alert = $this->errorValidation(validation_errors());
            $this->data['alert'] = $this->alert->set_alert(Alert::WARNING, $alert);
            $form = $this->services->form_data();
          }
        }else{
          $form = $this->form_data;
        }
      }

      $this->data['form_data'] = $form;
      $this->data['form_action'] = site_url($this->current_page.'/create');
      $this->data['name'] = $this->name;
      $this->data['parent_page'] = $this->parent_page.'/cluster';
      $this->data["block_header"] = "Kelompok Management";
      $this->data["header"] = "Tambah Kelompok";
      $this->data["sub_header"] = 'Tekan Tombol Simpan Ketika Selesai Mengisi Form';
      $this->render( "member/cluster/create");
    }

    public function createUser(){
      $field['party_id'] = $this->input->post('party_id');
      $field['user_id'] = $this->session->userdata('user_id'); 

      $getId = $this->m_fund_applicant->getWhere('user_id='.$this->session->userdata('user_id'));
      if($getId!=false){
        $update = $this->m_fund_applicant->update2($this->session->userdata('user_id'), $field);
        if($update){
          $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Data Kelompok Sudah Di Update!'));
          redirect($this->current_page);
        }else{
          $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::WARNING, 'Kelompok Sudah Di pilih!'));
          redirect($this->current_page);
        }
      }else{
        $insert = $this->m_fund_applicant->add($field);
        if($insert){
          $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Data Kelompok Sudah Di Inputkan!'));
          redirect($this->current_page);
        }else{
          $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::WARNING, 'Kelompok Sudah Di pilih!'));
          redirect($this->current_page);
        }
      }
    }

    public function insert($data) {
      $insert = $this->m_member_cluster->add($data);
      return $insert;
    }

    public function edit($id=null){
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $form_value = $this->m_member_cluster->getWhere($w);
      if($form_value==false){
        redirect($this->current_page);
      }else{
        $form_value = $form_value[0];
      }

      if($this->input->post()!=null){
        $this->form_validation->set_rules($this->services->validation_config());
        if($this->form_validation->run() === TRUE){
            $input_data = $this->input->post();
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
      $this->data['form_data'] = $form;
      $this->data['form_action'] = site_url($this->current_page.'/edit/'.$id);
      $this->data['name'] = $this->name;
      $this->data['parent_page'] = $this->current_page;
      $this->data["block_header"] = "Kelompok Management";
      $this->data["header"] = "Ubah Kelompok";
      $this->data["sub_header"] = 'Silahkan ubah data yang ingin anda ganti';
      $this->render( "member/cluster/edit");
    }

    public function update($id, $data) {
      $insert = $this->m_member_cluster->update($id, $data);
      return $insert;
    }


    public function detail($id=null){
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $form_value = $this->m_member_cluster->getWhere($w);
      if($form_value==false){
        redirect($this->current_page);
      }else{
        $form_value = $form_value[0];
      }

      $this->data['form_data'] = $this->services->form_data($form_value);
      $this->data['parent_page'] = $this->current_page;
      $this->data['name'] = $this->name;
      $this->data['detail'] = true;
      $this->data["block_header"] = "Kelompok Management";
      $this->data["header"] = "Detail Kelompok";
      $this->data["sub_header"] = 'Halaman Ini Hanya Berisi Informasi Detail Dari Data';
      $this->render("member/cluster/detail");
    }

    public function delete() {
      $id = $this->input->post('id');
      if($id==null){
        redirect($this->current_page);
      }
      $w['party_id'] = $id;
      $delete = $this->m_member_cluster->delete($w);
      if($delete){
        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Delete data berhasil'));
        redirect($this->current_page);
      }else{
        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::WARNING, 'Terjadi Kesalahan'));
        redirect($this->current_page);
      }

    }
}

?>