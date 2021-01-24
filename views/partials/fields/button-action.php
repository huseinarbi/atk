<div class="col-lg-6">
	<div class="form-group">
		<label class="form-control-label" for="input-username" style="color:white;" ><?php echo $label; ?></label>

	    <div class="input-group">
            <a id="<?php echo $name; ?>" name="<?php echo $name; ?>" class="btn btn-success"><?php echo $label; ?></a>
            <?php 

            /* 
            <div class="input-group-prepend">
	            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
	        </div>
            <input class="form-control" placeholder="Select date" type="month" id="<?php echo $name; ?>" name="<?php echo $name; ?>"  <?php echo ($required) ? 'required="required"' : ''; ?> value="<?php echo !empty($data) ? $data : ''; ?>" >
            */
            ?>
	    </div>
	</div>
</div>