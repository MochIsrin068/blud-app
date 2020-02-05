<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Application_services{
    function __construct(){

    }

    public function __get($var)
  	{
  		return get_instance()->$var;
  	}

    public $name = [
      'id',
      'party_id',
      'date',
      'info',
    ];

    public $label =  [
      'id' => 'Pemohon Id',
      'party_id' => 'Nama Kelompok',
      'name' => 'Nama Kelompok',
      'date' => 'Tanggal Permohonan',
      'status' => 'Status Permohonan',
      'info' => 'Catatan',
    ];

    public $type =  [
      'id' => 'number',
      'party_id' => 'select',
      'date' => 'date',
      'info' => 'textarea',
    ];

    public function validation_config($type=null){
        $arr_con = [];
        $username = ($type!='edit') ? 'trim|required|is_unique[table_request.party_id]' : 'trim|required';

        foreach ($this->name as $key => $value) {
          if($value!='id'){
            switch ($value) {
              case 'party_id':
                $arr = array(
                  'field' => $value,
                  'label' => $this->label[$value],
                  'rules' =>  $username,
                  'errors' => array(
                              'required'      => 'Field %s tidak boleh kosong.',
                              'is_unique'      => 'Nama Kelompok %s telah di gunakan.',
                            )
                );
                break;
              default:
                $arr = array(
                  'field' => $value,
                  'label' => $this->label[$value],
                  'rules' =>  'trim|required',
                  'errors' => array(
                              'required' => 'Field %s tidak boleh kosong  .',
                      )
                );
                break;
            }

            array_push($arr_con, $arr);
          }
        }

    		return $arr_con;
  	}

    public function form_data($form_value=null, $param=null, $party_id=null){
        // $userId = $this->m_fund_cluster->is_complete(  );

        $this->load->model('m_fund_cluster');
        $user = $this->m_fund_cluster->getWhere('id='.$party_id.'');
  
        foreach ($user as $k => $v) {
          $select['party_id'][$v->id] = $v->name.'-'.$v->id;
        }

        $select['status'] = ['Di Tolak','Di Survey','Di Setujui'];

    		foreach ($this->name as $key => $value) {
          if($form_value!=null){
            if(isset($form_value->{$value})&&$form_value->{$value}!=null){
              $val = $form_value->{$value};
            }else{
              $val = $this->form_validation->set_value($value);
            }
          }else{
            $val = $this->form_validation->set_value($value);
          }

          switch ($this->type[$value]) {
            case 'select':
              $class = $param == 'detail' ? 'form-control readonly' : 'form-control show-tick';
              $data[$value] = array(
                'name' => $value,
                'label' => $value,
                'id' => $value,
                'type' => $this->type[$value],
                'placeholder' => $this->label[$value],
                'data-live-search' => "true",
                'option' => !empty($user) ? $select[$value] : "",
                'class' => $class,
                'value' => $val,
              );
              break;
            case 'date':
              $data[$value] = array(
                'name' => $value,
                // 'label' => $value,
                // 'id' => $value,
                'type' => $this->type[$value],
                'placeholder' => '',
                'class' => 'form-control',
                'value' => $val,
              );
              break;
            default:
             
                $data[$value] = array(
                'name' => $value,
                'label' => $value,
                'id' => $value,
                'type' => $this->type[$value],
                'placeholder' => $this->label[$value],
                'class' => 'form-control',
                'value' => $val,
                );
                break;
          }

        };
        unset($data['id']);
    		return $data;
  	}

    public function tabel_header($arr){
      $label = [];
      foreach ($arr as $key => $value) {
        $label[$value] = $this->label[$value];
      }
      if(isset($label['id'])) unset($label['id']);
      return $label;
    }

}
?>
