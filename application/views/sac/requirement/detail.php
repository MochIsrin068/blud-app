<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<h2>
				<?php echo $block_header?>
			</h2>
		</div>
		<div class="row clearfix">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>
							<?php echo strtoupper("Anggota Kelompok")?>
						</h2>
						<ul class="header-dropdown m-r--5">
							<a href="<?php echo site_url($parent_page)?>"><button type="button" class="btn btn-warning" name="button">Kembali</button></a>
						</ul>
					</div>
					<div class="body">
						<div class="row clearfix">
							<div class="col-md-12">
								<?php if(!empty($anggota)){?>								
									<table class="table table-striped">
										<thead>
											<tr>
												<th>Nama</th>
												<th>Status</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($anggota as $a): ?>
												<tr>
													<td><?php echo $a->first_name.''.$a->last_name; ?></td>
													<td>
														<?php
															if($a->status == 1){
																echo "<span class='badge bg-green'>Ketua</span>";
															}else if($a->status == 2){
																echo "<span class='badge bg-blue'>Anggota</span>";
															}else if($a->status == 3){
																echo "<span class='badge bg-cyan'>Bendahara</span>";
															}else{
																echo "<span class='badge bg-teal'>Sekretaris</span>";
															}
														?>
													</td>
													<td>
														<?php echo form_open($parent_page.'/detail/'.$myid) ?>
															<input type="hidden" name="idMember" value=<?php echo $a->id ?> >
															<input type="hidden" name="idUser" value=<?php echo $a->user_id ?> >
															<button type="submit" class="btn btn-sm bg-pink">Detail</button>
														<?php echo form_close(); ?>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								<?php }else{?>
									<h3 align="center">Anggota Tidak Ada</h3>
								<?php }?>
							</div>
						</div>
					</div>
				</div>

				<div class="card">
					<div class="header">
						<?php echo strtoupper("Verifikasi<br/>")?>
							<small><?php echo "Verivikasi Data, di tolak atau di setujui" ?></small>
						</h2>
					</div>	
					<div class="body">
						<div class="row clearfix">
							<div class="col-md-6">
								<button type="button" class="btn btn-sm bg-pink form-control" data-toggle="modal" data-target="#defaultModal">Tolak</button>
							</div>
							<div class="col-md-6">
								<?php echo form_open($parent_page.'/approve') ?>
									<input type="hidden" value=<?php echo $myid ?> name="id"/>
									<button type="submit" class="btn btn-sm bg-green form-control">Setujui</button>
								<?php echo form_close(); ?>
							</div>
						</div>

					</div>
				</div>				
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<?php if(!empty($memberDetail)){ ?>
					<div class="card animated bounceInDown">
						<div class="header">
							<h2>
								<?php echo strtoupper("Detail Anggota")?>
							</h2>
						</div>
						<div class="body">
							<?php foreach($memberDetail as $m){ ?>
								<table class="table table-striped">
									<tbody>
										<tr>
											<td>Nama Lengkap</td>
											<td>:</td>
											<td><?php echo $m->first_name.' '.$m->last_name ?></td>
										</tr>
										<tr>
											<td>Alamat</td>
											<td>:</td>
											<td><?php echo $alamat ?></td>
										</tr>
										<tr>
											<td>Nomor HP</td>
											<td>:</td>
											<td><?php echo $m->phone ?></td>
										</tr>
										<tr>
											<td>Nominal Pinjaman</td>
											<td>:</td>
											<td><?php echo $nominal ?></td>
										</tr>
										<?php if(!empty($memberDocument)){ 
											foreach($memberDocument as $md){ ?>
											<tr>
												<td>Foto Ktp</td>
												<td>:</td>
												<td><a href="<?php echo base_url('uploads/dokumen/').$md->identity_card ?>" target="blank"><?php echo $md->identity_card == 'nothing' ? "-" : $md->identity_card ?></a></td>
											</tr>
											<tr>
												<td>Pajak Bangunan</td>
												<td>:</td>
												<td><a href="<?php echo base_url('uploads/dokumen/').$md->property_tax ?>" target="blank"><?php echo $md->property_tax == 'nothing' ? "-" : $md->property_tax ?></a></td>
											</tr>
											<tr>
												<td>Tagihan Listrik</td>
												<td>:</td>
												<td><a href="<?php echo base_url('uploads/dokumen/').$md->electricity_bills ?>" target="blank"><?php echo $md->electricity_bills == 'nothing' ? "-" : $md->electricity_bills ?></a></td>
											</tr>
											<tr>
												<td>Tagihan Air</td>
												<td>:</td>
												<td><a href="<?php echo base_url('uploads/dokumen/').$md->water_bills ?>" target="blank"><?php echo $md->water_bills == 'nothing' ? "-" : $md->water_bills?></a></td>
											</tr>
											<tr>
												<td>Surat Rekomendasi</td>
												<td>:</td>
												<td><a href="<?php echo base_url('uploads/dokumen/').$md->letter_of_recommendation ?>" target="blank"><?php echo $md->letter_of_recommendation == 'nothing' ? "-" : $md->letter_of_recommendation?></a></td>
											</tr>
										<?php }} ?>
									</tbody>
								</table>
							<?php } ?>
						</div>
					</div>				
				<?php }?>
					
			
				<div class="card">
					<div class="header">
						<h2>
							<?php echo strtoupper($header)?>
							<small><?php echo $sub_header ?></small>
						</h2>
					</div>
					<div class="body">
						<div class="row clearfix">
							<?php echo (!isset($detail)) ? form_open_multipart($form_action) : null;?>
								<?php foreach ($form_data as $key => $value): ?>
									<div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h4 class="modal-title" id="defaultModalLabel">Konfirmasi</h4>
												</div>
												<div class="modal-body">
													<?php echo form_open($parent_page.'/edit') ?>
														<input type="hidden" value=<?php echo $reqId ?> name="id"/>
														<input type="hidden" value='0' name="status"/>
														<label>Masukan Alasan / Keteranagan Penolakan</label>
														<div class="form-group">
															<div class="form-line">
																<textarea rows="4" class="form-control no-resize" name="info" placeholder="Masukkan Alasan disini..."></textarea>
															</div>
														</div>
												</div>
												<div class="modal-footer">
														<button type="submit" class="btn btn-sm bg-green">Kirim</button>
													<?php echo form_close(); ?>
													<button type="button" class="btn btn-sm bg-cyan" data-dismiss="modal">CLOSE</button>
												</div>
											</div>
										</div>
									</div>	
								<?php if ($value['type']!='hidden'): ?>
									<div class="col-sm-12">
										<div class="form-group form-float">
											<div class="form-line focused">
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
												case 'textarea':
													$label =  $value['placeholder'];
													unset($value['placeholder']);
													echo "<label class='form-label'>$label</label>";
													echo form_textarea($value);
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
						</div>
					</div>
				</div>
			</div>
	
		</div>
	</div>

</section>
