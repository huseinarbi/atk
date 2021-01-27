<div id="<?php echo isset($id) ? $id : 'form-table-part' ?>" class="col-lg-12">
    <div class="form-group">
        <table class="table align-items-center table-flush responsive" style="width:100%; padding-top:15px">
                <?php foreach ($data as $field => $value) : ?>
                    
                        <?php if ( is_array( $value ) ) : ?>
                            <?php $xx = 1; ?>
                            <?php foreach( $value as $periode => $val ) : ?>   
                                <tr><td><?php echo 'periode-'.($xx.' ('.$periode.')'); ?></td><td><?php echo $val; ?></td></tr>
                                <?php $xx++; ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <thead>
                            <tr><th><?php echo $field; ?></th><th><?php echo $value; ?></th></tr>
                            </thead>
                        <?php endif; ?>
                        
                    <tbody>
                        <?php if ( is_array( $value ) ) : ?>
                            <?php foreach( $field as $val ) : ?>
                                
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                <?php endforeach; ?>
        </table>
    </div>
</div>
