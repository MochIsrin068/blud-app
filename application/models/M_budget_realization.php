<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_budget_realization extends MY_Model
{
    /**
	 * Holds an array of tables used
	 *
	 * @var array
	 */
	protected $table = "table_budget_realization";
	
	public function __construct()
	{
		parent::__construct( $this->table );
		parent::set_join_key( 'budget_realization_id' );
        
	}
	/**
	 * create
	 *
	 * @param array  $data
	 * @return static
	 * @author madukubah
	 */
	public function create( $data )
  	{
		// Filter the data passed
        $data = $this->_filter_data($this->table, $data);
		if( $this->is_exist( $data['realization_id'], $data['month'], $data['account_id']  ) )
		{
			$_data_param['realization_id'] = $data['realization_id'];
			$_data_param['month'] = $data['month'];
			$_data_param['account_id'] = $data['account_id'];

			$this->update( $data, $_data_param  );
			return TRUE ;
		}
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id($this->table . '_id_seq');
		
		if( isset($id) )
		{
			$this->set_message("berhasil");//('account_creation_successful');
			return $id;
		}
		$this->set_error("gagal");//('account_creation_unsuccessful');
        return FALSE;
	}

	public function add_batch( $rows ) {
		foreach( $rows as $row )
		{
			$this->create( (array) $row );
		}
        return true;
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

			$this->set_error("gagal");//('account_update_unsuccessful');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->set_message("berhasil");//('account_update_successful');
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
		//foreign
		//delete_foreign( $data_param. $models[]  )
		if( !$this->delete_foreign( $data_param  ) )
		{
			$this->set_error("gagal");//('account_delete_unsuccessful');
			return FALSE;
		}
		//foreign
		$this->db->trans_begin();

		$this->db->delete($this->table, $data_param );
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();

			$this->set_error("gagal");//('account_delete_unsuccessful');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->set_message("berhasil");//('account_delete_successful');
		return TRUE;
	}

	public function budget_realization( $account_ids = NULL,  $realization_id , $month  )
	{
		$this->db->select( [
			$this->table.".*",
			"( table_budget_realization.this_month + table_budget_realization.last_month ) as until_this_month",
			"ABS( table_budget_realization.dpa_budget - table_budget_realization.this_month - table_budget_realization.last_month ) as difference",

			"table_account.name as account_code",
			"table_account.description as account_description",
		] );
		$this->db->join(
		  "table_account",
		  "table_account.id = table_budget_realization.account_id",
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
		
		$this->db->where( $this->table.".realization_id ", $realization_id );
		$this->db->where( $this->table.".month ", $month );
	
		$this->db->order_by("table_account.name", "ASC"); 
  
		return $this->db->get( $this->table );
	}

	public function monthly_budget_realization( $realization_id )
	{
		$this->db->select( [
			$this->table.".*",
		] );
		
		$this->db->where( $this->table.".realization_id ", $realization_id );
		$this->db->group_by( $this->table.".month"); 
  
		return $this->db->get( $this->table );
	}
	// is_monthly_exist
	/**
	 * @param string $message The message
	 *
	 * @return string The given message
	 * @author Ben Edmunds
	 */
	public function is_monthly_exist( $month, $realization_id )
	{
		$this->db->select( [
			$this->table.".month",
		] );
		
		$this->db->where( $this->table.".month", $month );
		$this->db->where( $this->table.".realization_id", $realization_id );
		$this->db->group_by( $this->table.".month"); 
  
		return $this->db->count_all_results( $this->table )  > 0 ;
	}

	public function is_exist( $realization_id,  $month, $account_id   )
	{
		$this->db->select( [
			$this->table.".*",
		] );
		
		$this->db->where( $this->table.".month", $month );		
		$this->db->where( $this->table.".realization_id ", $realization_id );
		$this->db->where( $this->table.".account_id ", $account_id );
  
		return $this->db->count_all_results( $this->table ) > 0;
	}

	// MASSAGES AND ERROR
	/**
	 * set_message("berhasil");//
	 *
	 * Set a message
	 *
	 * @param string $message The message
	 *
	 * @return string The given message
	 * @author Ben Edmunds
	 */
	public function set_message($message)
	{
		$messageLang = $this->lang->line($message) ? $this->lang->line($message) : $message ;
		parent::set_message( $messageLang );
	}
	/**
	 * set_error("gagal");//
	 *
	 * Set an error message
	 *
	 * @param string $error The error to set
	 *
	 * @return string The given error
	 * @author Ben Edmunds
	 */
	public function set_error( $error )
	{
		$errorLang = $this->lang->line($error) ? $this->lang->line($error) :  $error ;
		parent::set_error( $errorLang );
	}
}