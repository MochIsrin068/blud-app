<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<h2>
				<?php echo $block_header?>
			</h2>
		</div>
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
						<?php if(isset($alert)) echo $alert; ?>
						<h2>
							<?php echo strtoupper($header)?>
							<small><?php echo $sub_header ?></small>
						</h2>
						<ul class="header-dropdown m-r--5">
							<a href="<?php echo site_url($parent_page)?>"><button type="button" class="btn btn-warning" name="button">Kembali</button></a>
						</ul>
					</div>
					<div class="body">

						<?php echo $form_cluster?>
					</div>
				</div>
			</div>
		</div>
		<div class="row clearfix">
			<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>
							<?php echo strtoupper("Anggota Kelompok")?>
						</h2>
					</div>
					<div class="body">
						<?php echo form_open_multipart( site_url( $parent_page."/create_aplicantly" ) ) ?>
						<input name='party_id' type='hidden' id='party_id' value="<?php echo $party_id?>"/>
						<input name='district' type='hidden' id='district' value="<?php echo $district?>"/>
						<input name='group4' type='hidden' id='group4' value="1"/>
						<?php 
							echo $form_add_1 ;
							echo "<p' class='text-mute'>status</p>";												
							echo "<div class='demo-radio-button'>";

							// echo "<input name='group4' type='radio' id='radio_1' value='1' class='radio-col-red' checked />";
							// echo "<label for='radio_1'>Ketua</label>";
							
							// echo "<input name='group4' type='radio' id='radio_8' value='3' class='radio-col-pink' />";
							// echo "<label for='radio_8'>Bendahara</label>";

							// echo "<input name='group4' type='radio' id='radio_9' value='4' class='radio-col-purple' />";
							// echo "<label for='radio_9'>Sekretaris</label>";

							echo "<input name='group4' type='radio' id='radio_7' value='2' class='radio-col-red' checked/>";
							echo "<label for='radio_7'>Anggota</label>";

							echo "</div>";
						?>
							<!-- <div class="row clearfix">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="pull-right" >
											<button type="submit" class="btn float-left btn-success waves-effect">Simpan</button>
									</div>
								</div>
							</div> -->
						<?php //echo form_close() ?>
						
					</div>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>
							<?php echo strtoupper("Upload Document")?>
							<small><?php echo "Ingat!, Ktp, Rekening Listrik / Air Wajib!!" ?></small>
						</h2>
					</div>
					<div class="body">
						<!-- <input name='group4' type='hidden' id='group4' value="1"/> -->
						<?php 
							echo $form_add_2 ;
						?>
					</div>
				</div>
			</div>

		</div>

		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="body">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="pull-right" >
										<button type="submit" class="btn float-left btn-success waves-effect">Simpan</button>
								</div>
							</div>
						</div>
						<?php echo form_close() ?>
					</div>
				</div>
			</div>
		</div>

	</div>

</section>
