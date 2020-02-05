
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
                <?php
                    if( $plan->status == -1 ):
                ?>
                    <div class="card">
                        <div class="header bg-pink">
                            <h2>
                                Catatan
                            </h2>
                        </div>
                        <div class="body">
                            <?php
                                echo $plan->note;
                            ?>
                        </div>
                    </div>
                <?php
                    endif;
                ?>
                <!--  -->
                <!--  -->
                <div class="card" >
                    <div class="body">
                        <?php echo form_open()  ?>	
                        <!--  -->
                            <?php echo $form_add  ?>
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <button class=" pull-right btn btn-bold btn-primary btn-sm " style="margin-left: 5px;" type="submit" >
                                        Ubah
                                    </button>
                                </div>
                            </div>	
                        <!--  -->
                        <?php echo form_close()  ?>	
                    </div>
                </div>
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
                            <li><h5>  Pendapatan </h5> </li>
                                <?php echo $table_pendapatan?>
                                <!--  -->
                                <div class="row clearfix" >
                                    <div class="col-md-12">
                                        <div class="pull-right" >
                                                <!--  -->
                                                <?php echo $modal_budget_plan_add_pendapatan ?>
                                                <!--  -->
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                            <li><h5>  Belanja </h5> 
                                <ol start="1"  >
                                    <li> <h5>  Belanja Pegawai </h5> </li>      
                                                <?php echo $table_belanja?>
                                                <!--  -->
                                                <div class="row clearfix" >
                                                    <div class="col-md-12">
                                                        <div class="pull-right" >
                                                                <!--  -->
                                                                <?php echo $modal_budget_plan_add_belanja ?>
                                                                <!--  -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--  -->

                                    <li> <h5>  Belanja Barang dan Jasa </h5> </li>
                                                <?php echo $table_barang?>
                                                <!--  -->
                                                <div class="row clearfix" >
                                                    <div class="col-md-12">
                                                        <div class="pull-right" >
                                                                <!--  -->
                                                                <?php echo $modal_budget_plan_add_barang ?>
                                                                <!--  -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--  -->
                                </ol>
                            </li>
                            <li><h5>  Pembiayaan </h5> </li>
                                <?php echo $table_penerimaan?>
                                    <!--  -->
                                    <div class="row clearfix" >
                                        <div class="col-md-12">
                                            <div class="pull-right" >
                                                    <!--  -->
                                                    <?php echo $modal_budget_plan_add_penerimaan ?>
                                                    <!--  -->
                                            </div>
                                        </div>
                                    </div>
                                    <!--  -->
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
                <!--  -->
                <div class="card" >
                    <div class="body">
                            <div class="pull-right">
                                <?php echo $modal_form_finish  ?>	
                            </div>
                            <br>
                    </div>
                </div>
                <!--  -->
          </div>
      </div>
	<!-- </div> -->
</section>
