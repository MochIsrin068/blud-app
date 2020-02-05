<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Contract_services{
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
