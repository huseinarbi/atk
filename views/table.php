<?php Flight::render('partials/header'); ?>

<div class="container-fluid mt--7">
	<!-- Table -->
	<div class="row">
		<div class="col">
			<div class="card shadow table-card">
				<div class="card-header border-0">

					<?php Flight::renderMessage(); ?>

					<div class="float-left">
						<h3 class="mb-0"><?php echo $heading; ?></h3>
					</div>
					<?php if (isset($add)) : ?>
						<div class="float-right button-wrapper">
							<?php if (isset($btn_download)) : ?>
								<?php foreach ($btn_download as $download) : ?>
									<a href="<?php asset_url($download['url']); ?>" id="<?php echo $download['id']; ?>" class="btn btn-info">
										<span class="btn-inner--icon"><i class="ni ni-cloud-download-95"></i></span>
										<span class="btn-inner--text"><?php echo $download['string']; ?></span>
									</a>
								<?php endforeach; ?>
							<?php endif; ?>

							<?php if (!isset($disable_import)) : ?>
								<a href="javascript:;" id="btn-import" class="btn btn-icon btn-warning">
									<span class="btn-inner--icon"><i class="ni ni-cloud-upload-96"></i></span>
									<span class="btn-inner--text">Import</span>
								</a>
							<?php endif; ?>
							<?php if (isset($more_add)) : ?>
								<a href="<?php echo $base_url . '/add'; ?>" class="btn btn-success"><?php echo $more_add; ?></a>
							<?php endif; ?>

							<?php if ($add !== false && !is_array($add)) : ?>
								<a href="<?php echo $base_url . '/add'; ?>" class="btn btn-success"><?php echo $add; ?></a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="table-responsive">
					<?php if (!empty($table)) : ?>
						<table class="table align-items-center table-flush responsive">
							<thead class="thead-light">
								<tr>
									<?php foreach ($table['cols'] as $col) : ?>
										<?php if (is_array($col)) : ?>
											<?php foreach ($col as $sub_col) : ?>
												<?php if ($sub_col == 'ID') : ?>
													<th scope="col" style="display:none;"><?php echo $sub_col; ?></th>
												<?php else : ?>
													<th scope="col"><?php echo $sub_col; ?></th>
												<?php endif; ?>
											<?php endforeach; ?>
										<?php else : ?>
											<?php if ($col == 'ID') : ?>
												<th scope="col" style="display:none;"><?php echo $col; ?></th>
											<?php else : ?>
												<th scope="col"><?php echo $col; ?></th>
											<?php endif; ?>
										<?php endif; ?>
									<?php endforeach; ?>
									<th scope="col"></th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($table['data'])) : foreach ($table['data'] as $data) : ?>
										<tr>
											<?php foreach ($table['cols'] as $col_key => $col_name) : ?>

												<?php if (is_array($col_name)) : ?>
													<?php foreach ($col_name as $key => $value) : ?>
														<?php if ($value == 'ID') : ?>
														<td style="display:none;" scope="row" data-label="<?php echo $value; ?>">
															<?php echo $data[$col_key]; ?>
														</td>
														<?php else: ?>
														<td scope="row" data-label="<?php echo $value; ?>">
															<?php echo $data[$col_key]; ?>
														</td>
														<?php endif; ?>
													<?php endforeach; ?>
												<?php else : ?>
													<?php if ($col_name == 'ID') : ?>
													<td style="display:none;" scope="row" data-label="<?php echo $col_name; ?>">
														<?php echo $data[$col_key]; ?>
													</td>	
													<?php else: ?>
													<td scope="row" data-label="<?php echo $col_name; ?>">
														<?php echo $data[$col_key]; ?>
													</td>
													<?php endif; ?>
												<?php endif; ?>
											<?php endforeach; ?>

											<?php if (isset($custom_btn) ) : ?>
												<?php if (is_array($custom_btn)) : ?>
													<td class="text-right">
														<?php foreach ($custom_btn as $btn_key => $btn) : ?>
															<?php
																$target_url = isset($btn['url']) ? $btn['url'].'/' : '';
																$target_action 	= isset($btn['action']) ? $btn['action'].'/' : '';
																$custom_class 	= isset($btn['class']) ? 'class="'.'btn '.$btn['class'].'"' : 'class="btn btn-primary"';
															?>
															<a href="
																<?php echo $base_url . '/' 
																. $target_url
																. $target_action 
																. $data[$table['key']]; ?>" 
																<?php echo $custom_class; ?>
															><?php echo $btn['title']; ?></a>
														<?php endforeach; ?>
													</td>
												<?php endif; ?>
											<?php else : ?>
												<td class="text-right">
													<?php if (isset($more_btn)) : ?>
														<?php foreach ($more_btn as $more_key => $btn) : ?>
															<?php
																$target_url 	= isset($btn['url']) ? $btn['url'].'/' : '';
																$target_action 	= isset($btn['action']) ? $btn['action'].'/' : '';
																$custom_class 	= isset($btn['class']) ? 'class="'.'btn '.$btn['class'].'"' : 'class="btn btn-primary"';
															?>
															<a href="
																<?php echo $base_url . '/' 
																. $target_url
																. $target_action 
																. $data[$table['key']]; ?>" 
																<?php echo $custom_class; ?>
															><?php echo $btn['title']; ?></a>
														<?php endforeach; ?>
													<?php endif; ?>
													<a href="<?php echo $base_url . '/edit/' . $data[$table['key']]; ?>" class="btn btn-primary">Edit</a>
													<a data-action="delete" href="<?php echo $base_url . '/delete/' . $data[$table['key']]; ?>" class="btn btn-warning">Hapus</a>
												</td>
											<?php endif; ?>
										</tr>
									<?php endforeach;
								else : ?>
									<tr>
										<td class="text-center" colspan="<?php echo count($table['cols']); ?>">Belum ada data.</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					<?php endif; ?>
				</div>
				<?php
				Flight::render('partials/pagination', array(
					'base'  => $base_url,
					'page'  => $table['pagination']['page'],
					'total' => $table['pagination']['total']
				));
				?>
			</div>
			<?php echo !empty($table['error_catch']) ? '<i><small> Warning : ' . $table['error_catch'] . '</i>' : ''; ?>
		</div>
	</div>
</div>

<?php Flight::render('partials/import-modal', array(
	'import_mapel' => isset($add) && is_array($add) ? $add : ''
)); ?>

<?php Flight::render('partials/footer'); ?>