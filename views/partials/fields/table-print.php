<?php

if ( isset( $_REQUEST ) && ! empty( $_REQUEST ) ) {
	$query_string = '?'.http_build_query($_REQUEST,'','&');
}

?>
<div id="<?php echo isset($id) ? $id : 'form-table-part' ?>" class="col-lg-12">
    <div class="form-group">
        <table id="<?php echo $table_id; ?>"" class="table align-items-center table-flush responsive" style="width:100%; padding-top:15px">
                    
        <thead>
            <tr>
                <?php foreach( $fields as $field ) : ?>
                    <th> <?php echo ucwords(str_replace('_',' ', $field)); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>

        <tbody>
    
            <?php if ( isset( $data ) && ! empty( $data ) ) : ?>
                <?php foreach ($data as $key => $values) : ?>
                        <tr>
                        <?php foreach ($values as $value) : ?>
                            <?php echo '<td>'.$value.'</td>'; ?>
                        <?php endforeach; ?>
                        <td>
                        <a href="<?php echo  '/'. 'prediksi/'. 'detail/'. $values['id_barang'].$query_string; ?>" class="btn btn-primary"><?php echo 'detail'; ?></a>
                        </td>
                        </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
                
        </table>
    </div>
</div>
