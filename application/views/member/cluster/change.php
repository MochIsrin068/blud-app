<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<h2><?php echo $page_title?></h2>
		</div>
        <div class="card" style="padding:20px;">
		    <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php echo $this->session->flashdata('alert'); ?>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                    <a href="change" style="text-decoration:none">
                        <div class="info-box bg-orange hover-expand-effect animated zoomIn">
                            <div class="icon">
                                <i class="material-icons">supervised_user_circle</i>
                            </div>
                            <div class="content">
                                <div class="text">PILIH KELOMPOK</div>
                                <div class="number count-to" data-from="0" data-to="125" data-speed="15" data-fresh-interval="20">
                                    <?php echo $kelompok ?>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <h4 style="text-align:center;color:grey;padding-top:20px;">
                        <span class="label bg-red animated zoomIn" style="border-radius:20px;">Atau</span>
                    </h4>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                    <a href="change/create" style="text-decoration:none">
                        <div class="info-box bg-light-green hover-expand-effect animated zoomIn">
                            <div class="icon">
                                <i class="material-icons">supervised_user_circle</i>
                            </div>
                            <div class="content">
                                <div class="text">BUAT KELOMPOK</div>
                                <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20">
                                    <?php echo "NEW" ?>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
	</div>
</section>