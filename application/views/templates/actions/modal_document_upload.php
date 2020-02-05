<div class="modal fade" id="<?php echo $partyId ?>" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="defaultModalLabel">Upload Document Akad</h4>
			</div>
			<div class="modal-body">
				<?php echo form_open_multipart($action) ?>
					<input type="hidden" value=<?php echo $dataId ?> name="id"/>
					<input type="hidden" value=<?php echo $partyId ?> name="idParty"/>
                    <input type="file" name="name" class="form-control" required/>
			</div>
			<div class="modal-footer">
					<button type="submit" class="btn btn-sm bg-green">Upload</button>
				<?php echo form_close(); ?>
				<button type="button" class="btn btn-sm bg-cyan" data-dismiss="modal">BATAL</button>
			</div>
		</div>
	</div>
</div>	


