<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<h2><?php echo $block_header ?></h2>
		</div>
    <div class="row clearfix">

    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="card">
            <?php if (!empty($for_table)){ ?>
              <div class="header bg-green">
                  <h2>
                      Keterangan Permohonan <small>Anda Sudah Melakukan Permohonan</small>
                  </h2>
                  <ul class="header-dropdown m-r-0">
                    <li>
                        <a href="javascript:void(0);">
                            <i class="material-icons">check_circle</i>
                        </a>
                    </li>
                  </ul>
              </div>
              <div class="body">
                  <p>Silahkan klik tambah permohonan jika belum melakukan permohonan, jika telah melakukan permohonan, anda dapat mengolah data anda</p>
                  <a href="<?php echo site_url($current_page.'/create/'.$status[0]->party_id)?>" class="btn btn-lg btn-primary btn-lg waves-effect btn-block disabled" disabled>Buat Permohonan</a>
              </div>
            <?php }else{ ?>
              <div class="header bg-orange">
                  <h2>
                      Keterangan Permohonan <small>Anda Belum Melakukan Permohonan</small>
                  </h2>
                  <ul class="header-dropdown m-r-0">
                    <li>
                        <a href="javascript:void(0);">
                            <i class="material-icons">cancel</i>
                        </a>
                    </li>
                  </ul>
              </div>
              <div class="body">
                 <p>Silahkan klik tambah permohonan jika belum melakukan permohonan, jika telah melakukan permohonan, anda dapat mengolah data anda</p>
                  <a href="<?php echo site_url($current_page.'/create/'.$status[0]->party_id)?>" class="btn btn-lg btn-primary btn-lg waves-effect btn-block">Buat Pemohonan</a>
              </div>
            <?php } ?>
        </div>
      </div>

      <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
          <div class="card p-t-10">
            <div class="body table-responsive">
								<?php echo $alert ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
														<?php foreach ($table_header as $kh => $vh): ?>
															<th><?php echo $vh ?></th>
														<?php endforeach; ?>
                            <th>Pilihan</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php 
                        $d['for_table'] = $for_table;
                        $d['status'] = $status;
                        $tbl = $this->load->view('templates/tables/application_member_table', $d, true);
                        echo $tbl; 
                      ?>
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
