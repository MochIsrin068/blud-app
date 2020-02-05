<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_account extends MY_Model
{
    /**
	 * Holds an array of tables used
	 *
	 * @var array
	 */
	protected $table = "table_account";
	protected $account_list = array();
	protected $account_ids_list = array();

	protected $account_start_fetch = 0;
	protected $account_depth = 5;
	protected $MAX_DEPTH = 5;
	
	public function __construct()
	{
		parent::__construct( $this->table );
		parent::set_join_key( 'account_id' );
        
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
	 * tree
	 *
	 * @param int  $account_id
	 * @return tree array
	 * @author madukubah
	 */
	public function tree( $account_id = 0, $depth = 0  )
  	{	
		if( $depth == $this->account_depth )
		{
			return array();
		}
		$tree = $this->accounts( $account_id )->result();
		if( empty( $tree ) )
		{
			return array();
		}
		foreach( $tree as $branch )
		{
			// echo $depth.' '.$this->account_start_fetch .'<br>';
			// echo ( $depth == $this->account_start_fetch  ).'<br>';
			if( $depth >= $this->account_start_fetch -1  )
			// if( true )
			{

				$data_point = (object) array(
					'id' =>$branch->id,
					'code' =>$branch->code,
					'name' =>$branch->name,
					'description' =>$branch->description,
				);
				$this->account_list[] = $data_point;	
				$this->account_ids_list[] = $data_point->id;	
			}
			$branch->branch = $this->tree( $branch->id , $depth + 1 );
		}

		return $tree;
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
		if( !$this->delete_foreign( $data_param, ['m_account','m_budget_realization', 'm_budget_plan','m_generalcash']  ) )
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
	public function account( $id = NULL  )
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
	public function accounts( $account_id = NULL )
	{
		if( isset( $account_id ) )
		{
			$this->where($this->table.'.account_id', $account_id);
		}
		$this->order_by($this->table.'.id', 'asc');
		return $this->fetch_data();
	}
	/**
	 * accounts
	 *
	 *
	 * @return static
	 * @author madukubah
	 */
	public function accounts_by_name( $names = NULL )
	{
		if (isset($names))
		{
			// build an array if only one group was passed
			if (!is_array($names))
			{
				$names = [$names];
			}
			foreach( $names as $name )
			{
				$this->like($this->table.'.name', $name, 'after');
				// $this->like($this->table.'.name', $name, 'none');
			}
		}
		$this->order_by($this->table.'.id', 'asc');
		return $this->fetch_data();
	}
	/**
	 * get_end_point
	 *
	 *
	 * @return static
	 * @author madukubah
	 */
	public function get_end_point( )
	{
		$_account_list = array();
		$this->get_pendapatan_end_point( );
		$_account_list = $this->account_list;
		$this->get_belanja_end_point( );
		$_account_list = array_merge( $_account_list, $this->account_list );
		$this->get_belanja_end_point(  ['7'] , 2 );
		$_account_list = array_merge( $_account_list, $this->account_list );
		return $_account_list;
	}
	/**
	 * get_start_balance_end_point
	 *
	 *
	 * @return static
	 * @author madukubah
	 */
	public function get_start_balance_end_point( $account_code , $account_start_fetch = 1 )
	{
		$this->account_list = array();
		$this->account_ids_list = array();
		$end_points = $this->accounts_by_name( $account_code )->result();
		// $list = array();
		foreach( $end_points as $end_point )
		{
			$data_point = (object) array(
				'id' =>$end_point->id,
				'code' =>$end_point->code,
				'name' =>$end_point->name,
				'description' =>$end_point->description,
			);
			$this->account_ids_list[] = $end_point->id;
			$this->account_depth = $this->MAX_DEPTH;
			$this->account_start_fetch = $account_start_fetch ; 
			$end_point->branch = $this->tree( $end_point->id  );
		}
		// $this->account_ids_list[]=
		// echo var_dump
		return $end_points;
	}
	/**
	 * get_pendapatan_end_point
	 *
	 *
	 * @return static
	 * @author madukubah
	 */
	public function get_pendapatan_end_point( $account_code = ['4', '1', '9.2.1'], $account_start_fetch = 1 )
	{
		$this->account_list = array();
		$this->account_ids_list = array();
		$end_points = $this->accounts_by_name( $account_code )->result();
		// $list = array();
		foreach( $end_points as $end_point )
		{
			$data_point = (object) array(
				'id' =>$end_point->id,
				'code' =>$end_point->code,
				'name' =>$end_point->name,
				'description' =>$end_point->description,
			);
			// $this->account_list[] = $data_point;
			$this->account_depth = $this->MAX_DEPTH;
			$this->account_start_fetch = $account_start_fetch ; 
			$end_point->branch = $this->tree( $end_point->id  );
		}
		return $end_points;
	}
	/**
	 * get_belanja_end_point
	 *
	 *
	 * @return static
	 * @author madukubah
	 */
	public function get_belanja_end_point( $account_code = [ '5' ], $account_start_fetch = 4 )
	{
		$this->account_list = array();
		$this->account_ids_list = array();
		$end_points = $this->accounts_by_name( $account_code )->result();
		// $list = array();
		foreach( $end_points as $end_point )
		{
			$data_point = (object) array(
				'id' =>$end_point->id,
				'code' =>$end_point->code,
				'name' =>$end_point->name,
				'description' =>$end_point->description,
			);
			// $this->account_list[] = $data_point;
			$this->account_depth = $this->MAX_DEPTH;
			$this->account_start_fetch = $account_start_fetch ; // mengambil akun pada kedalaman yang ditentukan
			$end_point->branch = $this->tree( $end_point->id  );
		}
		return $end_points;
	}

	public function get_account_list( )
	{
		return $this->account_list;
	}
	public function get_account_ids_list( )
	{
		return $this->account_ids_list;
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