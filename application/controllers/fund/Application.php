<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application extends Fund_Controller {

    private $services = null;
    private $name = null;
    private $parent_page = 'fund';
    private $current_page = 'fund/application';
    private $current_page2 = 'fund/document';
    private $form_data = null;

    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('services/Application_services');
        $this->services = new Application_services;
        $this->name = $this->services->name;
        $this->form_data = $this->services->form_data();
        $this->load->model(
          array(
            'm_fund_application',
            'm_fund_cluster',
            'm_document_applicant',
            'm_fund_applicant',
            'm_userprofiles'
            )
        );
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
    public function create( $party_id ){
        
        if( ! $this->m_fund_cluster->is_complete_party( $party_id ) )
        {
          $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, "Anggota kelompok Belum Lengkap" ) );
            redirect( site_url( 'fund/cluster' ) ,'refresh');
            // echo var_dump( $this->m_fund_cluster->db );
            return ;
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
      $this->render( "admin_fund/application/create");
    }
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function asdf_create(){
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
        $this->form_validation->set_rules($this->services->validation_config('edit'));
        if($this->form_validation->run() === TRUE){
            $input_data['party_id'] = $this->input->post('party_id');
            $input_data['date'] = $this->input->post('date');
            $input_data['status'] = 1;
            $input_data['info'] = $this->input->post('info');
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
      $partyV = $this->m_fund_application->getWhere($p);

      $party_id = null;
      foreach($partyV as $idp){
        $idParty = $idp->party_id;
        $fetch['where'] = array("table_party.id"=> $idParty);
        $party_id = $idParty;
      }
      $myTable = 'table_user_party';
      $member = $this->m_fund_application->fetchMember($fetch, $myTable);

      $param = 'detail';
      $this->data['form_data'] = $this->services->form_data($form_value, $param, $party_id);
      $this->data['anggota'] = $member;
      $this->data['parent_page'] = $this->current_page;
      $this->data['name'] = $this->name;
      $this->data['detail'] = true;
      $this->data["block_header"] = "Permohonan Management";
      $this->data["header"] = "Detail Permohonan";
      $this->data["sub_header"] = 'Halaman Ini Hanya Berisi Informasi Detail Dari Data';
      $this->render("admin_fund/application/detail");
    }

    public function delete() {
      $id = $this->input->post('id');
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

    public function documentUpload(){
      $config['upload_path']          = './uploads/dokumen';
      $config['allowed_types']        = 'jpg|png|pdf|doc|docx|jpeg';
      $config['max_size']             = 5024;
      // $config['max_width']            = 1024;
      // $config['max_height']           = 768;
      $config['file_name'] = 'document akad approved -'.date('d/m/y');
      $this->load->library('upload', $config);
      $this->upload->initialize($config);

      if (!$this->upload->do_upload('name'))
      {
        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::WARNING, 'Document Tidak Terupload'));
        redirect($this->current_page);
      }
      else
      {
        $id = $this->input->post('id');
        $partyId = $this->input->post('idParty');
        $input_data['status'] = 4;

        $update = $this->update($id, $input_data);
        if($update){
          $docName = $this->upload->data();

          $d['party_id'] = $partyId;
          $d['name'] = $docName['file_name'];
          
          $insert = $this->m_fund_application->insertDoc($d);

          if($insert!=false){
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Upload document berhasil'));
            redirect($this->current_page);
          }else{
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::WARNING, 'Document Tidak Terupload'));
            redirect($this->current_page);
          }
        }else{
          $form = $this->services->form_data();
          redirect($this->current_page);
        }
      }
    }

    public function cetakAkad($id=null){
      if($id==null){
        redirect($this->current_page);
      }
      $data['id'] = $id; 
      $getApplication = $this->m_fund_application->getWhere($data);

      if($getApplication){
        $partyId = $getApplication[0]->party_id;

        $getForAkad = $this->m_fund_applicant->getForAkad($partyId);

        $this->load->library('pdf');
        $pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
        
        foreach($getForAkad as $gfa){
          
          $pdf->SetTitle('KUITANSI');
          
          $pdf->setPrintHeader(false);
          $pdf->setPrintFooter(false);
          
          $pdf->SetTopMargin(18);
          $pdf->SetLeftMargin(18);
          $pdf->SetRightMargin(18);
          $pdf->setFooterMargin(39);

          $pdf->SetAutoPageBreak(true);
          $pdf->SetAuthor('DANA BERGULIR');
          $pdf->SetDisplayMode('real', 'default');
          $pdf->AddPage();
          $pdf->SetFont('times', NULL, 11);

          $d['surename'] = $gfa->surename;
          $d['address'] = $gfa->address;
          $d['card'] = $gfa->identity_card_number;
          $d['party'] = $gfa->name.'-'.$gfa->party_id;
          $d['rt'] = $gfa->rt;
          $d['rw'] = $gfa->rw;
          $d['kec'] = $gfa->district;
          $html = $this->load->view('templates/report/akad', $d, true);	
          $pdf->writeHTML($html, true, false, true, false, '');
        }
        // return;
        $pdf->Output("AKAD KREDIT PINJAMAN ".date('d-m-Y').".pdf",'D');
        // header("Location: ".site_url( $this->current_page ));
      }
      
    }

    public function getReceipt($id=null){
      if($id==null){
        redirect($this->current_page);
      }

      $data['id'] = $id; 
      $getApplication = $this->m_fund_application->getWhere($data);
      
      if($getApplication){
          $party_id = null;
          $w = array();
          foreach($getApplication as $a){
            $w['id'] = $a->party_id;
            $party_id = $a->party_id;
          }
          
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
          $fetch['order'] = array("field"=>"table_user_party.status","type"=>"DESC");
          $fetch['where'] = array("table_user_party.party_id"=> $party_id);
          $myTable = 'table_users';
          $member = $this->m_fund_cluster->fetchMember($fetch, $myTable);

          $this->data['party_id'] = $party_id;
          $this->data['form_data'] = $this->services->form_data($form_value);
          $this->data['parent_page'] = $this->current_page;
          $this->data['anggota'] = $member;
          $this->data['name'] = $this->name;
          $this->data['detail'] = true;
          $this->data["block_header"] = "Kelompok Detail";
          $this->data["header"] = "Detail Kelompok";
          $this->data["sub_header"] = 'Halaman Ini Hanya Berisi Informasi Detail Dari Data';
          // $this->data["receipt_modal"] = $this->load->view('templates/actions/modal_form_kwitansi');
          $this->render("admin_fund/application/receipt");
      }

    }


    public function generate_receipt($id=null )
    {	
      
      if($id==null){
        redirect($this->current_page);        
      }

      $this->load->library('pdf');
      $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);

      $pdf->SetTitle('KUITANSI');
      
      $pdf->setPrintHeader(false);
      $pdf->setPrintFooter(false);
      
      $pdf->SetTopMargin(10);
      $pdf->SetLeftMargin(10);
      $pdf->SetRightMargin(10);
      //$pdf->setFooterMargin(20);
      $pdf->SetAutoPageBreak(true);
      $pdf->SetAuthor('DANA BERGULIR');
      $pdf->SetDisplayMode('real', 'default');
      $pdf->AddPage();
      $pdf->SetFont('times', NULL, 9);

      $d = array();

      $fetch['user_id'] = $id;
      $member = $this->m_userprofiles->getWhere($fetch);
      // echo var_dump($member);return;
      foreach($member as $m){
        $d['name'] = $m->surename;
        $f['user_id'] = $id;
        $doc = $this->m_document_applicant->getWhere($f);
        foreach($doc as $dc){
          $d['nominal'] = $dc->nominal;
          $d['kepala_upt'] = $this->input->post('kepala_upt');
          $d['bendahara'] = $this->input->post('bendahara');
          $html = $this->load->view('templates/report/receipt_dagur', $d, true);	
        }
      }
      
      $party_id = $this->input->post('party_id');

      // return;
      $pdf->writeHTML($html, true, false, true, false, '');
      $pdf->Output("KUITANSI DAGUR".date('d-m-Y').".pdf",'D');
      redirect($this->current_page.'/getReceipt/'.$party_id);        
      // header("Location: ".site_url( $this->current_page ));
    }


}
