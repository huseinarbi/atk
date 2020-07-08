<div id="<?php echo isset($id) ? $id : 'form-table-part' ?>" class="col-lg-12">
    <div class="form-group">
        <table class="table align-items-center table-flush responsive" style="width:100%; padding-top:15px">
            <tr>
                <thead>
                    <?php foreach( $fields as $field ) : ?>
                    <th>
                        <?php echo ucwords(str_replace('_',' ', $field)); ?>
                    </th>
                    <?php endforeach; ?>
                </thead>
                <tbody>
                    <?php foreach( $fields as $field ) : ?>
                    <th>
                        <?php echo ''; ?>
                    </th>
                    <?php endforeach; ?>
                </tbody>
            </tr>
        </table>
    </div>
</div>
