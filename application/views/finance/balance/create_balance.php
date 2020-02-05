
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
                        <div class="row clearfix" style="margin-bottom:-10px">
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
                                    <div class="row clearfix">
                                        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12" style="margin-bottom:0px!important">
                                        </div>
                                        <div class="col-lg-6 col-md-4 col-sm-12 col-xs-12">
                                            <!--  -->
                                                <a href="<?php echo site_url($parent_page)?>"><button type="button" class="pull-right btn btn-warning" name="button">Kembali</button></a>
                                            <!--  -->
                                        </div>
                                    </div>
                                </div>

                            </div>
                            </div>
                            <!--  -->
                        </div>
                    </div>
                </div>
                <!-- NOTE -->
                <!--  -->
                <!--  -->
                <div class="card" >
                    <div class="header">
                        <div class="row clearfix" style="margin-bottom:-10px">
                            <div class="col-md-6">
                                <h2>
                                    List 
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body" >
                        <ol start="1" type="I" >
                            <?php foreach( $LISTS as $list ) : ?>
                                <li><h5>  <?php echo $list->name  ?> </h5> </li>
                                <?php echo $list->table?>
                                <div class="row clearfix" >
                                    <div class="col-md-12">
                                        <div class="pull-right" >
                                                <!--  -->
                                                <?php echo $list->modal ?>
                                                <!--  -->
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </ol>
                        <!--  -->
                        <?php //echo $table?>
                        <!--  -->
                        <!--  -->
                        <div class="row clearfix" >
                            <div class="col-md-12">
                                <div class="pull-right" >
                                        <!--  -->
                                        <?php //echo $modal_budget_plan_add ?>
                                        <!--  -->
                                </div>
                            </div>
                        </div>
                        <!--  -->
                    </div>
                    <!--  -->
                </div>
                <!--  -->
                
          </div>
      </div>
	<!-- </div> -->
</section>
