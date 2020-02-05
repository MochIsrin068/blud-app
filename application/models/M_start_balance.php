<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_start_balance extends MY_Model
{
    /**
	 * Holds an array of tables used
	 *
	 * @var array
	 */
	protected $table = "table_start_balance";
	
	public function __construct()
	{
		parent::__construct( $this->table );
		parent::set_join_key( 'start_balance_id' );
        
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
		if( $this->is_exist( $data['account_id'], $data['year'] ) )
		{
			$data_param['account_id'] = $data['account_id'];
			$data_param['year'] = $data['year'];

			$this->update( $data, $data_param  );
			return TRUE;
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
		if( !$this->delete_foreign( $data_param ) )
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

	/**
	 * account
	 *
	 * @param int|array|null $id = id_accounts
	 * @return static
	 * @author madukubah
	 */
	public function start_balance( $id = NULL  )
  	{
		if (isset($id))
		{
			$this->where($this->table.'.id', $id);
        }

		$this->limit(1);
        $this->order_by($this->table.'.id', 'desc');

		$this->accounts(  );

		return $this;
	}
	/**
	 * accounts
	 *
	 *
	 * @return static
	 * @author madukubah
	 */
	public function start_balances_by_account_ids( $year = NULL, $account_ids = NULL )
	{
		$this->db->select( [
			$this->table.".*",
			"table_account.name as account_code",
      		"table_account.description as account_description",
		] );
		$this->db->join(
			"table_account",
			"table_account.id = table_start_balance.account_id",
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
		$this->db->where( $this->table.".year", $year );		
		
		$this->db->order_by( $this->table.".date", "ASC");

		return $this->db->get( $this->table ) ;
	}
	/**
	 * accounts
	 *
	 *
	 * @return static
	 * @author madukubah
	 */
	public function is_exist( $account_id, $year )
    {
      $this->db->select( [
        $this->table.".*",
      ] );
      
      $this->db->where( $this->table.".account_id", $account_id );		
      $this->db->where( $this->table.".year", $year );		
    
      return $this->db->count_all_results( $this->table ) > 0;
    }

	/**
	 * accounts
	 *
	 *
	 * @return static
	 * @author madukubah
	 */
	public function get_years( $start = 0 , $limit = NULL )
	{
		$this->db->select( [
			$this->table.".*",
		] );
		  
		$this->db->group_by(  $this->table.".year"); 

		if (isset( $limit ))
		{
			$this->db->limit( $limit );
		}
		$this->db->order_by( $this->table.".year", "DESC");

		
		  return $this->db->get( $this->table ) ;
	}

	/**
	 * accounts
	 *
	 *
	 * @return static
	 * @author madukubah
	 */
	public function record_count(  )
	{
		$this->db->select( [
			$this->table.".*",
		] );
		  
		$this->db->group_by(  $this->table.".year"); 

		if (isset( $limit ))
		{
			$this->db->limit( $limit );
		}
		$this->db->order_by( $this->table.".year", "DESC");

		
		  return $this->db->count_all_results( $this->table ) ;
	}

	/**
	 * sum_balance
	 *
	 *
	 * @return db
	 * @author madukubah
	 */
	public function sum_balance( $year , $account_ids = NULL, $month = NULL, $group_by = NULL , array $order_by )
	{
		$_group = array(
			'account_id' => $this->table.".account_id",
			'real_date' => "real_date",
			'id' => $this->table.".id",
			'account_prefix' => "account_prefix",
		);
		$this->db->select( [
			$this->table.".*",
			"SUM( ".$this->table.".nominal ) nominal",
			"FROM_UNIXTIME(".$this->table.".date)  as real_date",
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

		$this->db->where( $this->table.".year", $year );		
		
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