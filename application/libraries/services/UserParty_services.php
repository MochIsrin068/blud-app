<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class UserParty_services{
    function __construct(){

    }

    public function __get($var)
  	{
  		return get_instance()->$var;
  	}

    public $name = [
      'id',
      'user_id',
      'status',
    ];

    public $label =  [
      'id' => 'Pemohon Id',
      'user_id' => 'Nama Pemohon',
      'users_username'  => 'Nama Pemohon',
      'party_id' => 'Nama Kelompok',
      'party_name' => 'Nama Kelompok',
      'phone' => 'No Pemohon',
      'status' => 'Status',
    ];

    public $type =  [
      'id' => 'number',
      'user_id' => 'select',
      'status' => 'radio',
    ];

    // public function validation_config($type=null){
    //     $arr_con = [];

    //     foreach ($this->name as $key => $value) {
    //       if($value!='id'){
    //         switch ($value) {

    //           default:
    //             $arr = array(
    //               'field' => $value,
    //               'label' => $this->label[$value],
    //               'rules' =>  'trim|required',
    //               'errors' => array(
    //                           'required' => 'Field %s tidak boleh kosong  .',
    //                   )
    //             );
    //             break;
    //         }

    //         array_push($arr_con, $arr);
    //       }
    //     }

    // 		return $arr_con;
  	// }

    public function form_data($form_value=null, $param=null){
        $this->load->model('m_user');
        $this->load->model('m_fund_cluster');
        $user = $this->m_user->getWhere('group_id=2');
        $kelompok = $this->m_fund_cluster->get();

        foreach ($user as $k => $v) {
          $select['user_id'][$v->id] = $v->first_name.' '.$v->last_name;
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
                'option' => $select[$value],
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
