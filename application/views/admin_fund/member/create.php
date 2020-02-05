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
								<?php foreach ($form_data as $key => $value): ?>
								<?php if ($value['type']!='hidden'): ?>
									<div class="col-sm-12">
									<div class="form-group form-float">
										<div class="form-line">
										<?php
											switch ($value['type']) {
											case 'select':
												$label =  $value['placeholder'];
												$options =  $value['option'];
												$name = $value['name'];
												$selected = $value['value'];
												unset($value['placeholder']);
												unset($value['option']);
												unset($value['name']);
												echo "<p' class='text-mute'>$label</p>";
												echo form_dropdown($name, $options, $selected, $value);
												break;
											case 'radio':
												echo "<p' class='text-mute'>status</p>";												
												echo "<div class='demo-radio-button'>";
												echo "<input name='group4' type='radio' id='radio_7' value='2' class='radio-col-red' checked />";
												echo "<label for='radio_7'>Anggota</label>";

												echo "<input name='group4' type='radio' id='radio_8' value='3' class='radio-col-pink' />";
												echo "<label for='radio_8'>Bendahara</label>";

												echo "<input name='group4' type='radio' id='radio_9' value='4' class='radio-col-purple' />";
												echo "<label for='radio_9'>Sekretaris</label>";
												echo "</div>";
												break;
										
											default:
												if($value['name'] == "date"){
												$label =  $value['placeholder'];
												unset($value['placeholder']);
												// echo "<div class='form-group'><div class='form-line' id='bs_datepicker_container'>";
												echo "<label class='form-label'>$label</label>";
												echo form_input($value);
												// echo "</div></div>";
												break;
												}else{
												$label =  $value['placeholder'];
												unset($value['placeholder']);
												echo "<label class='form-label'>$label</label>";
												echo form_input($value);
												break;
												}
											}
										?>
										</div>
									</div>
									</div>
								<?php else: ?>
									<?php
									$name = $value['name'];
									$val = $value['value'];
									echo form_hidden($name, $val);
									?>
								<?php endif; ?>

								<?php endforeach; ?>
								<?php if (!isset($detail)): ?>
									<div class="col-sm-12 ">
										<button type="submit" class="btn float-left btn-success waves-effect">Simpan</button>
									</div>
									<?php echo form_close();?>
								<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</section>
