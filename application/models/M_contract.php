<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_contract extends CI_Model{
    function __construct() {
        parent::__construct();
    }

    private $table = 'table_party';
    private $base = 'fund';
    private $id = 'id';


    public function fetch($data){
      $start = $data['start'];
      $limit = $data['limit'];
      $where = (isset($data['where'])) ? $data['where'] : null;
      $select = (isset($data['select'])) ? $data['select'] : null;
      $join = (isset($data['join'])) ? $data['join'] : null;
      $like = (isset($data['like'])) ? $data['like'] : null;
      $order = (isset($data['order'])) ? $data['order'] : null;

      if($select==null || !is_array($select)){
        $this->db->select('*');
      }else{
        foreach($select as $s){
          $this->db->select($s);
        }
      }

      $this->db->distinct();

      $this->db->from($this->table);

      if($join!=null && is_array($join)){
        foreach($join as $j){
          if($j['table'] == 'table_users'){
            $this->db->join(
              $j['table'],
              $j['table'].'.'.$j['id'] .'='. $j['on'],
              $j['join']
            );
          }else{
            $this->db->join(
            $j['table'],
            $j['table'].'.'.$j['id'] .'='. $this->table.'.'.$this->id,
            $j['join']
          );
          }
        }
      }

      if($where!=null && is_array($where)){
        $this->db->where($where);
      }

      if($like!=null && is_array($like)){
        $this->db->group_start();
        $i=0;
        foreach($like['name'] as $l){
          if($i==0){
            $this->db->like($l, $like['key']);
          }else{
            $this->db->or_like($l, $like['key']);
          }
          $i++;
        }
        $this->db->group_end();
      }

      if($order!=null && is_array($order)){
        $this->db->order_by($this->table.'.'.$order['field'],$order['type']);
      }

      if($limit!=null){
        $this->db->limit($limit, $start);
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
              $myTable.'.id='.$j['table'].'.'.$j['id'],
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
    public function getWhere($data){
      $query = $this->db->where($data)->get($this->table);
      return $query->result();
    }

    public function get_current($limit, $start){
      $this->db->select('*');
      $this->db->distinct();
      $this->db->from($this->table);
      $this->db->limit($limit, $start);
      $query = $this->db->get($this->table);
      if ($query->num_rows() > 0){
          return $query->result();
      }
      return false;
    }

    public function get_total(){
      return $this->db->count_all($this->table);
    }

    public function search($key=null, $limit=null, $start=null, $name=null){
      $this->db->select('*');
      $this->db->distinct();
      $this->db->from($this->table);
      foreach ($name as $k => $value) {
        if ($k==0) {
          $this->db->like($this->table.'.'.$value, $key);
        }else {
          $this->db->or_like($this->table.'.'.$value, $key);
        }
      }
      $this->db->limit($limit, $start);
      $query = $this->db->get($this->table);
      if($query->num_rows() > 0) {
        foreach($query->result() as $row) {
          $data[] = $row;
        }
        return $data;
      }
      return null;
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

    public function getLead($party_id){
      $this->db->select('first_name, last_name, status');
      $this->db->from('table_user_party a');
      $this->db->join('table_users b', 'a.user_id=b.id', 'left');
      $this->db->where('a.party_id', $party_id);
      $this->db->where('a.status', 1);
      $query = $this->db->get();
      return $query->result();
    }
}