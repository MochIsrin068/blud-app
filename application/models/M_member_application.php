<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_member_application extends CI_Model{

    function __construct() {
        parent::__construct();
    }

    private $table = 'table_request';
    private $base = 'member ';
    private $id = 'id';

    public function fetch($data){
      $where = (isset($data['where'])) ? $data['where'] : null;
      $select = (isset($data['select'])) ? $data['select'] : null;
      $select_join = (isset($data['select_join'])) ? $data['select_join'] : null;
      $join = (isset($data['join'])) ? $data['join'] : null;

      if($select==null || !is_array($select)){
        $this->db->select('*');
      }else{
        foreach($select as $s){
          $this->db->select($this->table.'.'.$s);
        }
        if($select_join!=null) {
          foreach ($select_join as $sj) {
            $this->db->select($sj);
          }
        }
      }

      $this->db->distinct();

      $this->db->from($this->table);

      if($join!=null && is_array($join)){
        foreach($join as $j){
            if($j['table'] == "table_user_party"){
              $this->db->join(
                $j['table'],
                $j['table'].'.party_id'.'='.$j['id'],
                $j['join']
              );
            }else{
              $this->db->join(
                $j['table'],
                $j['table'].'.id'.'='.$this->table.'.'.$j['id'],
                $j['join']
              );
            }
        }
      }

      if($where!=null && is_array($where)){
        $this->db->where($where['id']);
      }

      $query = $this->db->get();
      return $query->result();
    }

    public function fetchMember($data, $myTable){
      $where = (isset($data['where'])) ? $data['where'] : null;
      $select = (isset($data['select'])) ? $data['select'] : null;
      $select_join = (isset($data['select_join'])) ? $data['select_join'] : null;
      $join = (isset($data['join'])) ? $data['join'] : null;
      $order = (isset($data['order'])) ? $data['order'] : null;

      if($select==null || !is_array($select)){
        $this->db->select('*');
      }else{
        foreach($select as $s){
          $this->db->select($myTable.'.'.$s);
        }
        if($select_join!=null) {
          foreach ($select_join as $sj) {
            $this->db->select($sj);
          }
        }
      }

      $this->db->distinct();

      $this->db->from($myTable);

      if($join!=null && is_array($join)){
        foreach($join as $j){
            $this->db->join(
              $j['table'],
              $myTable.'.'.$j['id'].'='.$j['table'].'.id',
              $j['join']
            );
        }
      }

      if($where!=null && is_array($where)){
        $this->db->where($where);
      }


      if($order!=null && is_array($order)){
        $this->db->order_by($order['field'],$order['type']);
      }

      $query = $this->db->get();
      return $query->result();
    }

    public function get(){
      $query = $this->db->get($this->table);
      return $query->result();
    }


    public function getWhere($data, $newTable = null){
      if($newTable != null){
        $query = $this->db->where($data)->get($newTable);
      }else{
        $query = $this->db->where($data)->get($this->table);
      }
      return $query->result();
    }

    public function getCluster($data){
      $table = 'table_user_party';
      $this->db->select('*');
      $this->db->from($table);
      $this->db->join('table_party', 'table_party.id='.$table.'.'.'party_id', 'left');
      $this->db->where('table_user_party.user_id', $data);
      $query = $this->db->get();
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
      //run Query to update data
      if(isset($data[$this->id]))unset($data[$this->id]);
      $query = $this->db->where('id', $id)->update(
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
      // $this->db->limit($limit, $start);
      $query = $this->db->count_all_results();
      return $query;

    }
    public function last(){
      return $this->db->count_all($this->table);;
    }



}
?>
