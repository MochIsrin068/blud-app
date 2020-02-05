<?php
    if (!empty($for_table2)): //if array data in not empty, show table
        $no=$number;
        foreach ($for_table2 as $key => $value) :
            $no++;
            $cID['name'] = $value->name;
            $cID['base'] = "Kelompok";
            $cID['dataId'] = $value->id;
            $cID['deletePage'] = $current_page.'/delete';
            $modal = $this->load->view('templates/actions/modal_delete_confirm',$cID ,true); 
            echo $modal;
        ?>
        <tr>
            <th scope="row"><?php echo $no?></th>
				<?php foreach ($table_header as $kh => $vh): ?>
					<td><?php echo $value->{$kh}?></td>
				<?php endforeach; ?>
            <td style="text-align:center">
                <button type="button" class="btn btn-sm btn-danger waves-effect" data-toggle="modal" data-target="#<?php echo $value->id ?>">X</button>
            </td>
        </tr>
        <?php
        endforeach;
    else:
    ?>
    <h3>Data Tidak Ditemukan</h3>
<?php endif; ?>