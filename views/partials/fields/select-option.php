<div class="col-lg-6">
	<div class="form-group">
		<label class="form-control-label" for="<?php echo $name; ?>"><?php echo $label; ?></label>
		<select class="form-control" id="<?php echo $name; ?>" name="<?php echo $name; ?>"  <?php echo ($required) ? 'required="required"' : ''; ?> <?php echo $disable === true ? 'disabled' : ''; ?> >
		  <?php foreach ( $value as $key => $option_data ) : ?> 
			<?php if ( $default === false ) : ?>
				  <option value="" hidden="hidden"><?php echo $label; ?></option> 
			<?php endif; ?>
		  	<option id="<?php echo $option_data['id']; ?>" name="<?php echo $option_data['id']; ?>" value="<?php echo $option_data['id']; ?>" <?php echo !empty($data) && $data === $option_data['id'] ? 'selected' : ''; ?> ><?php echo $option_data['values']; ?></option>
	      <?php endforeach; ?>
		</select>
	</div>
</div>