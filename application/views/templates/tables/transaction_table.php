<?php
    $this->load->model('m_fund_applicant');
    if (!empty($for_table)):
        $no=$number;
        foreach ($for_table as $key => $value) :
            $no++;
            $cID['name'] = $value->name.'-'.$value->id;
            $cID['base'] = "Kelompok";
            $cID['dataId'] = $value->id;
            $cID['deletePage'] = $current_page.'/delete';
            $modal = $this->load->view('templates/actions/modal_delete_confirm',$cID ,true); 
            echo $modal;
            $i['party_id'] = $value->id;
            $userId = $this->m_fund_applicant->getWhere($i);
            $ketua = null;
            if($userId){
                $ketua = $userId[0]->user_id;
            }
        ?>
        <tr>
            <th scope="row"><?php echo $no?></th>
			<td><?php echo $value->name.'-'.$value->party_id?></td>
            <td style="text-align:center">
				<a href="<?php echo site_url($current_page.'/detail/'.$value->party_id)?>" class="btn btn-sm btn-success waves-effect">Detail Transaksi</a>
                <a href="<?php echo site_url($current_page.'/cetak/'.$value->party_id)?>" class="btn btn-sm btn-primary waves-effect">Cetak</a>
            </td>
        </tr>
        <?php
        endforeach;
    else:
    ?>
        <h3>Data Tidak Ditemukan</h3>
    <?php endif; ?>