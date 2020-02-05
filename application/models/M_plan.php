<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH . '/core/MY_Model_core.php';

class M_plan extends MY_Model_core
{
    function __construct() {
        parent::__construct( 'table_plan' );
        $this->set_join_key( 'plan_id' );
    }

    public function add($data){
      $data = $this->_filter_data($this->table, $data);
      $time = strtotime( '15/5/'.$data['year'] );
      
      if( $this->is_exist( $data['year']  ) )
      {
          return FALSE;
      }
      // return FALSE;
      $this->db->insert( $this->table ,$data );

      $id = $this->db->insert_id( $this->table . '_id_seq');
		
      if( isset($id) )
      {
        return $id;
      }
      return FALSE;
    }

    public function add_batch( $rows ) {
        return $this->db->insert_batch( $this->table , $rows);
    }

    /**
     * update
     *
     * @param array  $data
     * @param array  $data_param
     * @return bool
     * @author madukubah
     */
    public function update( $data, $data_param  )
    {
        if( $this->is_verified_dpa_plan(  $data_param['id'] ) ) return FALSE;

        $this->db->trans_begin();
        $data = $this->_filter_data($this->table, $data);

        $this->db->update($this->table, $data, $data_param );
        if ($this->db->trans_status() === FALSE)
        {
          $this->db->trans_rollback();
          return FALSE;
        }

        $this->db->trans_commit();
        return TRUE;
    }
    /**
     * delete
     *
     * @param array  $data_param
     * @return bool
     * @author madukubah
     */
    public function exist_by_time( $time  )
    { 
        $year = date('Y', $time);

        $this->db->select( [
          $this->table.".*",
        ] );
        
        $this->db->where( $this->table.".year ", $year );
        $this->db->where( $this->table.".status ", 2 );
      
        return $this->db->count_all_results( $this->table ) > 0;
    }
    /*
    * delete
    *
    * @param array  $data_param
    * @return bool
    * @author madukubah
    */
    public function is_exist( $year  )
    { 

        $this->db->select( [
          $this->table.".*",
        ] );
        
        $this->db->where( $this->table.".year ", $year );
      
        return $this->db->count_all_results( $this->table ) > 0;
    }
     /**
     * delete
     *
     * @param array  $data_param
     * @return bool
     * @author madukubah
     */
    public function delete( $data_param  )
    { 
          if( $this->is_verified_dpa_plan(  $data_param['id'] ) ) return FALSE;

          // delete_foreign( $data_param, $models[]  )
          if( !$this->delete_foreign( $data_param, ['m_budget_plan', 'm_bookkeeping', 'm_realization']  ) )
          {
            return FALSE;
          }
          //foreign
          $this->db->trans_begin();

          $this->db->delete( $this->table , $data_param );
          if ($this->db->trans_status() === FALSE)
          {
            $this->db->trans_rollback();

            return FALSE;
          }

          $this->db->trans_commit();

          return TRUE;
    }

    public function plan(  $plan_id )
    {
        $fetch['select'][] = "*" ;
        $fetch['where'][] = [ $this->table.".id" => $plan_id ] ;
        $fetch['order'] = array("field"=>"id","type"=>"ASC");
        $fetch['limit'] = 1;
        return $this->_fetch($fetch);
    }

    public function plan_by_year(  $year )
    {
        $fetch['select'][] = "*" ;
        $fetch['where'][] = [ $this->table.".year" => $year ] ;
        $fetch['order'] = array("field"=>"id","type"=>"DESC");
        $fetch['limit'] = 1;
        return $this->_fetch($fetch);
    }

    public function plans( $start = 0 , $limit = NULL )
    {
        $fetch['select'][] = "*" ;
        $fetch['order'] = array("field"=>"id","type"=>"DESC");
        $fetch['limit'] = $limit;
        $fetch['start'] = $start;

        return $this->_fetch($fetch);
    }

    public function verified_plans( $start = 0 , $limit = NULL )
    {
        $fetch['select'][] = "*" ;
        $fetch['where'][] = [ $this->table.".status = 1 OR ".$this->table.".status = 2" => NULL ] ;
        $fetch['order'] = array("field"=>"id","type"=>"DESC");
        $fetch['limit'] = $limit;
        $fetch['start'] = $start;

        return $this->_fetch($fetch);
    }
    public function dpa_plans( $start = 0 , $limit = NULL )
    {
        $fetch['select'][] = "*" ;
        $fetch['where'][] = [ $this->table.".status = 2" => NULL ] ;
        $fetch['order'] = array("field"=>"id","type"=>"DESC");
        $fetch['limit'] = $limit;
        $fetch['start'] = $start;

        return $this->_fetch($fetch);
    }
    public function is_verified_plan(  $plan_id )
    {
      if( $this->is_verified_dpa_plan(  $plan_id ) ) return TRUE;

      return $this->db->where( $this->table.".id", $plan_id )
                        ->where( $this->table.".status", 1 )
                        ->limit(1)
                        ->count_all_results( $this->table ) > 0;
    }
    
    public function is_verified_dpa_plan(  $plan_id )
    {
      return $this->db->where( $this->table.".id", $plan_id )
                        ->where( $this->table.".status", 2 )
                        ->limit(1)
                        ->count_all_results( $this->table ) > 0;
    }

    public function search( $key )
    {
        $fetch['select'][] = "*" ;
        $fetch['like'] = ($key!=null) ? array("name" => ['table_plan.title', 'table_plan.year'], "key" => $key) : null;
        $fetch['order'] = array("field"=>"id","type"=>"DESC");

        return $this->_fetch($fetch);
    }
    public function search_verified_plans( $key )
    {
        $fetch['select'][] = "*" ;
        $fetch['where'][] = [ $this->table.".status" => 1 ] ;
        $fetch['like'] = ($key!=null) ? array("name" => ['table_plan.title', 'table_plan.year'], "key" => $key) : null;
        $fetch['order'] = array("field"=>"id","type"=>"DESC");

        return $this->_fetch($fetch);
    }
}
?>