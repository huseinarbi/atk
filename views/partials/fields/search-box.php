<!-- <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name"> -->
<div class="col-lg-12">
<div class="col-lg-6">
	<div class="form-group">
		<label class="form-control-label" for="<?php echo $name; ?>"><?php echo $label; ?></label>
        <input id="search-box" class="form-control form-control-alternative" type="text" id="<?php echo $name; ?>" name="<?php echo $name; ?>"  placeholder="<?php echo $label; ?>" value="<?php echo !empty($data) ? $data : ''; ?>" <?php echo $disable === true ? 'disabled' : ''; ?> <?php echo $editable === false ? 'readonly style="background: none;box-shadow: none;"' : '' ?> >
       
        <ul id="container-list-barang" class="list-group custom-list-group" style="display:none;">
            <?php foreach ( $data_list as $list ) : ?>
                <li class="list-group-item custom-list-group-item list-item" data-search-id="<?php echo $list['search_id']; ?>" data-search-name="<?php echo $list['search_name']; ?>" data-harga-barang="<?php echo $list['harga_barang']; ?>" data-stok-barang="<?php echo $list['stok']; ?>"><p><?php echo $list['search_name']; ?></p><a id="add-to-cart" class="btn btn-lg btn-success" style="z-index: 2;">Tambahkan</a></li>
            <?php endforeach; ?>
        </ul>
       
	</div>
</div>
</div>