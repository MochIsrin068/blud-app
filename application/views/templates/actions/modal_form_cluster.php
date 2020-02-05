<?php
    $data = ( isset( $data ) && $data != NULL )? $data : '';
    $data_param = ( $data != '' )? ( isset( $data->$param ) ? $data->$param :''  )   : '';
?>
<button class="btn btn-bold btn-<?php echo $button_color?> btn-sm " style="margin-left: 5px;" data-toggle="modal" data-target="#<?php echo $modal_id.$data_param?>">
    <?php echo $name?>
</button>
<!-- Modal Delete-->
<div class="modal fade" id="<?php echo $modal_id.$data_param?>" role="dialog">
    <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <?php echo form_open( $url );?>
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo $name?></h4>
        </div>
        <div class="modal-body">
        <!--  -->
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="" class="control-label">Pilih Kecamatan</label>
                    <select name="kecamatan" id="kecamatan" class="form-control" >
                        <option value="Kendari">Kendari</option>
                        <option value="Kendari Barat">Kendari Barat</option>
                        <option value="Poasia">Poasia</option>
                        <option value="Abeli">Abeli</option>
                        <option value="Kambu">Kambu</option>
                        <option value="Mandonga">Mandonga</option>
                        <option value="Wua-wua">Wua - wua</option>
                        <option value="Puuwatu">Puuwatu</option>
                        <option value="Kadia">Kadia</option>
                        <option value="Baruga">Baruga</option>
                    </select>
                </div>

                
            </div>
            <!-- <?php 
            // $_data["form_data"] = $form_data;
            // $_data["data"] = $data;
            // $this->load->view('templates/form/bsb_form', $_data );  
            ?> -->
        <!--  -->
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn  btn-success">Ok</button>
            <button class="btn" data-dismiss="modal" aria-hidden="true">Batal</button>
        </div>
        <?php echo form_close(); ?>
    </div>
    </div>
</div>
<!--  -->