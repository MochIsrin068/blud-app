<script src="<?php echo base_url();?>assets/jquery.js"></script>
<script>
    $(".dropdown-toggle").on("mouseenter", function () {
        // make sure it is not shown:
        if (!$(this).parent().hasClass("show")) {
            $(this).click();
        }
    });
    $(".btn-group, .dropdown").on("mouseleave", function () {
        // make sure it is shown:
        if ($(this).hasClass("show")){
            $(this).children('.dropdown-toggle').first().click();
        }
    });
</script>

<style>
.dropdown-submenu {
    position: relative;
}
.dropdown-submenu > a.dropdown-item:after {
    font-family: FontAwesome;
    content: "\f054";
    float: right;
}
.dropdown-submenu > a.dropdown-item:after {
    content: ">";
    float: right;
}
.dropdown-submenu > .dropdown-menu {
    top: 77%;
    left: 100%;
    margin-top: 0px;
    margin-left: 0px;
}
.dropdown-submenu:hover > .dropdown-menu {
    display: block;
}
</style>

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
                        <!--  -->
                            <?php echo $form_add  ?>	
                        <!--  -->
                    </div>
                </div>
                <!--  -->
                <!--  -->
                <div class="card" >
                    <div class="header">
                        <div class="row clearfix" style="margin-bottom:-10px">
                            <div class="col-md-6">
                                <h2>
                                    List RBA
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="body" id="content" >
                    
                        <!--  -->
                            <!--  -->
                            <?php echo $table?>
                            <!--  -->
                    </div>
                </div>
                <!--  -->
          </div>
      </div>
	<!-- </div> -->
</section>
