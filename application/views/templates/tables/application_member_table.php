<?php
    if (!empty($for_table)):
        foreach ($for_table as $key => $value) : 
            $cID['name'] = $value->name;
            $cID['base'] = "Permohonan";
            $cID['dataId'] = $value->id;
            $cID['deletePage'] = $current_page.'/delete';
            $modal = $this->load->view('templates/actions/modal_delete_confirm',$cID ,true); 
            echo $modal;
        ?>
            <tr>
            <td><?php echo $value->name.'-'.$value->party_id?></td>
			<td><?php echo $value->date ?></td>
			<td>
                <?php foreach ($table_header as $kh => $vh): ?>
                    <?php if($kh=='status') echo $value->{$kh} = ($value->{$kh}==1) ? '<span class="badge bg-blue">Di Survey</span>' : $value->{$kh} = ($value->{$kh}==2) ? '<span class="badge bg-teal">Di Kepala UPT</span>' : $value->{$kh} = ($value->{$kh}==3) ? '<span class="badge bg-green">Di Terima</span>': $value->{$kh} = ($value->{$kh}==4) ? '<span class="badge bg-green">Permohonan Sepenuhnya Diterima</span>': '<span class="badge bg-pink">Di Tolak</span>'; ?>
                <?php endforeach; ?>
            </td>
			<td><?php echo $value->info ?></td>
                <td style="text-align:center">
                    <td><a href="<?php echo site_url($current_page.'/detail/'.$value->id)?>" class="btn btn-sm bg-green">Detail</a></td>
                    <?php if($value->status != '<span class="badge bg-green">Permohonan Sepenuhnya Diterima</span>'){ ?>
                        <td><button type="button" class="btn btn-sm btn-danger waves-effect" data-toggle="modal" data-target="#<?php echo $value->id ?>">Batalkan</button></td>
                    <?php } ?>
                </td>
            </tr>
        <?php
        endforeach;
    else:
    ?>
    <h3>Data Tidak Ditemukan</h3>
<?php endif; ?>