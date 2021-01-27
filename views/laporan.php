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
                
                <div class="nav-wrapper">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row m-4" id="tabs-icons-text" role="tablist">
                        <?php if ( !empty( $sections ) ) : foreach( $sections as $section ) : ?>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 <?php echo strtolower( $section['title'] ) == $active_section ? 'active' : ''; ?>" id="tabs-icons-text-<?php echo str_replace(' ','-',$section['title']); ?>-tab" href="<?php echo get_url().'laporan'.strtolower('/'.$section['title']); ?>" aria-controls="tabs-icons-text-<?php echo str_replace(' ','-',$section['title']); ?>" aria-selected="<?php echo $sections[0] === $section ? 'true' : 'false'; ?>"><i class="ni ni-cloud-upload-96 mr-2"></i><?php echo isset( $section['title'] ) ? $section['title'] : ''; ?></a>
                        </li>
						<?php endforeach; endif; ?>
						
                    </ul>
				</div>
				
				<div class="card shadow">
					<div class="card-body">
						<div class="tab-content" id="myTabContent">
							<?php if ( !empty( $sections ) ) : foreach( $sections as $section ) : ?>
								<div class="tab-pane fade <?php echo $sections[0] === $section ? 'show active' : ''; ?>" id="tabs-<?php echo str_replace(' ','-',$section['title']); ?>" role="tabpanel" aria-labelledby="tabs-icons-text-<?php echo str_replace(' ','-',$section['title']); ?>-tab">
									<div class="row">
										<?php
										foreach ($section['fields'] as $field) {
											$field['disable'] = isset($field['disable']) && $field['disable'] === true ? true : false;
											$field['default'] = isset($field['default']) && $field['default'] === true ? true : false;
											$field['editable'] = isset($field['editable']) && $field['editable'] === false ? false : true;
											try {
												echo isset($field['full']) && $field['full'] === false ? '<div class="col-lg-12">' : '';
												Flight::render( 'partials/fields/' . $field['type'], $field );
												echo isset($field['full']) && $field['full'] === false ? '</div>' : '';
											} catch (Exception $e) {}
										}
										?>
									</div>
									</div>
							<?php endforeach; endif; ?>
						</div>
					</div>
				</div>

				<div class="table-responsive">
					<?php if (!empty($table)) : ?>
						<table class="table align-items-center table-flush responsive">
							<thead class="thead-light">
								<tr>
									<?php foreach ( !empty( $table['cols_view'] ) ? $table['cols_view'] : $table['cols'] as $col) : ?>
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
											<?php foreach (!empty( $table['cols_view'] ) ? $table['cols_view'] : $table['cols'] as $col_key => $col_name) : ?>

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
					'base'  => isset( $base ) ? $base : $base_url,
					'page'  => $table['pagination']['page'],
					'total' => $table['pagination']['total']
				));
				?>
			</div>
			<hr class="my-4" />
			<?php echo !empty($table['error_catch']) ? '<i><small> Warning : ' . $table['error_catch'] . '</i>' : ''; ?>
		</div>
	</div>
</div>

<?php Flight::render('partials/import-modal', array(
	'import_mapel' => isset($add) && is_array($add) ? $add : ''
)); ?>

<?php Flight::render('partials/footer'); ?>