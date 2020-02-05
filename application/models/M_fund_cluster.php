<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_fund_cluster extends CI_Model{
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

    public function add($data){
      $this->db->insert($this->table,$data);
      return $id = $this->db->insert_id($this->table . '_id_seq');
      // return ($this->db->affected_rows() != 1) ? false : true;
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

    public function getLead2($kec){
      $this->db->select('*');
      $this->db->from('table_userprofiles');
      // $this->db->join('table_users b', 'a.user_id=b.id', 'left');
      $this->db->where('district', $kec);
      // $this->db->where('a.status', 1);
      $query = $this->db->get();
      return $query->result();
    }

    public function is_complete_party( $party_id )
    {
      $sql = "
        SELECT COUNT(*) AS `numrows`
        FROM `table_party`
        INNER JOIN `table_user_party` ON `table_user_party`.`party_id` = `table_party`.`id`
        WHERE `table_party`.`id` = '$party_id'
        AND `table_user_party`.`status` != 2
        AND (
        `table_user_party`.`status` = 1
        OR `table_user_party`.`status` = 3
        OR `table_user_party`.`status` = 4
        )
      ";
      $count =  $this->db->query($sql)->row()->numrows ;
      return 3 <= $count && $count <= 5;
      return;
        $this->db->select( [
          "table_party.*",
        ] );
         $this->db->join(
        "table_user_party",
        "table_user_party.party_id = table_party.id",
        "inner"
      );
        $this->db->where( "table_party.id ", $party_id );
        $this->db->where( "table_user_party.status !=  ", 2 );
        $this->db->or_where( "table_user_party.status ", 1 );
        $this->db->or_where( "table_user_party.status ", 3 );
        $this->db->or_where( "table_user_party.status ", 4 );
        $count = $this->db->count_all_results( 'table_party' ) ;
        return 3 <= $count && $count <= 5 ;
    }

    public function is_full_party( $party_id )
    {
        $this->db->select( [
          "table_party.*",
        ] );
         $this->db->join(
        "table_user_party",
        "table_user_party.party_id = table_party.id",
        "inner"
      );
        $this->db->where( "table_party.id ", $party_id );
        $count = $this->db->count_all_results( 'table_party' ) ;
        return $count >= 5 ;
    }
}