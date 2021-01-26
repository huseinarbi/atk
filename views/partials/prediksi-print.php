<?php Flight::render( 'partials/header' ); ?>
<style>

tbody td {white-space: pre-line;}
.card.shadow.table-card  {
	padding:40px;
}
</style>
<div class="container-fluid mt--7 container-print">
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

				<div class="card-body card-body-print">
					<div class="tab-content">
					<div class="card shadow">
					        <div class="tab-content" id="myTabContent">
								<div class="row">
									<div class="col">
										<?php if (isset($user_role) && $user_role == 'siswa' ) : ?>
											<div id="user-role" data-role="<?php echo $user_role; ?>"></div>
										<?php endif; ?>
										<div class="card shadow table-card">
											<div id="documentPrint">
												<table id = "tableRapor" data-title = "<?php echo $heading; ?>" data-bundle-css = "<?php echo asset_url( 'css/bundle.min.css' ); ?>" data-main-css = "<?php echo asset_url( 'css/main.css' ); ?>" class="table align-items-center table-flush table-print responsive" style="width:100%; padding-top:15px">
													<thead class = "headerTable">
															<tr>
																<th> <img src="<?php 	echo asset_url( 'img/theme/logo-man.png' ); ?>" style="width: 116px;"> </img> </th>
																<th> <h1>MAN 1 Magelang</h1>
																	Jl. Sunan Bonang No. 17
																	Karet Jurangombo Selatan,
																	Kec. Magelang Selatan
																	Kota Magelang, Jawa Tengah 56123
																	Telp.0293-362928 </th>
															</tr>
														</thead>
													<tbody>
													<?php if ( !empty( $sections ) ) : foreach( $sections as $section ) : ?>	
														<?php if ( !empty( $section['title'] ) ) : ?>
															<tr ><th colspan="2" class="section-title" style="text-align: center;"><div style="margin-top:10px;margin-bottom:10px;"> <?php echo !empty( $section['title'] ) ? $section['title'] : ''; ?> </div></th><th style="display: none;"></th> </tr> 
														<?php endif; ?>
														<?php if ( !empty( $section['fields'] ) ) : 
															foreach ( $section['fields'] as $key => $field ) : ?>
																<tr>  <th class="td-mobile" scope="row" style="width:250px;" ><?php echo $field['label']; ?> </th> <td  style="white-space: pre-line;"><?php echo !empty($field['data']) ? $field['data'] : '-' ; ?>   </td> </tr>
															<?php endforeach; ?>
														<?php endif; ?>
													<?php endforeach; endif; ?>
													</tbody>
												</table>	
											<div>
										<div>							
									</div>
					   		 	</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php Flight::render( 'partials/footer' ); ?>


