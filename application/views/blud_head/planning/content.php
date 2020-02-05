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
                      <?php echo $links?>
                      <!--  -->
                  </div>
              </div>
          </div>
      </div>
	<!-- </div> -->
</section>

