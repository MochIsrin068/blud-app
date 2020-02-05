<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH . '/core/MY_Model_core.php';

class M_dept_payment extends CI_Model
{

    function __construct() {
        parent::__construct();
    }

    private $table = 'table_dept_payment';
    // private $base = 'fund';
    private $id = 'id';

    public function get(){
        $query = $this->db->get($this->table);
        return $query->result();
    }
    public function getWhere($data){
        $query = $this->db->where($data)->get($this->table);
        return $query->result();
    }

    public function get_total(){
        return $this->db->count_all($this->table);
    }

    public function add($data){
        $this->db->insert($this->table,$data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    public function update($id, $data){
      if(isset($data[$this->id]))unset($data[$this->id]);
     return $this->db->where('user_id', $id)->update(
        $this->table, $data
      );
      return ($this->db->affected_rows() != 1) ? false : true;

    }

    public function update2($id, $data){
      //run Query to update data
      // if(isset($data[$this->id]))unset($data[$this->id]);
      $query = $this->db->where('user_id', $id)->update(
        $this->table, $data
      );
      return ($this->db->affected_rows() != 1) ? false : true;

    }

    public function delete($data){

      $this->db->delete($this->table, $data);
      return ($this->db->affected_rows() != 1) ? false : true;
    }


    public function search_count($key=null, $name=null){
      foreach ($name as $k => $value) {
        if ($k==0) {
          $this->db->like($value, $key);
        }else {
          $this->db->or_like($value, $key);
        }
      }
      $this->db->from($this->table);
      $query = $this->db->count_all_results();
      return $query;

    }
    public function last(){
      return $this->db->count_all($this->table);;
    }

    public function sum($data){
        $this->db->select_sum('nominal');
        $this->db->where($data);
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function getUserParty($data){
        $this->db->select('a.*, b.*, c.*, d.id as id_agreement, d.nominal');
        $this->db->from('table_user_party a');
        $this->db->join('table_party b', 'a.party_id=b.id', 'left');
        $this->db->join('table_users c', 'a.user_id=c.id', 'left');
        $this->db->join('table_dept_agreement d', 'a.user_id=d.user_id', 'left');
        $this->db->where('b.id', $data);
        $query = $this->db->get();
        return $query->result();
    }
}

?>
