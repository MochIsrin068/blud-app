<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Account_services{
    function __construct(){

    }

    public function __get($var)
  	{
  		return get_instance()->$var;
  	}

    public function validation_config( ){
      $config = array(
        array(
          'field' => 'code',
           'label' => 'Kode',
           'rules' =>  'trim|required',
        ),
        array(
          'field' => 'name',
           'label' => 'Nama',
           'rules' =>  'trim|required',
        ),
        array(
          'field' => 'description',
           'label' => 'Deskripsi',
           'rules' =>  'trim|required',
        ),
        array(
          'field' => 'account_id',
           'label' =>('account_id'),
           'rules' =>  'trim|required',
        ),
      );
      
      return $config;
  	}

}
?>
