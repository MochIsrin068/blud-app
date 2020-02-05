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
                        	<small><?php echo 'Tekan Tombol ">" untuk melihat detail piutang anggota kelompok' ?></small>
						</h2>
						<ul class="header-dropdown m-r--5">
							<a href="<?php echo site_url($parent_page)?>"><button type="button" class="btn btn-warning" name="button">Kembali</button></a>
						</ul>
					</div>

					<div class="body">
						<div class="row clearfix">
							<div class="col-md-12">
								<?php echo $this->session->flashdata('alert') ?>
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
													<td>
														<?php echo form_open($parent_page.'/detail/'.$myid) ?>
															<input type="hidden" value="<?php echo $a->id ?>" name="id"/>
															<button type="submit" class="btn btn-sm bg-teal">Detail</button>
														<?php echo form_close() ?>
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

			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<?php if(!empty($nominal)){ ?>
					<div class="card animated bounceInDown">
						<div class="header">
							<h2>
								<?php echo strtoupper("Detail Tagihan ".$usernames)?>
							</h2>

							<?php 
								if($historyPayment == null){
									$is_complete =  $nominal[0]->nominal <= $totalPayment;
									echo $is_complete ? '' : '<ul class="header-dropdown m-r--5"><button type="button" class="btn btn-primary" name="button" data-toggle="modal" data-target="#'.$nominal[0]->id.'">Bayar Angsuran</button></ul>';
								}
							?>
						</div>
						<div class="body">
							<?php foreach($nominal as $m){ ?>
								<table class="table table-striped">
									<tbody>
										<tr>
											<td>Nilai Pinjaman</td>
											<td>:</td>
											<td><?php echo 'Rp.'.number_format($m->nominal) ?></td>
										</tr>
										<td>Sisa Utang</td>
											<td>:</td>
											<?php 
												$nominalPay = $totalPayment == null ? 0 : $totalPayment;
												$sisa = $m->nominal - $nominalPay; 
											?>
											<td><?php echo 'Rp.'.number_format($sisa) ?>
										</td>
										<tr>
											<td>Status Pinjaman</td>
											<td>:</td>
											<td>
												<?php if($nominal[0]->nominal <= $totalPayment){
													echo "<span class='badge bg-green'>Lunas</span>";
												}else{
													echo "<span class='badge bg-red'>Belum Lunas</span>";
												} ?>
											</td>
										</tr>
									</tbody>
								</table>
							<?php } ?>
						</div>
					</div>				
				<?php }?>
			</div>
		</div>

		<?php
			if(!empty($nominal)){
				$cID['dataId'] = $nominal[0]->id;
				// $cID['partyId'] = $party_id;
				$cID['pay'] = $parent_page.'/pay/'.$party_id.'/'.$nominal[0]->nominal.'/'.$totalPayment;
				$modal = $this->load->view('templates/actions/modal_form_payment',$cID ,true); 

				echo $modal;
			}
		?>

		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<?php if(!empty($historyPayment)){ ?>
					<div class="card animated bounceInUp">
						<div class="header">
							<h2>
								<?php echo strtoupper("History Pembayaran ".$usernames)?>
							</h2>
							<?php 
								$is_complete =  $nominal[0]->nominal <= $totalPayment;
								echo $is_complete ? '' : '<ul class="header-dropdown m-r--5"><button type="button" class="btn btn-primary" name="button" data-toggle="modal" data-target="#'.$nominal[0]->id.'">Bayar Angsuran</button></ul>';
							?>
						</div>
						<div class="body">
							<?php foreach($nominal as $m){ ?>
								<table class="table table-striped">
									<thead>
										<tr>
											<th>Tanggal Pembayaran</th>
											<th>Nominal Pembayaran</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($historyPayment as $hp){ ?>
											<tr>
												<?php $tgl = explode('-', $hp->date) ?>
												<?php $bln = explode('-', $hp->date) ?>
												<?php $tahun = explode('-', $hp->date) ?>
												<?php $date = $tgl[2].'-'.$bln[1].'-'.$tahun[0]; ?>
												<td><?php echo $date ?></td>
												<td><?php echo 'Rp.'.number_format($hp->nominal) ?></td>
											</tr>
										<?php } ?>
										<tr>
											<th>TOTAL PEMBAYARAN</th>
											<th><?php echo 'Rp.'.number_format($totalPayment) ?></th>
										<tr>
									</tbody>
								</table>
							<?php } ?>
						</div>
					</div>
				</div>				
			<?php } ?>
		</div>
	</div>

</section>
