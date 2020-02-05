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
													<td><?php echo $a->first_name.' '.$a->last_name; ?></td>
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

													<?php 
														$users['user_id'] = $a->id;
														$users['parent_page'] = $parent_page;
														$users['party_id'] = $party_id;
														// $users['fullname'] = $a->first_name.' '.$a->last_name;
														$receiptModal = $this->load->view('templates/actions/modal_form_kwitansi', $users);
													?>
													<td>
														<button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#receiptkwitansi<?php echo$a->id;?>">Cetak Kuitansi </button>
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
