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
							<?php echo strtoupper($header)?>
							<small><?php echo $sub_header ?></small>
						</h2>
						<ul class="header-dropdown m-r--5">
							<a href="<?php echo site_url($parent_page)?>"><button type="button" class="btn btn-warning" name="button">Kembali</button></a>
						</ul>
					</div>
					<div class="body">
						<div class="row clearfix">
							<?php $this->load->view('templates/_admin_parts/form'); ?>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>
							<?php echo strtoupper("Anggota Kelompok")?>
						</h2>
						<ul class="header-dropdown m-r--5">
						<!-- http://localhost/blud/fund/cluster/create_aplicants/39 -->
							<a href="<?php echo site_url('fund/cluster/create_aplicants/'.$party_id.'/'.$user_id)?>" class="btn btn-sm btn-primary">Tambah</a>
							<!-- <a href="<?php echo site_url($parent_page)?>"><button type="button" class="btn btn-warning" name="button">Kembali</button></a> -->
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
