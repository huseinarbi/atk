<div class="col-lg-6" style="<?php echo isset( $style ) ? $style : 'margin-left: 50%'; ?>">
	<div class="form-group" style="display: flex;">
		<label class="form-control-label" style="white-space: nowrap;margin-top: auto;margin-right: 15px;" for="<?php echo $name; ?>"><?php echo $label; ?></label>
		<input class="form-control form-control-alternative" type="text" id="<?php echo $name; ?>" name="<?php echo $name; ?>"  placeholder="<?php echo $label; ?>" value="<?php echo !empty($data) ? $data : ''; ?>" <?php echo ($required) ? 'required="required"' : ''; ?> <?php echo $disable === true ? 'disabled' : ''; ?> <?php echo $editable === false ? 'readonly style="background: none;box-shadow: none;"' : '' ?> >
	</div>
</div>
