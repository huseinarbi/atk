<style>
    div.ex3 {
        white-space: nowrap;
        overflow-x: scroll; 
        overflow-y: hidden;
        
    }
</style>

<div class="card-body">
    <div class="tab-content">
        <a class="nav-link mb-sm-3 mb-md-0" > <i class="ni ni-cloud-upload-96 mr-2"></i><?php echo $label; ?></a>
        <div class='card-header border-0 ex3' style="border:1px solid black;display:inline-grid;">  
            <div class="table-responsive" style="margin-top: -27px;">
                <table id="table-target" class="table table-card align-items-center table-flush" data-title="Target" style="display: inline-flex;">
                    <?php foreach( $value['data'] as $key => $column ) : ?>
                    <tbody id='mapel-master' data-mapel-id="<?php echo $column['id_mapel'] ?>" data-tingkat="<?php echo $column['tingkat']; ?>" > 
                    <tr id="tr-target">
                        <td>
                            <div id="mapel-card-target" data-mapel-id="<?php echo $column['id_mapel'] ?>" style="width:120%">
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
                                            <span class="h2 font-weight-bold mb-0"><?php echo $column['mapel']?></span>
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
                    </tbody>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>