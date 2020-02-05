<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH . '/core/MY_Model_core.php';

class M_generalcash extends MY_Model_core
{
    function __construct() {
        parent::__construct( 'table_general_cash' );
        $this->load->model(array(
          'm_bookkeeping' ,
          'm_plan' ,
        ));
    }

    public function add( $data )
    {
        if( ! $this->m_plan->exist_by_time( $data['date'] ) ) return FALSE;

        $data = $this->_filter_data($this->table, $data);
        if( $this->m_bookkeeping->is_exist_by_time( $data['date'] ) )
        {
          return FALSE;
        }
        $this->db->insert( $this->table ,$data );

        $id = $this->db->insert_id( $this->table . '_id_seq');
      
        if( isset($id) )
        {
          return $id;
        }
        return FALSE;
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
      if( $this->m_bookkeeping->is_exist_by_time( $data['date'] ) )
      {
        return FALSE;
      }

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

    public function put_bookkeeping_id( $data, $data_param  )
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
        if (array_key_exists("id",$data_param) )
        {
            $general_cash = $this->general_cash(  $data_param['id'] )->row();
            if( $this->m_bookkeeping->is_exist_by_time( $general_cash->date ) )
            {
              return FALSE;
            }
        }

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
  

  public function general_cash(  $general_cash_id )
  {
      $fetch['select'][] = "*" ;
      $fetch['select_join'][]= "table_account.name as account_code ";
      $fetch['join'] = array(
        array(
          "table"	=>"table_account",
          "on"	=> "table_account.id = table_general_cash.account_id",
          "join"	=>"inner",
        ),
      );
      $fetch['where'][] = array( $this->table.".id" => $general_cash_id );
      $fetch['order'] = array("field"=>"id","type"=>"ASC");
      $fetch['limit'] = 1;
      return $this->_fetch($fetch);
  }
  public function general_cashes( $start = 0 , $limit = NULL  )
  {

    $fetch['select'][] = "*" ;
    $fetch['select_join'][]= "table_account.name as account_code ";
    $fetch['join'] = array(
      array(
        "table"	=>"table_account",
        "on"	=> "table_account.id = table_general_cash.account_id",
        "join"	=>"inner",
      ),
    );
    $fetch['order'] = array("field"=>"date","type"=>"DESC");
    $fetch['limit'] = $limit;
    $fetch['start'] = $start;

    return $this->_fetch($fetch);
  }

  public function last_general_cash( $date )
  {
    $year = date('Y', $date );
    $start_date = strtotime( "1/1/".$year." 00:00:01" );
		$end_date = strtotime( "12/31/".$year." 23:59:00");
      $this->db->select( [
        "table_general_cash.*",
        " LPAD( table_general_cash.serial_number , 3, 0) as serial_number",
        "table_account.name as account_code",
        "table_account.description as account_description",
      ] );
      $this->db->join(
        "table_account",
        "table_account.id = table_general_cash.account_id",
        "inner"
      );
      
      $this->db->where( $this->table.".date >", $start_date );
      $this->db->where( $this->table.".date <", $end_date );

      $this->db->order_by( $this->table.".date " , "ASC"); 
      $this->db->limit( 1); 

      return $this->db->get( $this->table );
  }

  public function general_cashes_account_ids( $account_ids = NULL, $start_date, $end_date, $group = TRUE  )
  {
      $this->db->select( [
        "table_general_cash.*",
        " LPAD( table_general_cash.serial_number , 3, 0) as serial_number",
        "SUM( table_general_cash.nominal ) total_nominal",
        "FROM_UNIXTIME(table_general_cash.date)  as real_date",
        "table_account.name as account_code",
        "table_account.description as account_description",
      ] );
      $this->db->join(
        "table_account",
        "table_account.id = table_general_cash.account_id",
        "inner"
      );
      if (isset($account_ids))
      {
        // build an array if only one group was passed
        if (!is_array($account_ids))
        {
          $account_ids = [$account_ids];
        }
        $this->db->where_in( $this->table.".account_id", $account_ids );
      }
      
      $this->db->where( $this->table.".date >", $start_date );
      $this->db->where( $this->table.".date <", $end_date );
      if ( $group )
      {
          $this->db->group_by("table_account.id"); 
      }
      $this->db->order_by("table_account.name", "DESC"); 

      return $this->db->get( $this->table );
  }

  public function monthly_general_cashes( $year, $month )
  {
    $start_date = strtotime( $month."/1/".$year." 00:00:01" );
		$end_date = strtotime( $month."/31/".$year." 23:59:00");
      $this->db->select( [
        "table_general_cash.*",
        " LPAD( table_general_cash.serial_number , 3, 0) as serial_number",
        // "SUM( table_general_cash.nominal ) total_nominal",
        "table_account.name as account_code",
        "table_account.description as account_description",
      ] );
      $this->db->join(
        "table_account",
        "table_account.id = table_general_cash.account_id",
        "inner"
      );
      if (isset($account_ids))
      {
        // build an array if only one group was passed
        if (!is_array($account_ids))
        {
          $account_ids = [$account_ids];
        }
        $this->db->where_in( $this->table.".account_id", $account_ids );
      }
      
      $this->db->where( $this->table.".date >", $start_date );
      $this->db->where( $this->table.".date <", $end_date );
      
      // $this->db->order_by("table_account.name", "ASC"); 
      $this->db->order_by( $this->table.".date " , "ASC"); 

      return $this->db->get( $this->table );
  }
  public function general_cashes_periods( $start_date, $end_date )
  {
    // $start_date = strtotime( $month."/1/".$year." 00:00:01" );
		// $end_date = strtotime( $month."/31/".$year." 23:59:00");
      $this->db->select( [
        "table_general_cash.*",
        " LPAD( table_general_cash.serial_number , 3, 0) as serial_number",
        // "SUM( table_general_cash.nominal ) total_nominal",
        "table_account.name as account_code",
        "table_account.description as account_description",
      ] );
      $this->db->join(
        "table_account",
        "table_account.id = table_general_cash.account_id",
        "inner"
      );
      
      $this->db->where( $this->table.".date >", $start_date );
      $this->db->where( $this->table.".date <", $end_date );
      
          // $this->db->group_by("table_account.id"); 

      $this->db->order_by("table_general_cash.date", "ASC"); 

      return $this->db->get( $this->table );
  }

  public function general_cashes_by_bookkeeping_id( $bookkeeping_id )
  {
      $this->db->select( [
        "table_general_cash.*",
        " LPAD( table_general_cash.serial_number , 3, 0) as serial_number",
        // "SUM( table_general_cash.nominal ) total_nominal",
        "table_account.name as account_code",
        "table_account.description as account_description",
      ] );
      $this->db->join(
        "table_account",
        "table_account.id = table_general_cash.account_id",
        "inner"
      );
      
      $this->db->where( $this->table.".bookkeeping_id", $bookkeeping_id );

      $this->db->order_by( $this->table.".date " , "ASC"); 

      return $this->db->get( $this->table );
  }

  public function search( $key )
    {
        $fetch['select'][] = "*" ;
        $fetch['select_join'][]= "table_account.name as account_code ";
        $fetch['select_join'][]= "LPAD( table_general_cash.serial_number , 3, 0) as serial_number";
        $fetch['join'] = array(
          array(
            "table"	=>"table_account",
            "on"	=> "table_account.id = table_general_cash.account_id",
            "join"	=>"inner",
          ),
        );

        $fetch['like'] = ($key!=null) ? array("name" => ['table_account.name', "table_general_cash.description"], "key" => $key) : null;
        $fetch['order'] = array("field"=>"id","type"=>"DESC");

        return $this->_fetch($fetch);
    }

}
?>
