<?php
if ( empty($menu) ) {
	return;
}
?>
<!-- Navigation -->
<ul class="navbar-nav">
	<?php
	$request = Flight::request();
	foreach ($menu as $data) : ?>
	<?php 
		$br = isset($data['br']) && $data['br'] === true ? true : false;
	?>
	<li class="nav-item">
		<a class="nav-link <?php echo get_url( $request->url ) == $data['url'] ? 'active' : ''; ?>" href="<?php echo $data['url']; ?>">
			<i class="<?php echo @$data['icon']; ?> text-primary"></i> <?php echo $data['text']; ?>
		</a>
		<?php if ( $br === true ) : ?>
		<hr class="my-3" style="width:80%;">
		<?php endif; ?>
	</li>
	<?php endforeach; ?>
</ul>
