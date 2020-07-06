<!DOCTYPE html>
<html lang="en" style="height: 100%;">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>
		Sistem Pengolahan Informasi
	</title>
	<!-- Favicon -->
	<link href="<?php asset_url('img/brand/favicon.png'); ?>" rel="icon" type="image/png">
	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
	<!-- Icons -->
	<link href="<?php asset_url('plugins/nucleo/css/nucleo.css'); ?>" rel="stylesheet" />
	<link href="<?php asset_url('plugins/@fortawesome/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet" />
	<!-- CSS Files -->
	<link href="<?php asset_url('css/bundle.min.css'); ?>" rel="stylesheet" />
	<link href="<?php asset_url('css/main.css'); ?>" rel="stylesheet" />
</head>

<body class="bg-default" style="height: 100%;">
	<div class="main-content" style="height: 90%;">
		<!-- Header -->
		<div class="header bg-gradient-primary py-7 py-lg-8">
			<div class="container">
				<div class="header-body text-center mb-5">
					<div class="row justify-content-center">
						<div class="col-lg-5 col-md-6">
							<h1 class="text-white">Sistem Informasi<br>Title<br>Sub Title</h1>
							<p class="text-lead text-light">Silahkan masukkan username dan password anda untuk masuk aplikasi.</p>
						</div>
					</div>
				</div>
				<?php if (!empty($message)) : ?>
					<div class="row justify-content-center">
						<div class="col-lg-5 col-md-7">
							<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<span class="alert-icon"><i class="ni ni-notification-70"></i></span>
								<span class="alert-text"><?php echo $message; ?></span>
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<div class="separator separator-bottom separator-skew zindex-100">
				<svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
					<polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
				</svg>
			</div>
		</div>
		<!-- Page content -->
		<div class="container mt--8 pb-5">
			<div class="row justify-content-center">
				<div class="col-lg-5 col-md-7">
					<div class="card bg-secondary shadow border-0">
						<div class="card-body px-lg-5 py-lg-5">
							<form role="form" method="post">
								<div class="form-group mb-3">
									<div class="input-group input-group-alternative">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="ni ni-circle-08"></i></span>
										</div>
										<input class="form-control" placeholder="Username" type="text" name="username">
									</div>
								</div>
								<div class="form-group">
									<div class="input-group input-group-alternative">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
										</div>
										<input class="form-control" placeholder="Password" type="password" name="password">
									</div>
								</div>
								<div class="custom-control custom-control-alternative custom-checkbox">
									<input class="custom-control-input" id=" customCheckLogin" type="checkbox" name="remember">
									<label class="custom-control-label" for=" customCheckLogin">
										<span class="text-muted">Remember me</span>
									</label>
								</div>
								<div class="text-center">
									<button type="submit" class="btn btn-primary my-4">Login ke Aplikasi</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="text-center">
			<div>
				<p class="text-lead text-light"><small><i>- Edited by Husein Arbi -</small></i></p>
			</div>
		</div>
	</div>
	
	<!--   Core   -->
	<script src="<?php asset_url('plugins/jquery/dist/jquery.min.js'); ?>"></script>
	<script src="<?php asset_url('plugins/bootstrap/dist/js/bootstrap.bundle.min.js'); ?>"></script>
	<script src="<?php asset_url('js/main.js'); ?>"></script>
</body>

</html>