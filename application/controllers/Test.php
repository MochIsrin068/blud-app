return;
		$this->services->set_budget_plans_total_price( $plan->id );//menset data DPA dahulu
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$pendapatan_budget_plans = $this->services->get_pendapatan_budget_plans();
		$pendapatan_generalcash =  $this->m_generalcash->general_cashes_account_ids( $pendapatan_budget_plans->account_ids ,$start_date, $end_date  )->result() ;
		$lra_pendapatan = $this->services->sync_realization( $pendapatan_budget_plans->budget_plans , $pendapatan_generalcash );
		array_push( $lra_pendapatan, $this->services->get_sum($lra_pendapatan) );
		$table = $this->services->get_lra_table_config(  );
		$table[ "rows" ] = $lra_pendapatan;
		$this->data[ "lra_pendapatan_table" ] = $this->load->view('templates/tables/LRA_table', $table, true);
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$pegawai_budget_plans = $this->services->get_pegawai_budget_plans();
		$pegawai_generalcash =  $this->m_generalcash->general_cashes_account_ids( $pegawai_budget_plans->account_ids ,$start_date, $end_date  )->result() ;
		$lra_pegawai = $this->services->sync_realization( $pegawai_budget_plans->budget_plans , $pegawai_generalcash );
		array_push( $lra_pegawai, $this->services->get_sum($lra_pegawai) );
		$table = $this->services->get_lra_table_config(  );
		$table[ "rows" ] = $lra_pegawai;
		$this->data[ "lra_pegawai_table" ] = $this->load->view('templates/tables/LRA_table', $table, true);
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$barang_budget_plans = $this->services->get_barang_budget_plans();
		$barang_generalcash =  $this->m_generalcash->general_cashes_account_ids( $barang_budget_plans->account_ids ,$start_date, $end_date  )->result() ;

		
		$lra_barang = $this->services->sync_realization( $barang_budget_plans->budget_plans , $barang_generalcash );
		// $this->m_budget_realization->add_batch( $lra_barang );

		array_push( $lra_barang, $this->services->get_sum($lra_barang) );
		$table = $this->services->get_lra_table_config(  );
		$table[ "rows" ] = $lra_barang;
		$this->data[ "lra_barang_table" ] = $this->load->view('templates/tables/LRA_table', $table, true);
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$this->render( "finance/report/LRA" );