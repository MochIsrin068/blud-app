<div class="modal fade" id="<?php echo $dataId ?>" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="defaultModalLabel">Konfirmasi</h4>
			</div>
			<div class="modal-body">
				<?php echo form_open($deletePage) ?>
					<input type="hidden" value=<?php echo $dataId ?> name="id"/>
                    <div style="word-wrap: break-word;" class="alert alert-warning">
                        <div style="word-wrap: break-word !important;" >
                        Apa Anda Yakin Menghapus <?php echo $base ?> <b><?php echo $name?></b> ?
                        </div>
                    </div>
			</div>
			<div class="modal-footer">
					<button type="submit" class="btn btn-sm bg-green">YA</button>
				<?php echo form_close(); ?>
				<button type="button" class="btn btn-sm bg-cyan" data-dismiss="modal">BATAL</button>
			</div>
		</div>
	</div>
</div>	


