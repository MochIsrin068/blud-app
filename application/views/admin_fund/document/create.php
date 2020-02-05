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
							<a href="<?php echo site_url($parent_page2)?>"><button type="button" class="btn btn-warning" name="button">Kembali</button></a>
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
								<div class="col-sm-6">
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
												case 'file':
													$label =  $value['placeholder'];
													echo "<label class='form-label'>$label</label><br/><br/>";
													echo form_upload($value);
													break;
												default:
													if($value['name'] == "date"){
													$label =  $value['placeholder'];
													unset($value['placeholder']);
													echo "<label class='form-label'>$label</label>";
													echo form_input($value);
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
