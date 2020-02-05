<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Document_applicant extends Fund_Controller {

    private $services = null;
    private $parent_page = 'fund/';
    private $current_page = 'fund/document_applicant/';
    private $current_page2 = 'fund/applicant/';
    private $form_data = null;

    // Handle Dat Upload
    private $identity_card = null;
    private $property_tax = null;
    private $electricity_bills = null;
    private $water_bills = null;
    private $letter_of_recommendation = null;

    public function __construct(){
        parent::__construct();
        $this->load->library('services/Document_applicant_services');
        $this->services = new Document_applicant_services;
        $this->load->model(array('m_document_applicant'));
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

    public function create($id=null){

        if($id==null){
            redirect($this->current_page);
        }

        $this->uploads($id, 'identity_card');//REQUIRE
        $this->uploads($id, 'property_tax');
        $this->uploads($id, 'electricity_bills');
        $this->uploads($id, 'water_bills');
        $this->uploads($id, 'letter_of_recommendation');

        $w['user_id'] = $id;
        $form_value = $this->m_document_applicant->getWhere($w);

        if($this->input->post()!=null){
            $manipulate = !empty($form_value) ? 'acak' : 'nothing';
            if($this->identity_card !== $manipulate){
                // $this->form_validation->set_rules($this->services->validation_config());
                // $this->form_validation->set_rules('identity_card_number', 'Nomor KTP', 'required',
                //         array('required' => '%s Tidak Boleh Kosong')
                // );
                $tos = !empty($form_value) ? FALSE : TRUE;
                // if($this->form_validation->run() === $tos){
                    $input_data['user_id'] = $id;
                    // $input_data['identity_card_number'] =  $this->input->post('identity_card_number');
                    $input_data['date'] = $this->input->post('date');
                    $input_data['nominal'] = $this->input->post('nominal');
                    $input_data['status'] = $this->input->post('status');
                    $input_data['identity_card'] = $this->identity_card;
                    $input_data['property_tax'] = $this->property_tax;
                    $input_data['electricity_bills'] = $this->electricity_bills;
                    $input_data['water_bills'] = $this->water_bills;
                    $input_data['letter_of_recommendation'] = $this->letter_of_recommendation;

                    if(!empty($form_value)){

                        $update_data['user_id'] = $id;
                        
                        // if($this->input->post('identity_card_number')){
                        //     $update_data['identity_card_number'] =  $this->input->post('identity_card_number');
                        // }

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
                        if($update!=false){
                            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Update data berhasil'));
                            redirect($this->current_page2);
                        }else{
                            $alert = 'Terjadi Kesalahan';
                            $this->data['alert'] = $this->alert->set_alert(Alert::WARNING, $alert);
                            $form = $this->services->form_data($id);
                        }
                    }else{
                        $insert = $this->insert($input_data);
                        if($insert!=false){
                            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, 'Tambah data berhasil'));
                            redirect($this->current_page2);
                        }else{
                            $form = $this->services->form_data($id);
                        }
                    }
                    
                // }else{
                //     // $this->form_validation->set_rules($this->services->validation_config());
                //     $alert = $this->errorValidation(validation_errors());
                //     $this->data['alert'] = $this->alert->set_alert(Alert::WARNING, $alert);
                //     $form = $this->services->form_data($id);
                // }
            }else{
                $alert = 'Scan KTP Harus Ada';
                $this->data['alert'] = $this->alert->set_alert(Alert::WARNING, $alert);
                $form = $this->services->form_data($id);
            }
        }else{
            if(!empty($form_value)){
                $form = $this->services->form_data($id, $form_value);                
            }else{
                $form = $this->services->form_data($id);
            }
        }

        if(!empty($form_value)){
            $this->data['note'] = "Masukkan Kembali Nomor Ktp dan Scan Ktp untuk melengkapi data";
        }else{
            $this->data['note'] = "";
        }
        $this->data['form_data'] = $form;
        $this->data['form_action'] = site_url($this->current_page.'create/'.$id);
        $this->data['parent_page'] = $this->current_page;
        $this->data['parent_page2'] = $this->current_page2;
        $this->data["block_header"] = "Upload Document";
        $this->data["header"] = "Upload Document";
        $this->data["sub_header"] = 'Silahkan Lengkapi Data Yang Akan Dilengkapi';
        $this->render("admin_fund/document/create");
    }


    public function insert($data) {
        $insert = $this->m_document_applicant->add($data);
        return $insert;
    }

    public function update($id, $data) {
        $insert = $this->m_document_applicant->update($id, $data);
        return $insert;
    }
}