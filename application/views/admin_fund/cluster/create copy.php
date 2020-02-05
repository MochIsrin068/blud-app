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
						<h2>
							<?php echo strtoupper($header)?>
							<small><?php echo $sub_header ?></small>
						</h2>
						<ul class="header-dropdown m-r--5">
							<a href="<?php echo site_url($parent_page)?>"><button type="button" class="btn btn-warning" name="button">Kembali</button></a>
						</ul>
					</div>
					<div class="body">
						<h2 class="card-inside-title"></h2>
						<div class="row clearfix">
							<div class="col-lg-12">
								<?php if(isset($alert)) echo $alert; ?>
							</div>


							<?php echo (!isset($detail)) ? form_open_multipart($form_action) : null;?>
									<div class="col-sm-12">
									<div class="form-group form-float">
										<div class="form-line">
											<label for="" class="control-label">Pilih Kecamatan</label>
											<select name="kecamatan" id="kecamatan" class="form-control" onChange="getkecamatan(this.value)">
												<option value="Kendari">Kendari</option>
												<option value="Kendari Barat">Kendari Barat</option>
												<option value="Poasia">Poasia</option>
												<option value="Abeli">Abeli</option>
												<option value="Kambu">Kambu</option>
												<option value="Mandonga">Mandonga</option>
												<option value="Wua-wua">Wua - wua</option>
												<option value="Puuwatu">Puuwatu</option>
												<option value="Kadia">Kadia</option>
												<option value="Baruga">Baruga</option>
											</select>
										</div>

										
									</div>
									</div>
							
								<?php if (!isset($detail)): ?>
									<?php echo form_close();?>
								<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>
							<?php echo strtoupper("Ketua")?>
						</h2>
					</div>
					<div class="body">
						<?php echo $form_add_1 ?>
						<h2 class="card-inside-title"></h2>
						<div class="row clearfix">
							<div class="col-lg-12">
								<?php if(isset($alert)) echo $alert; ?>
							</div>
							<script type="text/javascript" src="<?php echo base_url('assets/')?>ajax.js"></script> 
							<?php echo (!isset($detail)) ? form_open_multipart($form_action) : null;?>
									
								<?php if (!isset($detail)): ?>
									<?php echo form_close();?>
								<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>
							<?php echo strtoupper("Sekretaris")?>
						</h2>
					</div>
					<div class="body">
						<h2 class="card-inside-title"></h2>
						<div class="row clearfix">
							<div class="col-lg-12">
								<?php if(isset($alert)) echo $alert; ?>
							</div>
							<script type="text/javascript" src="<?php echo base_url('assets/')?>ajax.js"></script> 
							<?php echo (!isset($detail)) ? form_open_multipart($form_action) : null;?>
									<div class="col-sm-12">
									<div class="form-group form-float">			
									</div>
									</div>
								<?php if (!isset($detail)): ?>
									<?php echo form_close();?>
								<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>
							<?php echo strtoupper("Bendahara")?>
						</h2>
					</div>
					<div class="body">
						<h2 class="card-inside-title"></h2>
						<div class="row clearfix">
							<div class="col-lg-12">
								<?php if(isset($alert)) echo $alert; ?>
							</div>
							<script type="text/javascript" src="<?php echo base_url('assets/')?>ajax.js"></script> 
							<?php echo (!isset($detail)) ? form_open_multipart($form_action) : null;?>
									<div class="col-sm-12">
									<div class="form-group form-float">			
									</div>
									</div>
								<?php if (!isset($detail)): ?>
									<?php echo form_close();?>
								<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="body">
							<button type="submit" class="btn float-left btn-success waves-effect">Simpan</button>
					</div>
				</div>
			</div>
		</div>

	</div>

</section>
