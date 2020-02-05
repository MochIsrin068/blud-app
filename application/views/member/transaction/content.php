<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<h2><?php echo $block_header ?></h2>
		</div>
        <div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
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

                            <div class="row clearfix">
                              <select class="form-control show-tick" id="limit" name="" >
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                              </select>
                            </div>

                        </div>
                        <div class="col-md-10">
                            <div class="row clearfix">
                                <form method="get" action="<?php echo site_url($current_page)?>">
                                <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12" style="margin-bottom:0px!important">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="key" required class="form-control" placeholder="Pencarian" value="<?php echo ($key) ? $key : '' ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-4 col-sm-12 col-xs-12">
                                  <div class="btn-group">
                                    <button type="submit" class="btn btn-warning btn-sm m-l-15 waves-effect"><i class="material-icons">search</i></button>
                                    <a href="<?php echo site_url($current_page); ?>" type="button"  class="btn btn-warning btn-sm m-l-15 waves-effect"><i class="material-icons">refresh</i></a>
                                   </form>
                                     <?php //echo $model_form_add ?> 
                                    <!-- //////////////////////////////////////////////////////////////////////////////////////////// -->
                                    
                                   
                                    <!--  -->
                                    <!-- //////////////////////////////////////////////////////////////////////////////////////////// -->
                                  </div>
                                  
                                  <!-- <a href="<?php echo site_url($current_page.'/create')?>" class="btn btn-lg btn-primary">Tambah</a> -->
                                </div>
                                <div class="col-lg-6 col-md-4 col-sm-12 col-xs-12">
                                    
                                </div>
                            </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>

            <div class="body table-responsive">
								<?php echo $alert ?>
                <table class="table table-striped table-bordered table-hover">
                    <thead class="thin-border-bottom">
                        <tr>
                            <th>No</th>
														<?php foreach ($table_header as $kh => $vh): ?>
															<th><?php echo $vh ?></th>
														<?php endforeach; ?>
                            <th>Pilihan</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php 
                        $_data['for_table'] = $for_table;
                        // echo var_dump( $for_table );return;
                        $tbl = $this->load->view('templates/tables/transaction_table', $for_table, true);
                        echo $tbl; 
                      ?>
                    </tbody>
                </table>
                <div class="text-center">
                    <?php if (isset($links)) echo $links?>
                </div>
            </div>
        </div>
    </div>
</div>
	</div>
</section>
