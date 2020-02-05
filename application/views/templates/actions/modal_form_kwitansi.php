<div class="modal fade" id="receiptkwitansi<?php echo$user_id;?>" role="dialog">
    <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <?php 
            $attributes = array('target' => '_blank');
            echo form_open($parent_page.'/generate_receipt/'.$user_id, $attributes);
        ?>
        <!-- <form method="post" action="<?php //echo '../generate_receipt/'.$user_id;?>" target="_blank"> -->
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">PENANDA TANGAN</h4>
            </div>
            <div class="modal-body">
            <!--  -->
                <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="form-group form-float"><br/>
                                <input type="hidden" class="form-control" name="party_id" value="<?php echo $party_id;?>"/>
                                <div class="form-line">
                                    <label for="" class="control-label">Kepala UPT</label>
                                    <input type="text" class="form-control" name="kepala_upt" placeholder="Nama Kepala UPT" aria-required="true">
                                </div><br/><br/>
                                <div class="form-line">
                                    <label for="" class="control-label">Bendahara</label>
                                    <input type="text" class="form-control" name="bendahara" placeholder="Nama Bendahara" aria-required="true">
                                </div>
                            </div>
                        </div>
                </div>

            <!--  -->
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Cetak</button>
                <button class="btn" data-dismiss="modal" aria-hidden="true">Batal</button>
            </div>
        <!-- </form> -->
        <?php echo form_close(); ?>
    </div>
    </div>
</div>