<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<h2><?php echo $block_header ?></h2>
		</div>
  <div class="row clearfix">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="card">
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
                          if (!empty($for_table)): //if array data in not empty, show table
                            $no=$number;
                            foreach ($for_table as $key => $value) :
                                $no++;
                            ?>
                                <tr>
                                  <th scope="row"><?php echo $no?></th>
																	<?php foreach ($table_header as $kh => $vh): ?>
																		<?php if($kh=='status')$value->{$kh} = ($value->{$kh}==1) ? 'Active': 'Non-Active'; ?>
																		<td><?php echo $value->{$kh}?></td>
																	<?php endforeach; ?>
                                    <td style="text-align:center">
                                    <div class="dropdown">
                                      <?php echo form_open($current_page.'/createUser'); ?>
                                        <input type="hidden" name="party_id" value="<?php echo $value->id; ?>">
                                        <button type="submit" class="btn bg-green waves-effect" style="margin-right:20px;">
                                          <i class="material-icons">verified_user</i>
                                          <span>JOIN</span>
                                        </button>
                                      <?php echo form_close(); ?>
                                    </div>
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


    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="card">
            <div class="body table-responsive">
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
                        $tbl = $this->load->view('templates/tables/cluster_member_table', $for_table, true);
                        echo $tbl; 
                      ?>
                  </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
	</div>
</section>
