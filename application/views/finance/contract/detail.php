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
														<?php echo form_open($parent_page.'/cetakAkad/'.$myid) ?>
															<input type="hidden" name="idMember" value=<?php echo $a->id ?> >
															<input type="hidden" name="idParty" value=<?php echo $myid ?> >
															<button type="submit" class="btn btn-sm bg-cyan">Cetak Dokumen Akad</button>
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
			</div>
		</div>
	</div>

</section>
