<?php Flight::render( 'partials/header' ); ?>

<div class="container-fluid mt--7">
	<div class="row">
		<div class="col">
			<div class="card bg-secondary shadow">
				<div class="card-header bg-white border-0">

					<?php Flight::renderMessage(); ?>

					<div class="row align-items-center">
						<div class="col-8">
							<h3 class="mb-0"><?php echo $heading; ?></h3>
						</div>
					</div>
				</div>

				<div class="card-body">
					<div class="tab-content">
						<form id="form-add" method="post">
						<div class="nav-wrapper">
						    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
						        <?php if ( !empty( $sections ) ) : foreach( $sections as $section ) : ?>
						        <li class="nav-item">
						            <a class="nav-link mb-sm-3 mb-md-0 <?php echo $sections[0] === $section ? 'active' : ''; ?>" id="tabs-icons-text-<?php echo str_replace(' ','-',$section['title']); ?>-tab" data-toggle="tab" href="#tabs-<?php echo str_replace(' ','-',$section['title']); ?>" role="tab" aria-controls="tabs-icons-text-<?php echo str_replace(' ','-',$section['title']); ?>" aria-selected="<?php echo $sections[0] === $section ? 'true' : 'false'; ?>"><i class="ni ni-cloud-upload-96 mr-2"></i><?php echo isset( $section['title'] ) ? $section['title'] : ''; ?></a>
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
					<hr class="my-4" />
					<div class="col-lg-12">
					<div class="col-lg-6" style="margin-left: 50%;">
						<?php if (isset($custom_button)) : ?>
							<div class="">
								<?php foreach( $custom_button as $key => $value ) : ?>
									<div>
										<button id="btn-<?php echo $value['id']?>" type="<?php echo $value['type']?>" class="btn btn-lg <?php echo $value['class']; ?> float-right" style="margin-right:10px;"><?php echo $value['label']?></button>
									</div>
									<?php endforeach; ?>
							</div>
						<?php else: ?>
							<div class="pl-lg-4">
									<button id="btn-submit" type="submit" class="btn btn-lg btn-success">Simpan</button>
							</div>
						<?php endif; ?>
					</div>
					</div>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php Flight::render( 'partials/footer' ); ?>
