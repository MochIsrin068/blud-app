<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_journal extends MY_Model
{
    /**
	 * Holds an array of tables used
	 *
	 * @var array
	 */
	protected $table = "table_journal";
	
	public function __construct()
	{
		parent::__construct( $this->table );
		parent::set_join_key( 'journal_id' );
        
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
	public function journal( $id = NULL  )
  	{
		$this->db->select( [
			$this->table.".*",
			"table_account.name as account_code",
      		"table_account.description as account_description",
		] );
		$this->db->join(
			"table_account",
			"table_account.id = table_journal.account_id",
			"inner"
		);
		if( isset( $id ) )
        {
			$this->db->where( $this->table.".id", $id );		
        }
		
		$this->db->limit( 1 );

		return $this->db->get( $this->table ) ;
	}
	/**
	 * journals
	 *
	 *
	 * @return db
	 * @author madukubah
	 */
	public function journals_limit( $year = NULL, $start = 0 , $limit = NULL )
	{
		$this->db->select( [
			$this->table.".*",
			"table_account.name as account_code",
      		"table_account.description as account_description",
		] );
		$this->db->join(
			"table_account",
			"table_account.id = table_journal.account_id",
			"inner"
		);
		//set limit / offset
        if( isset( $limit ) && isset( $start ) )
		{
			$this->db->limit( $limit , $start);
		}
		else if (isset( $limit ))
		{
			$this->db->limit( $limit );
		}
		$this->db->where( $this->table.".year", $year );		
		
		$this->db->order_by( $this->table.".date", "DESC");

		return $this->db->get( $this->table ) ;
	}
	/**
	 * journals
	 *
	 *
	 * @return db
	 * @author madukubah
	 */
	public function journals( $year , $account_ids = NULL, $start_date = NULL, $end_date = NULL  )
	{
		$this->db->select( [
			$this->table.".*",
			"table_account.name as account_code",
      		"table_account.description as account_description",
		] );
		$this->db->join(
			"table_account",
			"table_account.id = table_journal.account_id",
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

		if ( isset($start_date) && isset($end_date) )
		{
			$this->db->where( $this->table.".date >", $start_date );
			$this->db->where( $this->table.".date <", $end_date );
			  
		}
		$this->db->where( $this->table.".year", $year );		
		
		$this->db->order_by( $this->table.".date", "ASC");

		return $this->db->get( $this->table ) ;
	}

	/**
	 * sum_journals
	 *
	 *
	 * @return db
	 * @author madukubah
	 */

	public function sum_journalsperdate( $year , $account_ids = NULL, $month = NULL, $group_by = NULL , array $order_by, $date_start, $date_end )
	{
		$_group = array(
			'account_id' => $this->table.".account_id",
			'real_date' => "real_date",
			'id' => $this->table.".id",
			'account_prefix' => "account_prefix",
			'month' => $this->table.".month",
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
		

		$this->db->from( "
				(
					SELECT 
					a.* ,
					MONTH( a.unix_date )  as month 
					FROM (
						SELECT 
						b.* ,
						FROM_UNIXTIME( b.date)  as unix_date 
						FROM table_journal b
					) a 
				) 
				table_journal
		" );

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

		if ( isset($month)  )
		{
			$this->db->where_in( $this->table.".month",  $month );
			  
		}
		$this->db->where( $this->table.".year", $year );		
		$this->db->where("date BETWEEN $date_start AND $date_end");

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

	
	public function sum_journals( $year , $account_ids = NULL, $month = NULL, $group_by = NULL , array $order_by )
	{
		$_group = array(
			'account_id' => $this->table.".account_id",
			'real_date' => "real_date",
			'id' => $this->table.".id",
			'account_prefix' => "account_prefix",
			'month' => $this->table.".month",
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
		

		$this->db->from( "
				(
					SELECT 
					a.* ,
					MONTH( a.unix_date )  as month 
					FROM (
						SELECT 
						b.* ,
						FROM_UNIXTIME( b.date)  as unix_date 
						FROM table_journal b
					) a 
				) 
				table_journal
		" );

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

		if ( isset($month)  )
		{
			$this->db->where_in( $this->table.".month",  $month );
			  
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

	/**
	 * sum_journals
	 *
	 *
	 * @return db
	 * @author madukubah
	 */
	public function journals_by_month( $year  )
	{
		$this->db->select( [
			$this->table.".*",
		] );

		$this->db->from( "
				(
					SELECT 
					a.* ,
					MONTH( a.unix_date )  as month 
					FROM (
						SELECT 
						b.* ,
						FROM_UNIXTIME( b.date)  as unix_date 
						FROM table_journal b
					) a 
				) 
				table_journal
		" );

		$this->db->join(
			"table_account",
			"table_account.id = ".$this->table.".account_id",
			"inner"
		);
		$this->db->where( $this->table.".year", $year );
		$this->db->group_by( $this->table.".month" );	

		return $this->db->get( ) ;
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
	 * accounts
	 *
	 *
	 * @return static
	 * @author madukubah
	 */
	public function year_record_count( $year )
	{
		$this->db->select( [
			$this->table.".*",
		] );
		  
		$this->db->where( $this->table.".year", $year );		

		$this->db->order_by( $this->table.".year", "DESC");

		
		  return $this->db->count_all_results( $this->table ) ;
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