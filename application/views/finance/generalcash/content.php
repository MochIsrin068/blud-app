<section class="content">
	<!-- <div class="container-fluid"> -->
		<div class="block-header">
			<h2><?php echo $block_header ?></h2>
		</div>
        <div class="row clearfix">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="card">
                  <div class="header">
                      <div class="row clearfix">
                          <div class="col-md-12">
                                <!-- alert  -->
                                <?php
                                    echo $alert;
                                ?>
                                <!-- alert  -->
                          </div>
                      </div>
                      <!--  -->
                      <div class="row clearfix" style="margin-bottom:-30px">
                        <div class="col-md-6">
                          <h2>
                              <?php echo strtoupper($header)?>
                              <small><?php echo $sub_header ?></small>
                          </h2>
                        </div>
                        <!-- search form -->
                        <div class="col-md-6">
                          <div class="row clearfix">
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-10">
                                <form method="get" action="<?php echo site_url($current_page)?>">
                                    <div class="row clearfix">
                                        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12" style="margin-bottom:0px!important">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" name="key" required class="form-control" placeholder="Pencarian" value="<?php echo ($key) ? $key : '' ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-4 col-sm-12 col-xs-12">
                                            <div class="btn-group">
                                                <button type="submit" class="btn btn-warning btn-md m-l-15 waves-effect"><i class="material-icons">search</i></button>
                                                <a href="<?php echo site_url($current_page); ?>" type="button"  class="btn btn-warning btn-md m-l-15 waves-effect"><i class="material-icons">refresh</i></a>
                                            </div>
                                            <a href="<?php echo site_url($current_page.'create')?>" class=" pull-right btn btn-lg btn-primary">Buat Transaksi</a>
                                        </div>
                                    </div>
                                </form>
                                <!--  -->
                            </div>

                          </div>
                        </div>
                        <!--  -->
                      </div>
                      <!--  -->
                  </div>
                  <div class="body">
                      <!--  -->
                      <?php echo $table?>
                      <!--  -->
                      <!--  -->
                      <?php echo ( isset( $links )  ) ? $links : '' ;  ?>
                      <!--  -->
                  </div>
              </div>
          </div>
      </div>
	<!-- </div> -->
</section>
<?php
    function generate_receipt( $data = NULL, $account )
	{	
		// $data = array(
		// 	'description' => 'bayar biaya rekening air PDAM kantor BLUD bulan OKtober 2018 sesuai bukti terlampir',
		// 	'nominal' => 77000,
		// 	'date' => 1543446000,
		// 	'spelled_out' => 'TUJUH PULUH RIBU RUPiah',
		// 	'account_id' => 308,
		// );
		// $account = $this->m_account->account( $data['account_id'] )->row();
		// echo var_dump( $account );return;
		// $data['account_name'] = $account->name ;
		// $data = $data;

		// ////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // // $this->load->library('pdf');
        // // C:/xampp/htdocs/blud/application/libraries/Pdf.php
        // require_once APPPATH."/libraries/Pdf.php"; 
		// $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);

		// $pdf->SetTitle('Berita Acara');
		
		// $pdf->setPrintHeader(false);
		// $pdf->setPrintFooter(false);
		 
		// $pdf->SetTopMargin(10);
		// $pdf->SetLeftMargin(10);
		// $pdf->SetRightMargin(10);
		// //$pdf->setFooterMargin(20);
		// $pdf->SetAutoPageBreak(true);
		// $pdf->SetAuthor('BLUD');
		// $pdf->SetDisplayMode('real', 'default');
		// $pdf->AddPage();
		// $pdf->SetFont('times', NULL, 9);

		
		// $html = $this->load->view('templates/report/receipt', $data, true);	

		// // return;

		// $pdf->writeHTML($html, true, false, true, false, '');
		// $pdf->Output("KUITANSI ".$account->name.date('d-m-Y', $data['date']).".pdf",'D');
	}
// if( $this->session->flashdata('kuitansi') )
// {
//     $data = $this->session->flashdata('kuitansi');
//     $account = $this->m_account->account( $data['account_id'] )->row();
//     // generate_receipt( $data, $account );
//     $data['account_name'] = $account->name ;
//     // $data = $data;

//     ////////////////////////////////////////////////////////////////////////////////////////////////////////////
//     $this->load->library('pdf');
//     // C:/xampp/htdocs/blud/application/libraries/Pdf.php
//     // require_once APPPATH."/libraries/Pdf.php"; 
//     $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);

//     $pdf->SetTitle('Berita Acara');
    
//     $pdf->setPrintHeader(false);
//     $pdf->setPrintFooter(false);
        
//     $pdf->SetTopMargin(10);
//     $pdf->SetLeftMargin(10);
//     $pdf->SetRightMargin(10);
//     //$pdf->setFooterMargin(20);
//     $pdf->SetAutoPageBreak(true);
//     $pdf->SetAuthor('BLUD');
//     $pdf->SetDisplayMode('real', 'default');
//     $pdf->AddPage();
//     $pdf->SetFont('times', NULL, 9);

    
//     $html = $this->load->view('templates/report/receipt', $data, true);	

//     // return;

//     $pdf->writeHTML($html, true, false, true, false, '');
//     $pdf->Output("KUITANSI ".$account->name.date('d-m-Y', $data['date']).".pdf",'D');
// }
?>
