<div class="col-lg-6">
	<div class="form-group" style="display: inline-flex;margin-top:38px;">
		<label class="form-control-label" for="checkbox"><?php echo $label; ?></label>
		<label class="custom-toggle" style="display: flex;margin-left:30px">
			<input type="hidden" name="<?php echo $name; ?>" value="">
		    <input type="checkbox" id="checkbox-insert" value="" name="<?php echo $name; ?>" <?php echo isset($default) && ($default) ? 'checked' : ''; ?>>
		    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
		</label>
	</div>
</div>


