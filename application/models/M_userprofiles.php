<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_userprofiles extends CI_Model{
    function __construct() {
        parent::__construct();
    }

    private $table = 'table_userprofiles';

    public function getWhere($data){
      $query = $this->db->where($data)->get($this->table);
      return $query->result();
    }
}