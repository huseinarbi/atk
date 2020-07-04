<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title><?php echo $title; ?></title>
		<!-- Favicon -->
		<link href="<?php asset_url( 'img/brand/favicon.png' ); ?>" rel="icon" type="image/png">
		<!-- Fonts -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
		<!-- Icons -->
		<link href="<?php asset_url( 'plugins/nucleo/css/nucleo.css' ); ?>" rel="stylesheet" />
		<link href="<?php asset_url( 'plugins/@fortawesome/fontawesome-free/css/all.min.css' ); ?>" rel="stylesheet" />
		<!-- CSS Files -->
		<link href="<?php asset_url( 'css/bundle.min.css' );?>" rel="stylesheet" />
		<link href="<?php asset_url( 'css/main.css' );?>" rel="stylesheet" />
		<link href="<?php asset_url( 'plugins/datatables/dist/css/datatables.css' );?>" rel="stylesheet" />
	</head>

	<body class="">
		<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
			<div class="container-fluid">
				<!-- Toggler -->
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<!-- Brand -->
				<a class="navbar-brand pt-0" href="<?php echo get_url(); ?>">
					<img src="<?php asset_url( 'img/brand/blue.png' );?>" class="navbar-brand-img" style="max-height: 3rem!important;" alt="...">
				</a>
				
				<a class="navbar-brand pt-0" style="display:flex;" href="<?php echo get_url(); ?>">
					<img src="<?php asset_url( 'img/theme/logo-man.png' );?>" class="navbar-brand-img" style="max-height: 2.5rem!important;" alt="...">
					<h3 class="mb-0" style="margin-top:7px; margin-left:4px;color:rgba(0,0,0,.5);">MAN 1 Magelang</h3>
				</a>
				<!-- User -->
				<ul class="nav align-items-center d-md-none">
					<li class="nav-item dropdown">
						<a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<div class="media align-items-center">
								<span class="avatar avatar-sm rounded-circle">
								<?php $logo = !empty(array_values(Flight::user('roles'))[0]) ? array_values(Flight::user('roles'))[0] : ''; ?>
										<img alt="Image placeholder" src="<?php asset_url( 'img/theme/team-4-800x800-'.strtolower($logo).'.jpg' ); ?>">
								</span>
							</div>
						</a>
						<div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
							<div class=" dropdown-header noti-title">
								<h6 class="text-overflow m-0">Welcome!</h6>
							</div>
							<div class="dropdown-divider"></div>
							<a href="<?php echo get_url( 'logout' ); ?>" class="dropdown-item">
								<i class="ni ni-user-run"></i>
								<span>Logout</span>
							</a>
						</div>
					</li>
				</ul>
				<!-- Collapse -->
				<div class="collapse navbar-collapse" id="sidenav-collapse-main">
					<!-- Collapse header -->
					<div class="navbar-collapse-header d-md-none">
						<div class="row">
							<div class="col-6 collapse-brand" style="display:flex;">
								<a class="navbar-brand pt-0" href="<?php echo get_url(); ?>">
									<img src="<?php asset_url( 'img/brand/blue.png' );?>" class="navbar-brand-img" alt="...">
								</a>
								<a class="navbar-brand pt-0 ml-1" style="display:flex;" href="<?php echo get_url(); ?>">
									<img src="<?php asset_url( 'img/theme/logo-man.png' );?>" class="navbar-brand-img" style="max-height: 2rem!important;" alt="...">
									<h3 class="mb-0" style="margin-top:4px; margin-left:4px;color:rgba(0,0,0,.5);font-size:15px;">MAN 1 Magelang</h3>
								</a>
							</div>
							<div class="col-6 collapse-close">
								<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
									<span></span>
									<span></span>
								</button>
							</div>
						</div>
					</div>
					<!-- Navigation -->
					<?php Flight::render( 'partials/menu' ); ?>
					<!-- Divider -->
					<hr class="my-3">
				</div>
			</div>
		</nav>
		<div class="main-content">
			<!-- Navbar -->
			<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
				<div class="container-fluid">
					<!-- Brand -->
					<span class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block"><?php echo $title; ?></span>
					<!-- Form -->

					<!-- User -->
					<ul class="navbar-nav align-items-center d-none d-md-flex">
						<li class="nav-item dropdown">
							<a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								<div class="media align-items-center">
									<span class="avatar avatar-sm rounded-circle">
										<?php $logo = !empty(array_values(Flight::user('roles'))[0]) ? array_values(Flight::user('roles'))[0] : ''; ?>
										<img alt="Image placeholder" src="<?php asset_url( 'img/theme/team-4-800x800-'.strtolower($logo).'.jpg' ); ?>">
									</span>
									<div class="media-body ml-2 d-none d-lg-block">
										<span class="mb-0 text-m  font-weight-bold"><?php echo ucwords(Flight::user('name')); ?></span>						
										<div>
										<span class="mb-0 text-sm" style="font-size:0.7rem!important"><?php echo Flight::role(); ?></span>
										</div>
									</div>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
								<div class=" dropdown-header noti-title">
									<h6 class="text-overflow m-0">Welcome!</h6>
								</div>
								<div class="dropdown-divider"></div>
								<a href="<?php echo get_url( 'logout' ); ?>" class="dropdown-item">
									<i class="ni ni-user-run"></i>
									<span>Logout</span>
								</a>
							</div>
						</li>
					</ul>
				</div>
			</nav>
			<!-- End Navbar -->
			<div class="header bg-gradient-primary pb-6 pt-5 pt-md-8">
				<?php if ( !empty( $message ) ): ?>
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

