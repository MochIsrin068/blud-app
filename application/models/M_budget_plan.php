<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH . '/core/MY_Model_core.php';

class M_budget_plan extends MY_Model_core
{
    function __construct() {
        parent::__construct( 'table_budget_plan' );
    }

    public function add( $data )
    {
        $data = $this->_filter_data($this->table, $data);
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

    public function budget_plan(  $budget_plan_id )
    {
        $fetch['select'][] = "*" ;
        $fetch['select_join'][]= "table_account.name as account_code ";
        $fetch['select_join'][]= "table_account.description as account_description ";        
        $fetch['join'] = array(
          array(
            "table"	=>"table_account",
            "on"	=> "table_account.id = table_budget_plan.account_id",
            "join"	=>"inner",
          ),
        );
        $fetch['where'][] =[  $this->table.".id" => $budget_plan_id ];
        $fetch['order'] = array("field"=>"id","type"=>"ASC");
        $fetch['limit'] = 1;
        return $this->_fetch($fetch);
    }

    public function budget_plans( $plan_id = NULL  )
    {

      $fetch['select'][] = "*" ;
      $fetch['select_join'][]= "table_account.name as account_code ";
      $fetch['select_join'][]= "table_account.description as account_description ";
      $fetch['join'] = array(
        array(
          "table"	=>"table_account",
          "on"	=> "table_account.id = table_budget_plan.account_id",
          "join"	=>"inner",
        ),
      );
      if(isset( $plan_id ))
      { 
        
         $fetch['where'][] = [ $this->table.".plan_id" => $plan_id ] ;
        //  echo var_dump( $fetch['where'] ).'<br>';
      }
      $fetch['order'] = array("field"=>"id","type"=>"ASC");

      return $this->_fetch($fetch);
  }

  public function budget_plans_by_account_ids( $plan_id = NULL, $account_ids = NULL )
  {
    $this->db->select( [
      $this->table.".*",
      "table_account.name as account_code",
      "table_account.description as account_description",
    ] );
    $this->db->join(
      "table_account",
      "table_account.id = table_budget_plan.account_id",
      "inner"
    );
    // filter by group id(s) if passed
		if (isset($account_ids))
		{
			// build an array if only one group was passed
			if (!is_array($account_ids))
			{
				$account_ids = [$account_ids];
			}
			$this->db->where_in( $this->table.".account_id", $account_ids );
		}

    $this->db->where( $this->table.".plan_id", $plan_id );
    return $this->db->get( $this->table );
  }

  public function budget_plans_total_price( $plan_id = NULL, $account_ids = NULL, $group = TRUE )
  {
    $this->db->select( [
      "table_account.id as account_id",
      "SUM( table_budget_plan.price ) total_price",
      "table_account.name as account_code",
      "table_account.description as account_description",
    ] );
    $this->db->join(
      "table_account",
      "table_account.id = table_budget_plan.account_id",
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

    $this->db->where( $this->table.".plan_id", $plan_id );
    if ( $group )
		{
        $this->db->group_by("table_budget_plan.account_id"); 
    }
    $this->db->order_by("table_account.name", "ASC"); 
    return $this->db->get( $this->table );
  }

  /**
	 * sum_balance
	 *
	 *
	 * @return db
	 * @author madukubah
	 */
	public function sum_budget_plan( $year , $account_ids = NULL, $month = NULL, $group_by = NULL , array $order_by )
	{
		$_group = array(
			'account_id' => $this->table.".account_id",
			'id' => $this->table.".id",
			'account_prefix' => "account_prefix",
		);
		$this->db->select( [
			$this->table.".*",
			"SUM( ".$this->table.".price ) price",
			"SUM( ".$this->table.".price ) nominal",
			"LEFT( table_account.description, 3 ) as account_prefix",
			"table_account.name as account_code",
			"table_account.id as account_id",
      		"table_account.description as account_description",
		] );
		

		$this->db->from( $this->table );

		$this->db->join(
			"table_account",
			"table_account.id = ".$this->table.".account_id",
			"inner"
    );
    $this->db->join(
			"table_plan",
			"table_plan.id = ".$this->table.".plan_id",
			"inner"
		);
		 // filter by group id(s) if passed
		if (isset($account_ids))
		{
			// build an array if only one group was passed
			if (!is_array($account_ids))
			{
				$account_ids = [$account_ids];
			}
			$this->db->where_in( $this->table.".account_id", $account_ids );
		}

		$this->db->where( "table_plan.year", $year );		
		
		if ( isset( $group_by ) )
		{
			foreach( $group_by as $group )
			{
				$this->db->group_by( $_group[ $group ] );	
			}
		}

		$this->db->order_by( $_group[ $order_by[0] ], $order_by[1] );

		return $this->db->get( ) ;
	}

}
?>
