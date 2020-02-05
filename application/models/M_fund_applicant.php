<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH . '/core/MY_Model_core.php';

class M_fund_applicant extends CI_Model
{

    function __construct() {
        parent::__construct();
    }

    private $table = 'table_user_party';
    private $base = 'fund';
    private $id = 'id';


    public function getUserProfiles(){
      $this->db->select('*');
      return $this->db->from('table_userprofiles')->get()->result();
    }


    public function fetch($data){
      $start = $data['start'];
      $limit = $data['limit'];
      $where = (isset($data['where'])) ? $data['where'] : null;
      $select = (isset($data['select'])) ? $data['select'] : null;
      $select_join = (isset($data['select_join'])) ? $data['select_join'] : null;
      $join = (isset($data['join'])) ? $data['join'] : null;
      $like = (isset($data['like'])) ? $data['like'] : null;
      $order = (isset($data['order'])) ? $data['order'] : null;

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
          $this->db->join(
            $j['table'],
            $this->table.'.'.$j['id'].'='.$j['table'].'.id',
            $j['join']
          );
        }
      }

      if($where!=null && is_array($where)){
        $this->db->where($where);
      }

      if($like!=null && is_array($like)){
        $this->db->group_start();
        $i=0;
        foreach($like['name'] as $l){
          $l = ($join!=null) ?  $this->table.'.'.$l: $l;
          if($i==0){
            $this->db->like($l, $like['key']);
          }else{
            $this->db->or_like($l, $like['key']);
          }
          $i++;
        }
        if($select_join!=null){
          foreach($select_join as $j){
            $join_name = explode( ' ',$j)[0];
            $this->db->or_like($join_name, $like['key']);
          }
        }
        $this->db->group_end();
      }

      if($order!=null && is_array($order)){
        $this->db->order_by($order['field'],$order['type']);
      }

      if($limit!=null){
        $this->db->limit($limit, $start);
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

    public function get_total(){
      return $this->db->count_all($this->table);
    }

    public function add($data){
      $this->db->insert($this->table,$data);
      return ($this->db->affected_rows() != 1) ? false : true;
    }

    
    public function addProfiles($data){
      $this->db->insert('table_userprofiles',$data);
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

    public function is_position_exist( $party_id,  $status )
    {
      $this->db->select( [
        $this->table.".*",
      ] );
      
      $this->db->where( $this->table.".party_id", $party_id );		
      $this->db->where( $this->table.".status ", $status );
      $this->db->where( $this->table.".status != ", '2' );
    
      return $this->db->count_all_results( $this->table ) > 0;
    }

    public function is_full( $party_id )
    {
      $this->db->select( [
        $this->table.".*",
      ] );
      
         $this->db->where( $this->table.".party_id", $party_id );		
    
      return $this->db->count_all_results( $this->table ) > 5;
    }

    public function getForAkad($partyId){
      $this->db->select('*');
      $this->db->from($this->table.' a');
      $this->db->join('table_party b', 'a.party_id=b.id', 'left');
      $this->db->join('table_userprofiles c', 'a.user_id=c.user_id', 'left');
      $this->db->where('party_id='.$partyId);
      $query = $this->db->get();
      return $query->result();
    }

}

?>
