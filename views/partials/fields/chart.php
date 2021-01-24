<canvas id="myChart" max-width="75" max-height="75"></canvas>

<div id="<?php echo $name; ?>" <?php echo isset( $hidden ) && $hidden == true ? 'hidden=hidden' : ''; ?> >
    <label class="form-control-label" for="input-username">Data</label>    
    <input class="form-control form-control-alternative" type="text" id="data-pengambilan" name="<?php echo $name; ?>" value="<?php echo !empty($pengambilan) ? $pengambilan : ''; ?>" >
    <input class="form-control form-control-alternative" type="text" id="data-koefisien" name="<?php echo $name; ?>" value="<?php echo !empty($koefisien) ? $koefisien : ''; ?>" >
    <input class="form-control form-control-alternative" type="text" id="data-prediksi" name="<?php echo $name; ?>" value="<?php echo !empty($prediksi) ? $prediksi : ''; ?>" >
</div>