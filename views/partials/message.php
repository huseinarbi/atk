<div class="alert alert-<?php echo isset( $type ) && 'error' !== $type ? $type : 'danger'; ?> alert-dismissible fade show" role="alert">
	<span class="alert-icon"><i class="ni ni-notification-70"></i></span>
	<span class="alert-text"><?php echo $message; ?></span>
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">×</span>
	</button>
</div>
