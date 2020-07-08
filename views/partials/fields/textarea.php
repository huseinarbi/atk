<div class="col-lg-6">
	<div class="form-group">
		<label><?php echo $label; ?></label>
		<textarea rows="4" class="form-control form-control-alternative" id="<?php echo $name; ?>" name="<?php echo $name; ?>" placeholder="<?php echo $label; ?>"  <?php echo ($required) ? 'required="required"' : ''; ?> <?php echo $editable === false ? 'readonly style="background: none;box-shadow: none;"' : '' ?>  ><?php echo !empty($data) ? $data : ''; ?></textarea>
	</div>
</div>