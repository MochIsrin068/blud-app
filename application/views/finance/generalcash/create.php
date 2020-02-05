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
    // define the function within the global scope
    $(document).ready(function(){
        $.fn.MessageBox = function(name, id , a) {
            console.log( name );
            $( a ).parents('div').find('input[name="account_name"]').val( name ) ;
            $( a ).parents('div').find('input[name="account_id"]').val( id ) ;
        };
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
                <?php echo form_open();  ?>							
                <!--  -->
                <div class="card" >
                    <div class="body">
                        <!--  -->
                            <?php echo $form_account_dropdown ?>	
                            <?php echo $form_add ?>	
                        <!--  -->
                        <!--  -->
                      
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <button class=" pull-right btn btn-bold btn-success btn-sm " style="margin-left: 5px;" type="submit" >
                                    Simpan
                                </button>
                                
                            </div>
                        </div>
                        <!--  -->
                    </div>
                </div>
                <!--  -->
                <?php echo form_close();  ?>
            </div>
            
      </div>
	<!-- </div> -->
  
</section>
