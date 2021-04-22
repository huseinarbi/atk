<?php Flight::render('partials/header'); ?>
<div class="container-fluid mt--7">
	<div class="header-body">
		<!-- Card stats -->
		<div>Haloo</div>
		<div class="row">
			<!-- Card -->
			<?php if (isset($card_view)) : foreach ($card_view as $key => $card) : ?>
					<?php
					$field	= isset($card['field']) ? $card['field'] : '';
					$type 	= isset($card['type']) ? $card['type'] : '';
					?>
					<?php if (!empty($table['data'])) : foreach ($table['data'] as $key => $value) : ?>
							<?php
							$count	= !empty(count($table['data'])) ? count($table['data']) : '';
							$text 	= !empty($value[$field['id']]) ? $value[$field['id']] : '';
							?>
					<?php endforeach;
					endif; ?>
					<?php if (!empty($count) && !empty($text)) : ?>
						<div class="col-xl-3 col-lg-6">
							<div class="card card-stats mb-4 mb-xl-0">
								<div class="card-body">
									<div class="row">
										<div class="col">
											<h5 class="card-title text-uppercase text-muted mb-0"><?php echo $field['title']; ?></h5>

											<span class="h2 font-weight-bold mb-0"><?php echo $type === 'count' ? (!empty($count) ? $count : '') : (!empty($text) ? $text : ''); ?></span>
										</div>
										<div class="col-auto">
											<div class="icon icon-shape bg-danger text-white rounded-circle shadow">
												<i class="<?php echo !empty($field['icon']) ? $field['icon'] : 'fas fa-chart-bar'; ?>"></i>
											</div>
										</div>
									</div>
									<p class="mt-3 mb-0 text-muted text-sm">
										<span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
										<span class="text-nowrap">Since last month</span>
									</p>
								</div>
							</div>
						</div>
					<?php endif; ?>
			<?php endforeach;
			endif; ?>
			<!-- Card -->
		</div>
	</div>
</div>
<?php Flight::render('partials/footer'); ?>