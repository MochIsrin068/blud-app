<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<h2><?php echo $page_title?></h2>
		</div>
		<div class="row clearfix">
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-expand-effect animated zoomIn" style="margin-bottom:0">
                    <div class="icon">
                        <i class="material-icons">account_box</i>
                    </div>
                    <div class="content">
                        <div class="text">JUMLAH PEMOHON</div>
                        <div class="number count-to" data-from="0" data-to="125" data-speed="15" data-fresh-interval="20">
							<?php echo $pemohon ?>
						</div>
                    </div>
                </div>
                <a href="fund/applicant" class="btn btn-sm bg-pink btn-block animated zoomIn"">MORE INFO</a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect animated zoomIn" style="margin-bottom:0">
                    <div class="icon">
                        <i class="material-icons">supervised_user_circle</i>
                    </div>
                    <div class="content">
                        <div class="text">JUMLAH KELOMPOK</div>
                        <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20">
							<?php echo $kelompok ?>
						</div>
                    </div>
                </div>
                <a href="fund/cluster" class="btn btn-sm bg-cyan btn-block animated zoomIn"">MORE INFO</a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-light-green hover-expand-effect animated zoomIn" style="margin-bottom:0">
                    <div class="icon">
                        <i class="material-icons">description</i>
                    </div>
                    <div class="content">
                        <div class="text">JUMLAH PERMOHONAN</div>
                        <div class="number count-to" data-from="0" data-to="243" data-speed="1000" data-fresh-interval="20">
							<?php echo $permohonan ?>
						</div>
                    </div>
                </div>
                <a href="fund/application" class="btn btn-sm bg-light-green btn-block animated zoomIn"">MORE INFO</a>
            </div>
        </div>
	</div>
</section>