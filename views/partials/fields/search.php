<style>
* {
  box-sizing: border-box;
}

#myInput {
  background-image: url('/css/searchicon.png');
  background-position: 10px 12px;
  background-repeat: no-repeat;
  width: 100%;
  font-size: 16px;
  padding: 12px 20px 12px 40px;
  border: 1px solid #ddd;
  margin-bottom: 12px;
}

#myUL {
  list-style-type: none;
  padding: 0;
  margin: 0;
}

#myUL li a {
  border: 1px solid #ddd;
  margin-top: -1px; /* Prevent double borders */
  background-color: #f6f6f6;
  padding: 12px;
  text-decoration: none;
  font-size: 18px;
  color: black;
  display: block
}

#myUL li a:hover:not(.header) {
  background-color: #eee;
}
</style>
<div class="col-lg-6">
	<div class="form-group">
        <label for="example-search-input" class="form-control-label"><?php echo $label; ?></label>
        <?php if ( isset($required) && $required === true ) : ?>
          <div class="form-control" style="display: flex;justify-content: space-between;">
            <input class="form search" type="text" id="<?php echo $id; ?>" name="<?php echo $name ?>"  placeholder="<?php echo $label; ?>" value="<?php echo !empty($data) ? $data : ''; ?>" <?php echo ($required) ? 'required="required"' : ''; ?> <?php echo $editable === false ? 'readonly' : '' ?> style="border:none;" >
            <span><i id="icon-check" class="ni ni-check-bold" style="display:none;margin-top:5px;color:#2dce89;"></i></span>
          </div>
        <?php else : ?>
          <input class="form-control" id="<?php echo $id; ?>" placeholder="<?php echo $label; ?>" value="<?php echo !empty($data) ? $data : ''; ?>" <?php echo ($required) ? 'required="required"' : ''; ?> <?php echo $editable === false ? 'readonly' : '' ?> style="border:none;background:white;">
        <?php endif; ?>
        <?php if (isset($data_search) && !empty($data_search)) : foreach ($data_search as $key => $value) : ?>
        <ul class="<?php echo str_replace('_','',$id).'-list'; ?>" style="display:none;">
            <li class="list" data-search-id="<?php echo $value['search_id']; ?>" data-search-name="<?php echo $value['search_name']; ?>"><?php echo $value[$id]; ?></li>
        </ul>
        <?php endforeach; endif; ?>
    </div>
</div>