<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH . '/core/MY_Model_core.php';

class M_bookkeeping extends MY_Model_core
{
    function __construct() {
        parent::__construct( 'table_bookkeeping' );
        $this->set_join_key( 'bookkeeping_id' );
        
    }

    public function add( $data )
    {
        $data = $this->_filter_data($this->table, $data);
        if( $this->is_exist( $data['plan_id'], $data['month'] ) )
        {
          $_data_param['plan_id'] = $data['plan_id'];
          $_data_param['month'] = $data['month'];

          $this->update( $data, $_data_param  );
          $bookkeeping = $this->bookkeeping_by_plan_id( $data['plan_id'], $data['month'] )->row();
          return $bookkeeping->id ;
        }
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
        if( !$this->delete_foreign( $data_param  ) )
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

    public function is_exist( $plan_id,  $month )
    {
      $this->db->select( [
        $this->table.".*",
      ] );
      
      $this->db->where( $this->table.".month", $month );		
      $this->db->where( $this->table.".plan_id ", $plan_id );
    
      return $this->db->count_all_results( $this->table ) > 0;
    }

    public function is_exist_by_time( $time )
    {
      $year = date('Y', $time);
      $month = date('m', $time);

      $start_date = strtotime( $month."/1/".$year." 00:00:01" );
      $end_date = strtotime( $month."/31/".$year." 23:59:00");
      

      $this->db->select( [
        $this->table.".*",
      ] );
      
      $this->db->where( $this->table.".date >", $start_date );
      $this->db->where( $this->table.".date <", $end_date );
      $this->db->where( $this->table.".status ", 1 );
    
      return $this->db->count_all_results( $this->table ) > 0;
    }

    public function bookkeeping_by_time( $time )
    {
      $year = date('Y', $time);
      $month = date('m', $time);

      $start_date = strtotime( $month."/1/".$year." 00:00:01" );
      $end_date = strtotime( $month."/31/".$year." 23:59:00");

      $this->db->select( [
        $this->table.".*",
        " ( table_bookkeeping.debit - table_bookkeeping.credit ) as closing_balance ",
      ] );
      
      $this->db->where( $this->table.".date >", $start_date );
      $this->db->where( $this->table.".date <", $end_date );
      // $this->db->where( $this->table.".status ", 1 );
    
      return $this->db->get( $this->table ) ;
    }
    
    public function bookkeeping_by_plan_id( $plan_id,  $month )
    {
      $this->db->select( [
        $this->table.".*",
        " ( table_bookkeeping.debit - table_bookkeeping.credit ) as closing_balance ",
      ] );
      
      $this->db->where( $this->table.".month", $month );		
      $this->db->where( $this->table.".plan_id ", $plan_id );
    
      return $this->db->get( $this->table );
    }

    public function bookkeepings( $plan_id = NULL  )
    {

      $fetch['select'][] = "*" ;
      $fetch['select_join'][]= "table_plan.year as year ";
      $fetch['select_join'][]= "( table_bookkeeping.debit - table_bookkeeping.credit ) as closing_balance ";
      $fetch['join'] = array(
        array(
          "table"	=>"table_plan",
          "on"	=> "table_plan.id = table_bookkeeping.plan_id",
          "join"	=>"inner",
        ),
      );

      $fetch['where'][] = [ $this->table.".plan_id" => $plan_id ] ;
      $fetch['order'] = array("field"=>"month","type"=>"ASC");

      return $this->_fetch($fetch);
    }

    public function bookkeeping( $id = NULL  )
    {

      $fetch['select'][] = "*" ;
      $fetch['select_join'][]= "table_plan.year as year ";
      $fetch['select_join'][]= "( table_bookkeeping.debit - table_bookkeeping.credit ) as closing_balance ";
      $fetch['join'] = array(
        array(
          "table"	=>"table_plan",
          "on"	=> "table_plan.id = table_bookkeeping.plan_id",
          "join"	=>"inner",
        ),
      );

      $fetch['where'][] = [ $this->table.".id" => $id ] ;
      $fetch['order'] = array("field"=>"month","type"=>"ASC");
      $fetch['limit'] = 1;

      return $this->_fetch($fetch);
    }

}
?>
