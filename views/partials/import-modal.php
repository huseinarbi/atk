<div aria-hidden="true" aria-labelledby="import-xlsxLabel" class="modal fade" id="import-xlsx" role="dialog" tabindex="-1" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-md modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Import dari file xlsx</h5><button id="btn-close-import" aria-label="Close" class="close"  type="button"><span aria-hidden="true">&times;</span></button>
			</div>

			<div id="modalSheet" class="modal-header" style="display:none">
				<div class="nav-wrapper">
					<ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
						<?php if ( !empty( $import_mapel ) ) : ?>
							<li class="nav-item">
								<a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-penilaian-tab" data-toggle="tab" href="#tabs-penilaian" role="tab" aria-controls="tabs-icons-text-penilaian" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Options</a>
							</li>
						<?php endif; ?>
						<li class="nav-item">
							<a class="nav-link mb-sm-3 mb-md-0 <?php echo empty($import_mapel) ? 'active' : ''; ?>" id="tabs-icons-text-sheet-tab" data-toggle="tab" href="#tabs-sheet" role="tab" aria-controls="tabs-icons-text-sheet" aria-selected="<?php echo empty($import_mapel) ? 'true' : 'false'; ?>"><i class="ni ni-cloud-upload-96 mr-2"></i>Sheet Setting</a>
						</li>
					</ul>
				</div>	

				<div class="form-group">
				  	<div class="tab-content" id="myTabContent">

						<!--   Tabs Penilaian   -->
						<?php if ( !empty( $import_mapel ) ) : ?>
							<div class="tab-pane fade show active" id="tabs-penilaian" role="tabpanel" aria-labelledby="tabs-icons-text-penilaian-tab">
								<div class="import-nilai">
									<?php foreach ( $import_mapel as $key => $option_data ) : ?>
									<?php
										$select_id 		= $key;
										$select_label	= ucwords(str_replace('_',' ', $key));
									?>
										<div style="padding: 10px;">
											<label class="form-control-label" for="<?php echo $select_id; ?>"><?php echo $select_label; ?></label>
										</div>
										<select class="form-control" id="<?php echo $select_id.'_select'; ?>">
											<option value="" hidden="hidden">Select <?php echo $select_label; ?></option> 
											<?php foreach ( $option_data as $option_key => $option ) : ?>
												<option id="option<?php echo $option['id']; ?>" value="<?php echo $option['id']; ?>" data-tingkat="<?php echo isset($option['tingkat']) ? $option['tingkat'] : ''; ?>" ><?php echo $option['title']; ?></option>
											<?php endforeach; ?>
										</select>
									<?php endforeach; ?>
									<div id="range-penilaian" style="display:none;">
										<div style="padding: 10px;">
											<label class="form-control-label" for="sheetSelect">First "Penilaian Ke - "</label>
										</div>
										<input id="min-penilaian" class="form-control form-control-alternative" type="number" value="1">
										<i><small>Ex. if you set 3. (Penilaian ke - 3, Penilaian ke - 4, etc ..) until the record empty</small></i>
									</div>
								</div>	
							</div>
						<?php endif; ?>
						<!--   Tabs Sheet   -->
						<div class="tab-pane fade <?php echo empty($import_mapel) ? 'show active' : ''; ?>" id="tabs-sheet" role="tabpanel" aria-labelledby="tabs-icons-text-sheet-tab">
							<div style="padding: 10px;">
								<label class="form-control-label" for="sheetSelect">Sheet</label>
							</div>
							<select class="form-control" id="sheetSelect" name="sheetSelect">
								<option value="" hidden="hidden">Select Sheet</option> 
							</select>
							<div style="padding: 10px;display:flex">
								<input type="checkbox" id="sheetHeaderCheck" name="sheetHeaderCheck" checked style="margin-top: 3px;">
								<label class="form-control-label" for="sheetHeaderCheck" style="margin-left:10px;">Disable Header</label>
								<input type="checkbox" id="sheetRowCheck" name="sheetRowCheck" style="margin-top: 3px;margin-left:20px;">
								<label class="form-control-label" for="sheetRowCheck" style="margin-left:10px;">Without First Column (Ex. No)</label>
							</div>
							<div style="padding: 10px;">
								<label class="form-control-label" for="sheetSelect">Total Header to Disable</label>
								<input id="total-header" class="form-control form-control-alternative" type="number"  value="1">
							</div>
						</div>	
				  	</div>
				</div>
			</div>

			<div class="modal-body">
				<div class="file-drop-area">
					<span class="fake-btn">Pilih file</span>
					<span class="file-msg">atau seret file kesini</span>
					<input class="file-input" type="file" id="input-xls">
				</div>
				<div id="workbook" style="max-height: 500px;overflow: auto;"></div>
			</div>
			<div class="modal-footer">
				<button id="do-import" class="btn btn-success btn-lg float-right" type="button" style="display:none">Proses</button>
			</div>
		</div>
	</div>
</div>
