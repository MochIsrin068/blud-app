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
                      <div class="row clearfix"  >
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
                                <div class="pull-right" >
                                    <?php echo $modal_form_create ?>
                                </div>
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

