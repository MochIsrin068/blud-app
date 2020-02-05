<div class="modal fade" id="<?php echo $dataId ?>" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="defaultModalLabel">Pembayaran</h4>
			</div>
			<div class="modal-body">
				<?php echo form_open($pay) ?>
					<input type="hidden" value=<?php echo $dataId ?> name="id"/>
					<!-- <input type="hidden" value=<?php echo $partyId ?> name="partyId"/> -->
                    <label for="nominal">Masukkan Nominal</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input name="nominal" type="number" id="nominal" class="form-control" placeholder="Rp. 200.000">
                        </div>
                    </div>
			</div>
			<div class="modal-footer">
					<button type="submit" class="btn btn-sm bg-green">BAYAR</button>
				<?php echo form_close(); ?>
				<button type="button" class="btn btn-sm bg-cyan" data-dismiss="modal">BATAL</button>
			</div>
		</div>
	</div>
</div>	


