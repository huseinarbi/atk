<div id="<?php echo isset($id) ? $id : 'form-table-part' ?>" class="col-lg-12">
    <div class="form-group">
        <table class="table align-items-center table-flush responsive" style="width:100%; padding-top:15px">
                    <tr>
                        <?php foreach( $fields as $field ) : ?>
                            <th> <?php echo ucwords(str_replace('_',' ', $field)); ?></th>
                        <?php endforeach; ?>
                    </tr>
                    
                    <?php if ( isset( $data ) && ! empty( $data ) ) : ?>
                        <?php foreach ($data as $key => $values) : ?>
                             <tr>
                                <?php foreach ($values as $value) : ?>
                                    <?php echo '<td>'.$value.'</td>'; ?>
                                <?php endforeach; ?>
                                <td>
                                <a href="<?php echo  '/'. 'prediksi/'. 'detail/'. $values['id_barang']; ?>" class="btn btn-primary"><?php echo 'detail'; ?></a>
                                </td>
                             </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <!-- <tbody></tbody> -->
        </table>
    </div>
</div>
