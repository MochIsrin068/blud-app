<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documrnt extends Fund_Controller {

    private $services = null;
    private $name = null;
    private $parent_page = 'fund';
    private $current_page = 'fund/application';
    private $current_page2 = 'fund/document';
    private $form_data = null;

    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('services/Document_services');
        $this->services = new Document_services;
        $this->name = $this->services->name;
        $this->form_data = $this->services->form_data();
        $this->load->model(array('m_fund_document'));
    }


    public function index(){
        //basic variable
        $key = $this->input->get('key');
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;
        $tabel_cell = ['id','name','date','status','info'];
        //pagination parameter
        $pagination['base_url'] = base_url($this->current_page) .'/index';
        $pagination['total_records'] = (isset($key)) ? $this->m_fund_application->search_count($key, $this->name) : $this->m_fund_application->get_total();
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
        $for_table = $this->m_fund_application->fetch($fetch);

        //get flashdata
        $alert = $this->session->flashdata('alert');
        $this->data["key"] = ($key!=null) ? $key : false;
        $this->data["alert"] = (isset($alert)) ? $alert : NULL ;
        $this->data["for_table"] = $for_table;
        $this->data["table_header"] = $this->services->tabel_header($tabel_cell);
        $this->data["number"] = $pagination['start_record'];
        $this->data["current_page"] = $this->current_page;
        $this->data["current_page2"] = $this->current_page2;
        $this->data["block_header"] = "Permohonan Management";
        $this->data["header"] = "TABLE PERMOHONAN";
        $this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

        $this->render( "admin_fund/application/content");
    }


    public function create(){
        if($this->input->post()!=null){
          $this->form_validation->set_rules($this->services->validation_config());
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

      $this->data['form_data'] = $form;
      $this->data['form_action'] = site_url($this->current_page.'/create');
      $this->data['name'] = $this->name;
      $this->data['parent_page'] = $this->current_page;
      $this->data["block_header"] = "Permohonan Management";
      $this->data["header"] = "Tambah Permohonan";
      $this->data["sub_header"] = 'Tekan Tombol Simpan Ketika Selesai Mengisi Form';
      $this->render( "admin_fund/application/create");
    }

    public function insert($data) {
        $insert = $this->m_fund_application->add($data);
        return $insert;
    }

    public function edit($id=null){
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $form_value = $this->m_fund_application->getWhere($w);
      if($form_value==false){
        redirect($this->current_page);
      }else{
        $form_value = $form_value[0];
      }

      if($this->input->post()!=null){
        $this->form_validation->set_rules($this->services->validation_config());
        if($this->form_validation->run() === TRUE){
            $input_data['id'] = $this->input->post('id');
            $input_data['party_id'] = $this->input->post('party_id');
            $input_data['date'] = $this->input->post('date');
            $input_data['status'] = 1;
            $input_data['catatan'] = $this->input->post('catatan');
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
      $this->data["block_header"] = "Permohonan Management";
      $this->data["header"] = "Ubah Permohonan";
      $this->data["sub_header"] = 'Silahkan ubah data yang ingin anda ganti';
      $this->render( "admin_fund/application/edit");
    }

    public function update($id, $data) {
      $insert = $this->m_fund_application->update($id, $data);
      return $insert;
    }


    public function detail($id=null){
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $form_value = $this->m_fund_application->getWhere($w);
      if($form_value==false){
        redirect($this->current_page);
      }else{
        $form_value = $form_value[0];
      }

      $this->data['form_data'] = $this->services->form_data($form_value);
      $this->data['parent_page'] = $this->current_page;
      $this->data['name'] = $this->name;
      $this->data['detail'] = true;
      $this->data["block_header"] = "Permohonan Management";
      $this->data["header"] = "Detail Permohonan";
      $this->data["sub_header"] = 'Halaman Ini Hanya Berisi Informasi Detail Dari Data';
      $this->render("admin_fund/application/detail");
    }

    public function delete($id) {
      if($id==null){
        redirect($this->current_page);
      }
      $w['id'] = $id;
      $delete = $this->m_fund_application->delete($w);
      if($delete!=false){
        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Delete data berhasil'));
        redirect($this->current_page);
      }else{
        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::WARNING, 'Terjadi Kesalahan'));
        redirect($this->current_page);
      }

    }

}
