<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cluster_services{
    function __construct(){

    }

    public function __get($var)
  	{
  		return get_instance()->$var;
  	}

    public $name = [
      'id',
      'name',
      'user_id',
    ];

    public $label =  [
      'id' => 'Id Kelompok',
      'name'=> 'Nama Kelompok',
      'user_id'=> 'Ketua Kelompok',
      'username'=> 'Ketua Kelompok',
      'first_name'=> 'Anggota',
    ];

    public $type =  [
      'id' => 'number',
      'name'=> 'text',
      'user_id'=> 'select',
    ];

    public function validation_config(){
        $arr_con = [];
        foreach ($this->name as $key => $value) {
          if($value!='id'){
            $arr = array(
              'field' => $value,
    					'label' => $this->label[$value],
    					'rules' =>  'trim|required',
              'errors' => array(
                          'required' => 'Field %s tidak boleh kosong  .',
                  )
            );
            array_push($arr_con, $arr);
          }
        }

    		return $arr_con;
  	}

    public function form_data($form_value=null){
    	  $this->load->model('m_user');
    	  $this->load->model('m_userprofiles');
        $user = $this->m_user->getWhere('group_id = 2');
        $select[0] = '';

        foreach ($user as $k => $v) {
          $getKtp = $this->m_userprofiles->getWhere('user_id='.$v->id);
          foreach($getKtp as $ktp){
            $select['user_id'][$v->id] = $v->first_name.' '.$v->last_name.' - '.$ktp->identity_card_number;
          }
        }

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
              $data[$value] = array(
                'name' => $value,
                'label' => $value,
                'id' => $value,
                'type' => $this->type[$value],
                'placeholder' => $this->label[$value],
                // 'option' => !empty($user) ? $select[$value] : array(),
                'option' => isset( $select[$value] ) ? $select[$value] : array() ,
                'class' => 'form-control show-tick',
                'data-live-search' => "true",
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
        unset($data['user_id']);// new editan
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
