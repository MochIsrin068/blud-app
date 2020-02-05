<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH . '/core/MY_Model_core.php';

class M_realization extends MY_Model_core
{
    function __construct() {
        parent::__construct( 'table_realization' );
        $this->set_join_key( 'realization_id' );
    }

    public function add($data){
      
      $data = $this->_filter_data($this->table, $data);
      if( $this->is_exist( $data['plan_id']  ) )
      {
          $_data_param['plan_id'] = $data['plan_id'];
  
          return $this->update( $data, $_data_param  );
      }
      $this->db->insert( $this->table ,$data );

      $id = $this->db->insert_id( $this->table . '_id_seq');
		
      if( isset($id) )
      {
        return $id;
      }
      return FALSE;
    }

    public function is_exist( $plan_id   )
    {
      $this->db->select( [
        $this->table.".*",
      ] );
      
      $this->db->where( $this->table.".plan_id ", $plan_id );
    
      return $this->db->count_all_results( $this->table ) > 0;
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
    public function delete( $data_param  )
    { 

          //delete_foreign( $data_param, $models[]  )
          if( !$this->delete_foreign( $data_param, ['m_budget_realization']  ) )
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

    public function realization(  $realization_id )
    {
        $fetch['select'][] = "*" ;
        $fetch['select_join'][]= "table_plan.year as year";
        $fetch['select_join'][]= "table_plan.description as description";
        $fetch['join'] = array(
          array(
            "table"	=>"table_plan",
            "on"	=> "table_plan.id = table_realization.plan_id",
            "join"	=>"inner",
          ),
        );
        $fetch['where'][] = [ $this->table.".id" => $realization_id ] ;
        $fetch['order'] = array("field"=>"id","type"=>"DESC");
        $fetch['limit'] = 1;
        return $this->_fetch($fetch);
    }

    public function realization_by_plan_id(  $plan_id )
    {
        $fetch['select'][] = "*" ;
        $fetch['where'][] = [ $this->table.".plan_id" => $plan_id ] ;
        $fetch['order'] = array("field"=>"id","type"=>"DESC");
        $fetch['limit'] = 1;
        return $this->_fetch($fetch);
    }


    public function realizations( $start = 0 , $limit = NULL )
    {
        $fetch['select'][] = "*" ;
        $fetch['select_join'][]= "table_plan.year as year";
        $fetch['select_join'][]= "table_plan.description as description";
        $fetch['join'] = array(
          array(
            "table"	=>"table_plan",
            "on"	=> "table_plan.id = table_realization.plan_id",
            "join"	=>"inner",
          ),
        );
        $fetch['order'] = array("field"=>"id","type"=>"DESC");
        $fetch['limit'] = $limit;
        $fetch['start'] = $start;

        return $this->_fetch($fetch);
    }

    public function search( $key )
    {
        $fetch['select'][] = "*" ;
        $fetch['like'] = ($key!=null) ? array("name" => ['table_realization.title'], "key" => $key) : null;
        $fetch['order'] = array("field"=>"id","type"=>"DESC");

        return $this->_fetch($fetch);
    }
   
}
?>