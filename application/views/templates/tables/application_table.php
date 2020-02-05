<?php
    if (!empty($for_table)): //if array data in not empty, show table
        $no=$number;
        foreach ($for_table as $key => $value) :
            $no++;
            $cID['name'] = $value->name.'-'.$value->party_id;
            $cID['base'] = "Permohonan";
            $cID['dataId'] = $value->id;
            $cID['deletePage'] = $current_page.'/delete';
            $modal = $this->load->view('templates/actions/modal_delete_confirm',$cID ,true); 

            $d['partyId'] = $value->party_id;
            $d['dataId'] = $value->id;
            $d['action'] = $current_page.'/documentUpload';
            $modalDocument = $this->load->view('templates/actions/modal_document_upload',$d ,true); 
            echo $modal;
            echo $modalDocument;
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
                <?php if($value->status == '<span class="badge bg-blue">Di Survey</span>' || $value->status == '<span class="badge bg-pink">Di Tolak</span>' || $value->status == '<span class="badge bg-teal">Di Kepala Blud</span>' ){ ?>
                    <a href="<?php echo site_url($current_page.'/detail/'.$value->id)?>" class="btn btn-sm bg-blue">Detail</a>
                    <!-- <a href="<?php echo site_url($current_page.'/edit/'.$value->id)?>" class="btn btn-sm bg-green">Edit</a> -->
                    <button type="button" class="btn btn-sm btn-danger waves-effect" data-toggle="modal" data-target="#<?php echo $value->id ?>">X</button>
                <?php } ?>

                <?php if($value->status == '<span class="badge bg-green">Di Terima</span>'){ ?>
                    <a href="<?php echo site_url($current_page.'/cetakAkad/'.$value->id)?>" class="btn btn-sm bg-cyan">Akad</a>
                    <button type="button" class="btn btn-sm btn-primary waves-effect" data-toggle="modal" data-target="#<?php echo $value->party_id ?>">Upload</button>
                    <a href="<?php echo site_url($current_page.'/detail/'.$value->id)?>" class="btn btn-sm bg-blue">Detail</a>
                    <!-- <a href="<?php echo site_url($current_page.'/edit/'.$value->id)?>" class="btn btn-sm bg-green">Edit</a> -->
                    <!-- <button type="button" class="btn btn-sm btn-danger waves-effect" data-toggle="modal" data-target="#<?php //echo $value->id ?>">X</button> -->
                <?php } ?>

                <?php if($value->status == '<span class="badge bg-green">Permohonan Sepenuhnya Diterima</span>'){ 
                    $this->load->model('m_fund_application');
                    $docAkad['party_id'] = $value->party_id;
                    $da = $this->m_fund_application->getAkad($docAkad);    
                ?>
                    <a href="<?php echo site_url('/uploads/dokumen/'.$da[0]->name)?>" target="blank" class="btn btn-sm bg-blue">Lihat Document</a>
                    <a href="<?php echo site_url($current_page.'/getReceipt/'.$value->id)?>" class="btn btn-sm bg-blue">Cetak Kuitansi</a>
                    <!-- <button type="button" class="btn btn-sm btn-danger waves-effect" data-toggle="modal" data-target="#<?php echo $value->id ?>">X</button> -->
                <?php } ?>
        
            </td>
        </tr>
        <?php
        endforeach;
    else:
    ?>
    <h3>Data Tidak Ditemukan</h3>
<?php endif; ?>