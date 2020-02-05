<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<h2><?php echo $block_header ?></h2>
		</div>
        <div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                  <div class="row clearfix" style="margin-bottom:-30px">
                    <div class="col-md-6">
                      <h2>
                          <?php echo strtoupper($header)?>
                          <small><?php echo $sub_header ?></small>
                      </h2>
                    </div>
                    <!-- search form -->
                    <div class="col-md-6">
                      <div class="row clearfix">
                        <div class="col-md-2">

                            <div class="row clearfix">
                              <select class="form-control show-tick" id="limit" name="" onchange="changeLimit()">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                              </select>
                            </div>

                        </div>
                        <div class="col-md-10">
                          <form method="get" action="<?php echo site_url($current_page)?>">
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12" style="margin-bottom:0px!important">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="key" required class="form-control" placeholder="Pencarian" value="<?php echo ($key) ? $key : '' ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-4 col-sm-12 col-xs-12">
                                  <div class="btn-group">
                                    <button type="submit" class="btn btn-warning btn-md m-l-15 waves-effect"><i class="material-icons">search</i></button>
                                    <a href="<?php echo site_url($current_page); ?>" type="button"  class="btn btn-warning btn-md m-l-15 waves-effect"><i class="material-icons">refresh</i></a>
                                  </div>
                                  <a href="<?php //echo site_url($current_page.'/create')?>" class="btn btn-lg btn-primary disabled" disabled>Tambah</a>
                                </div>
                            </div>
                          </form>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>

            <div class="body table-responsive">
								<?php echo $alert ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
														<?php foreach ($table_header as $kh => $vh): ?>
															<th><?php echo $vh ?></th>
														<?php endforeach; ?>
                            <th>Pilihan</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                          if (!empty($for_table)):
                            $no=$number;
                            foreach ($for_table as $key => $value) :
                                $no++;
                            ?>
                                <tr>
                                  <th scope="row"><?php echo $no?></th>
																	<td><?php echo $value->name.'-'.$value->party_id?></td>
                                  <td><?php echo $value->date ?></td>
                                  <td>
                                    <?php foreach ($table_header as $kh => $vh): ?>
                                      <?php if($kh=='status') echo $value->{$kh} = ($value->{$kh}==1) ? '<span class="badge bg-blue">Di Survey</span>' : $value->{$kh} = ($value->{$kh}==2) ? '<span class="badge bg-teal">Di Kepala UPT</span>' : $value->{$kh} = ($value->{$kh}==3) ? '<span class="badge bg-green">Di Terima</span>': $value->{$kh} = ($value->{$kh}==4) ? '<span class="badge bg-green">Permohonan Sepenuhnya Diterima</span>': '<span class="badge bg-pink">Di Tolak</span>'; ?>
                                    <?php endforeach; ?>
                                  </td>
                                  <td><?php echo $value->info ?></td>
                                    <td style="text-align:center">
                                      <?php if($value->status == '<span class="badge bg-green">Permohonan Sepenuhnya Diterima</span>' || $value->status == '<span class="badge bg-green">Di Terima</span>'){  ?>
                                        <button class="btn btn-sm bg-green disabled" disabled>Verifikasi</button>
                                      <?php }else{ ?>
                                        <a href="<?php echo site_url($current_page.'/detail/'.$value->id)?>" class="btn btn-sm bg-green">Verifikasi</a>
                                      <?php } ?>                                      
                                    </td>
                                </tr>
                        <?php
                            endforeach;
                          else:
                        ?>
                        <h3>Data Tidak Ditemukan</h3>
                      <?php endif; ?>
                    </tbody>
                </table>
                <div class="text-center">
                    <?php if (isset($links)) echo $links?>
                </div>
            </div>
        </div>
    </div>
</div>
	</div>
</section>
