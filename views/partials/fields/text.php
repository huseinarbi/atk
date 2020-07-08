<div class="col-lg-6">
	<div class="form-group">
		<label class="form-control-label" for="input-username"><?php echo $label; ?></label>
		<input class="form-control form-control-alternative" type="text" id="<?php echo $name; ?>" name="<?php echo $name; ?>"  placeholder="<?php echo $label; ?>" value="<?php echo !empty($data) ? $data : ''; ?>" <?php echo ($required) ? 'required="required"' : ''; ?> <?php echo $disable === true ? 'disabled' : ''; ?> <?php echo $editable === false ? 'readonly style="background: none;box-shadow: none;"' : '' ?> >
	</div>
</div>
