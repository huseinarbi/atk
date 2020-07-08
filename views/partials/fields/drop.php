<div class="card-body">
    <div class="tab-content">
        <a class="nav-link mb-sm-3 mb-md-0" > <i class="ni ni-cloud-upload-96 mr-2"></i><?php echo $label; ?></a>
        <div class='card-header-target border-0' style="display:contents;">  
            <div class="table-responsive">
                <table id="table-target" class="table align-items-center table-flush">
                    <tbody id="mapel-target">
                        <?php //echo '<pre>'; print_r($data); ?>
                        <?php foreach( $data['data'] as $key => $column ) : ?> 
                        <tr id="tr-target">
                            <td>
                                <div id="mapel-card-target" data-tingkat="<?php echo $column['tingkat']; ?>" data-mapel-id ="<?php echo $column['id_mapel']?>" data-id-mengajar="<?php echo !empty($column['id_mengajar']) ? $column['id_mengajar'] : $column['id_belajar']; ?>" >
                                <div class="card card-stats mb-4 mb-xl-0">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-auto">
                                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                                        <i class="fas fa-chart-bar"></i>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <h5 class="card-title text-uppercase text-muted mb-0"><?php echo $column['peminatan']; ?></h5>
                                                    <span id="mapel-title" class="h2 font-weight-bold mb-0"><?php echo $column['mapel']?></span>
                                                </div>
                                            </div>
                                            <p class="mt-3 mb-0 text-muted text-sm">
                                                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i><?php echo 'Tingkat'.$column['tingkat']; ?></span>
                                                <span class="text-nowrap"><?php echo $column['kelompok']; ?></span>
                                            </p>
                                        </div>
                                </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>