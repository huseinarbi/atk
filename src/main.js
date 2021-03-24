import XLSX from 'xlsx';
import CHART from 'chart.js';
import $ from 'jquery';
import { Printd } from 'printd';

// load scss
import './styles/main.scss';

class ATK {

    constructor() {

        this.setupPlugins();
        let searchcontainer = $(document).find('.searchid-list');

        if(searchcontainer.length > 0 ){
            this.searchList();
        }

        // Delete table data
        $( document ).on( 'click', '[data-action="delete"]', this.promptDelete );

        // import
        $( document ).on('click', '#btn-import', e => $('#import-xlsx').modal('show'));
        $( document ).on('hidden.bs.modal', '#import-xlsx', e => $('#import-xlsx #workbook').html(''));
        $( document ).on('show.bs.modal', '#import-xlsx', e => $('#import-xlsx').removeClass('loaded'));
        $( document ).on('dragenter focus click', '#input-xls', this.setActive);
        $( document ).on('dragleave blur drop', '#input-xls', this.setInctive);
        $( document ).on('change', '#input-xls', e => this.doParse(e.target));
        $( document ).on( 'click', '#btn-close-import', this.modalClose);

        // $( document ).on('click', '#checkbox-insert', this.checkboxModal);
        // $( document ).on('click', '#btn-submit', this.checkRequired)

        //cart
        $( document ).on( 'keyup' , '#search-box', this.searchBox );
        $( document ).on( 'click', '#add-to-cart', this.addToCart );
        $( document ).on( 'click', '.remove-current-item', this.remove_current_item );
        // $( document ).on( 'keyup', '#jumlah-cart', this.validate_stok_barang );
        
        $( document ).on( 'change', '#jumlah-cart', this.changeTotal );
        $( document ).on( 'change', '#harga-barang', this.changeTotal );
        // $(document).on( 'click', '#add-to-cart', this.calculateTotal );

        $( document ).on( 'click', '#btn-sumbit-save-cart', this.save_cart );
        $( document ).on( 'click', '#save-tutup-buku', this.save_tutup_buku );

        //report
        $( document ).ready(this.viewDataTable);
        $( document ).on( 'change', '#date-periode-prediksi', this.changePeriode );
        $( document ).on( 'change', '#date-periode-tutup-buku', this.changePeriode );
        $( document ).on( 'change', '#type-prediksi-option', this.changeTypePeriode );

        //print
        $( document ).on('click', '#btn-print', this.documentPrint);

        $( document ).on( 'click', '#search-box', function() {
            $('#container-list-barang').show();
        } );

        $( document ).on( 'focusout', '#search-box', function( event ) {
            $('#container-list-barang').hide('slow');
        } );

        $(document).on( 'keyup', '#jumlah-cart', function(e) {
            let default_stok = $(this).attr('max');
            console.log(e.target.value);
            console.log(default_stok);
            if (e.target.value > default_stok) {
              Swal.fire({
                icon    : 'error',
                title   : 'Oops...',
                text    : 'Stok barang sisa '+default_stok,
              });
              this.value = default_stok;
            } else if (e.target.value.length && e.target.value <= 0) {
              this.value = 1;
            }
      });

        this.load_chart();
        this.check_data_tutup_buku();
    }

    validate_stok_barang(e) {
        let default_stok    = $(this).attr('max');
        let jumlah          = e.target.value;

        if ( parseInt( jumlah ) > parseInt( default_stok ) ) {
            Swal.fire( {
                icon    : 'error',
                title   : 'Oops...',
                text    : 'Stok barang sisa '+default_stok,
            } );
            this.value = default_stok;

        } else if ( jumlah.length && jumlah <= 0 ) {
            this.value = 1;
        }
    }

    check_data_tutup_buku() {
        var periode = document.getElementById( 'date-periode-tutup-buku' );

        if ( periode != null ) {
            if ( this.getParameterByName('periode') == null ) {
                $.ajax({
                    type    : 'POST',
                    dataType: 'JSON',
                    url     : location.href,
                    data    : {
                        action  : 'check_tutup_buku'
                    },
                })
                .done( function( response ) {
                    var queryParams = new URLSearchParams( window.location.search );
                    queryParams.set( "periode", response.data );
                    history.replaceState( null, null, "?"+queryParams.toString() );
                    document.location.reload();
                });
            }
        }
    }

    save_tutup_buku() {
        let date  = $(document).find('#date-periode-tutup-buku').val();

        Swal.fire({
            text                : 'Apakah anda yakin akan melakukan tutup buku periode '+date+'?',
            icon                : 'warning',
            showCancelButton    : true,
            reverseButtons      : true,
            confirmButtonColor  : '#fb6340',
            cancelButtonColor   : '#adb5bd',
            cancelButtonText    : 'Batalkan',
            confirmButtonText   : 'Ya, Tutup Buku!'
        }).then( ( result ) => {
            if ( result.value ) {
                $.ajax( {
                    type        : 'POST',
                    dataType    : 'JSON',
                    url         : location.href,
                    data        : {
                        action    : 'save_tutup_buku',
                        date      : date,
                    },
                } )
                .done( function( response ) {
                    if ( response.success == false ) {

                        Swal.fire({
                            icon    : 'error',
                            title   : 'Oops...',
                            text    : response.message,
                        });
            
                    } else {
                        Swal.fire({
                            icon    : 'success',
                            title   : response.message,
                            showConfirmButton: false,
                            timer   : 3000
                        });
                        
                        setTimeout(() => {
                            document.location.reload();
                        }, 2000);
                    }
                });    
            }
        })  
    }

    getParameterByName(name, url = window.location.href) {
        name    = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    changeTypePeriode() {
        var queryParams = new URLSearchParams(window.location.search);
        queryParams.set("type", $(this).val());
        history.replaceState(null, null, "?"+queryParams.toString());
        document.location.reload();
    }

    changePeriode() {
        var queryParams = new URLSearchParams(window.location.search);
        queryParams.set("periode", $(this).val());
        history.replaceState(null, null, "?"+queryParams.toString());
        document.location.reload();
    }

    load_chart() {
        var ctx = document.getElementById('myChart');

        if ( ctx == null ) {
            return;
        }

        let new_item    = [];
        let regresi     = [];
        var ctx         = document.getElementById('myChart').getContext('2d');
        let pengambilan = $(document).find( '#data-chart' ).find( '#data-pengambilan' ).val();
        let koefisien   = $(document).find( '#data-chart' ).find( '#data-koefisien' ).val();
        let prediksi    = $(document).find( '#data-chart' ).find( '#data-prediksi' ).val();
        
        pengambilan     = pengambilan.split( ';' );
        koefisien       = { 'x' : 0, 'y' : koefisien };

        new_item.push(koefisien);
        pengambilan.forEach( function( item, index, arr ) {
            let xx = { 'x' : index+1, 'y' : item };
            new_item.push(xx);
        } );

        regresi         = [ koefisien, { 'x' : new_item.length - 1, 'y' : prediksi } ];

        var scatterChart = new Chart(ctx, {
            type: 'scatter',
            data: {
                datasets: [{
                    label: 'Pengambilan',
                    data: new_item,
                    backgroundColor: 'rgba(0, 129, 255, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },{
                    label : 'Regresi',
                    type: 'line',
                    data: regresi,
                    borderColor: 'rgba(255, 99, 71, 0.8)',
                    borderDash: [5],
                    fill: false,
                }]
            },
            options: {
                scales: {
                    xAxes: [{
                    ticks: {
                        min: 0,
                        stepSize: 1
                    },
                    }],
                    yAxes: [{
                    ticks: {
                        min: 0,
                        stepSize: 5
                    },
                    }]
                }
            }
        });
    }

    remove_current_item() {
        $( this ).closest( 'tr' ).remove();

        let $table1 = $('.table');
        var sum     = 0;

        $table1.find( 'tbody tr' ).each( function() { 
            $( this ).find( 'th:last' ).each( function() {
                if ( !isNaN( Number( $( this ).text() ) ) ) {
                    sum = sum + Number( $( this ).text() );
                }
            });
        });

        $( document ).find( '#total_harga' ).val( sum );
    }

    save_cart() {
        const headers = Array.from(
            document.querySelectorAll( '.table tr:first-child th' ),
            th => th.textContent.trim()
        );

        // Make an empty array for every item in headers:
        const data = Array.from( headers, () => [] );

        for ( const tr of document.querySelectorAll( '.table tr:nth-child(n + 2)' ) ) {
            [...tr.children].forEach((th, i) => {
                data[i].push(th.textContent.trim());
            });
        }

        let id_pegawai      = $(document).find('#id_pegawai').val();
        let jenis_transaksi = $(document).find('#jenis_transaksi').val();
        
        $.ajax({
            type    : 'POST',
            dataType: 'JSON',
            url     : location.href,
            data    : {
                action      : 'save_cart',
                jenis_transaksi : jenis_transaksi,
                id_pegawai  : id_pegawai,
                data        : {
                'headers' : headers,
                'value'   : data
                }
            },
        })
        .done(function(response) {

            if ( response.success == false ) {

                Swal.fire({
                icon    : 'error',
                title   : 'Oops...',
                text    : response.message,
                });
                

            } else {
                Swal.fire({
                icon: 'success',
                title: response.message,
                showConfirmButton: false,
                timer: 3000
                });
                
                setTimeout(() => {
                document.location.reload();
                }, 2000);
            }
        
        });
    }

    changeTotal() {
        let jumlah, harga, jenis_transaksi, $container_total;

        jenis_transaksi     = $(document).find('#jenis_transaksi').val();
        $container_total    = $(this).closest('tr').find('th:last');
        jumlah              = $(this).closest('tr').find('#jumlah-cart').val();

        if ( jenis_transaksi == 'pengambilan' ) {
            harga   = $(this).closest('tr').find('#harga').html();
        }

        if ( jenis_transaksi === 'penambahan' ) {
            harga   = $(this).closest('tr').find('#harga').find('#harga-barang').val();
        }

        $(this).closest('tr').find('.jumlah-hidden').html(jumlah);

        $container_total.html(jumlah*harga);

        let $table1 = $('.table');
        var sum     = 0;

        $table1.find('tbody tr').each(function(){
            $(this).find('th:last').each(function(){
                if(!isNaN(Number($(this).text()))){
                    sum=sum+Number($(this).text());
                }
            });
        });

        $(document).find('#total_harga').val(sum);

    }

    addToCart() {
        
        let id, name, harga, jumlah, default_number, default_harga, total, removeItem, jenis_transaksi, default_stok;
        let data = [];
        
        default_number  = 1;
        default_harga   = $(this).closest('.list-item').data('harga-barang');
        default_stok    = $(this).closest('.list-item').data('stok-barang');

        if ( default_stok <= 0 ) {
        Swal.fire({
            icon    : 'error',
            title   : 'Oops...',
            text    : 'Stok barang sisa '+default_stok,
        });

        return;
        }
        
        jenis_transaksi  = $(document).find('#jenis_transaksi').val();

        id      = $(this).closest('.list-item').data('search-id');
        name    = $(this).closest('.list-item').data('search-name');
        jumlah  = '<input id="jumlah-cart" type="number" class="form-control" style="width: 115px;" id="quantity" name="quantity" min="1" max="'+default_stok+'" value="'+default_number+'"><p class="jumlah-hidden hidden">'+default_number+'</p>';
        
        if ( jenis_transaksi == 'pengambilan' ) {
        harga   = default_harga;
        total   = default_number*harga;
        }

        if ( jenis_transaksi == 'penambahan' ) {
        harga   = '<input id="harga-barang" type="number" class="form-control" style="width: 115px;" id="price" name="price" min="1" value="'+default_harga+'"><p class="harga-hidden hidden">'+default_harga+'</p>';
        total   = default_number*default_harga;
        }
        
        
        removeItem  = '<button type="button" class="close remove-current-item" aria-label="Close"><span aria-hidden="true">&times;</span></button>';

        data  = [id, name, harga];

        $('.table').append('<tr class="row-data"><th>'+removeItem+'</th>><th class="id-data">'+id+'</th><th>'+name+'</th><th class="count-data">'+jumlah+'</th><th><div id="harga">'+harga+'</div></th><th>'+total+'</th></tr>');

        let $table1 = $('.table');
        var sum = 0;
        $table1.find('tbody tr').each(function(){
        $(this).find('th:last').each(function(){
            if(!isNaN(Number($(this).text()))){
            sum=sum+Number($(this).text());
            }
        });
        });

        $(document).find('#total_harga').val(sum);
        
    }

    searchBox() {
        var input, filter, ul, li, a, i, txtValue;

        if ( $(document).find('#search-box').is(":visible") ) {
            input   = document.getElementById("search-box");
            filter  = input.value.toUpperCase();
            ul      = document.getElementById("container-list-barang");
            li      = ul.getElementsByTagName("li");

            for (i = 0; i < li.length; i++) {
                a           = li[i].getElementsByTagName("a")[0];
                txtValue    = a.textContent || a.innerText;

                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }
        
    }

    documentPrint() {
        const d         = new Printd();
        let bundle_css  = $(document).find('.print-container').data('bundle-css');
        let main_css    = $(document).find('.print-container').data('main-css');
        
        const styles  = [
            bundle_css,
            main_css,
            '.table thead{display:table-header-group}',
            '.section-title {backgroud:black}'
        ]

        const el            = $(document).find('.print-container');
        const printCallback = ({ launchPrint }) => launchPrint();

        d.print(el, styles, printCallback);
    }

    documentPrint() {
        const d         = new Printd();
        let bundle_css  = $(document).find('#table-print').data('bundle-css');
        let main_css    = $(document).find('#table-print').data('main-css');
        
        const styles  = [
            bundle_css,
            main_css,
            '.table thead{display:table-header-group}',
            '.section-title {backgroud:black}'
        ]

        const el            = document.getElementById('table-print')
        const printCallback = ({ launchPrint }) => launchPrint();

        d.print(el, styles, printCallback);
    }

    viewDataTable() {
        // let user_role = $(document).find('#user-role').data('role');
        let addButton = '<button id="btn-print" class="dt-button buttons-excel buttons-html5 btn btn-success">Print</button>';

        // if (user_role == '') {
        //     return;
        // }

        if( $.fn.DataTable ){
            var rapor_title = $('.table-data-table').attr('data-title');
            $('.table-data-table').DataTable({ 
                fnDrawCallback: function( settings ){
                    if( this.api().page.info().pages === 1 ){
                    $('.table-data-table_paginate').hide();
                    } else {
                    $('.table-data-table_paginate').show();
                    }
                },
                
                dom: 'Bfrtip', // multiple
                buttons: [
                    {
                        extend: 'excel',
                        title: rapor_title,
                    }
                ],
                
                searching   : false, 
                info        : false,
                paging      : false,  
                order       : []
            });

            $(document).find( '.dt-button' ).addClass( 'btn btn-success' );
            
        } else {
            console.log( 'DataTable is not function.');
        }

        $('.dt-buttons').append(addButton);
    }

    searchList() { 
        let searchlist  = [];
        let searchname  = $(document).find('#search_name');
        let list        = $(document).find('.searchid-list');
        let nama_siswa  = $(document).find('.searchname-list');
        let icon_search = $(document).find('#icon-check');

        list.find( '.list' ).each( function( keys ) {
            searchlist[keys] = $(this).data('search-id');
        });
    
        $( document ).on( 'keyup' , '.search',function(event, data=searchlist) {

            var input     = parseInt($(this).val());
            var result    = data.indexOf(input); 

            if( result >= 0 ) {
                let name_result = nama_siswa.find("[data-search-id='" + data[result] +"']").text();
                searchname.val( name_result );
                icon_search.show();
            } else {
                searchname.val( '' );
                icon_search.hide();
            }
        
        });
    }

    checkRequired() {
        var name;
        var $id;
        var fail      = false;
        var fail_log  = '';
        var $name     = [];
        var i         = 0;
        
        $( '#form-add' ).find( 'select, textarea, input' ).each(function(){
            if( ! $( this ).prop( 'required' ) ){
            
            } else {
            
                if ( ! $( this ).val() ) {
                var $closest  = $(this).closest('.tab-pane');
                var id        = $closest.attr('id');

                $id         = id;
                fail        = true;
                name        = $( this ).attr( 'name' );
                $name[i++]  = name;
                fail_log    += name + " is required \n";

                }
            }       
        });

        $('.nav a[href="#' + $id + '"]').tab('show');    
    }

    checkboxModal() {
        
        let checkbox    = this;
        let select      = $(document).find('#id_kelas');

        if (checkbox.checked == true) {

        select.removeAttr('disabled');

        } else {

        select.attr('disabled','');
        select.attr('value','');

        }

    }

    setupPlugins() {
        if ($.fn.datepicker) {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                disableTouchKeyboard: true,
                autoclose: true
            }).val();
        }
    }

    promptDelete(e) {
        e.preventDefault();
        Swal.fire({
        // title: 'Apakah anda yakin?',
        text: 'Apakah anda yakin akan menghapus data?',
        icon: 'warning',
        showCancelButton: true,
        reverseButtons: true,
        confirmButtonColor: '#fb6340',
        cancelButtonColor: '#adb5bd',
        cancelButtonText: 'Batalkan',
        confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
        if (result.value) {
            document.location = $(this).attr('href');
        }
        })
    }

    setActive(e) {
        $('#import-xlsx .file-drop-area').addClass('is-active');
    }

    setInctive(e) {
        $('#import-xlsx .file-drop-area').removeClass('is-active');
    }

    modalClose() {
        $('#import-xlsx').modal('hide');
        document.location.reload();
    }

    doParse(inputElement) {
        const files = inputElement.files || [];
        if (!files.length) {
        return;
        }

        const reader = new FileReader();

        reader.onloadend = (event) => {
        var $this           = this;
        var disable_header  = true;
        var disable_row     = false;
        const workbook      = XLSX.read(reader.result, { type: 'array' });
        
        $('#import-xlsx').addClass('loaded');
        
        const json    = this.toJson(workbook);
        let $data     = json;
        const sheet   = Object.keys($data);
        

        $('#modalSheet').css('display','block');
        for ( const key of sheet ) {
            $('#sheetSelect').append($('<option>', { 
            value: key,
            text : key 
            }));
        }

        $('#sheetSelect').change(function(){
            const html    = $this.toHtml(workbook,$(this).val());
            $('#workbook').html(html);
            $('#do-import').show();
        });

        $('#jenis_penilaian_select').change(function(){
            if ( $(this).val() === 'nilai_harian' ) {
                $(document).find('#range-penilaian').show();
            } else {
                $(document).find('#range-penilaian').hide();
            }
        });
        
        $(document).on( 'click', '#do-import', function(){
            if ( $('#sheetHeaderCheck').is(':checked') ) {
            disable_header = true;
            } else {
            disable_header = false;
            }

            if ( $('#sheetRowCheck').is(':checked') ) {
            disable_row = true;
            } else {
            disable_row = false;
            }
            
            $this.doImport($data[$('#sheetSelect').val()],disable_header,disable_row);

        });
        

        };

        reader.readAsArrayBuffer(files[0]);
    }

    doImport($data,$header,$row) {
        let $mapel;
        let $jenis_penilaian;
        let $semester;
        let $tingkat_container;
        let $tingkat;
        let $min_penilaian;
        let $kelas;
        let $new_data = $data;
        let total_header = $('#total-header').val();

        $('#do-import').text('Sedang mengimport...');
        
        if ( $header === true ) {
            $new_data = $data.slice(total_header);
        }

        let $type                 = $(document).find('#typeSelect').val();
        
        if ($('.import-nilai').length > 0) {
            $semester             = $(document).find('.import-nilai').find('#semester_select').val();
            $mapel                = $(document).find('.import-nilai').find('#mata_pelajaran_select').val();
            $jenis_penilaian      = $(document).find('.import-nilai').find('#jenis_penilaian_select').val();
            $tingkat_container    = $(document).find('#mata_pelajaran_select').find('#option'+$mapel);
            $tingkat              = $tingkat_container.attr('data-tingkat');
            $min_penilaian        = $(document).find('#min-penilaian').val();
            $kelas                = $(document).find('.import-nilai').find('#kelas_select').val();
        }

        $.ajax({
        type    : 'POST',
        dataType: 'JSON',
        url     : location.href,
        data    : {
            action      : 'import',
            type        : $type,
            disable_row : $row,
            data        : $new_data,
            semester    : $semester,
            mapel       : $mapel,
            tingkat     : $tingkat,
            min_penilaian : $min_penilaian,
            jenis_penilaian : $jenis_penilaian,
            kelas : $kelas
        },
        })
        .done(function(response) {

            if( response.success === true ) {
            console.log(response.message);
            } else {
            console.log(response.message);
            }

            $('#import-xlsx').modal('hide');
            Swal.fire({
            icon: 'success',
            title: response.message,
            showConfirmButton: false,
            timer: 3000
            });
            
            setTimeout(() => {
            document.location.reload();
            }, 2000);
            
        });
    
    }

    toHtml(workbook,shitName) {
        let html = '';
        // console.log(workbook)
        // workbook.SheetNames.index(0).forEach(function(sheetName) {
        // const htmlstr = XLSX.write(workbook, { sheet:sheetName, type:'string', bookType:'html' });
        const htmlstr = XLSX.utils.sheet_to_html(workbook.Sheets[shitName], {});
        html += htmlstr;
        // });

        return html;
    }

    toJson(workbook) {
        let result = {};

        workbook.SheetNames.forEach(function(sheetName) {
        const json = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName], { header:1 });
        result[sheetName] = json;
        });

        return result;
    }

    calculateTotal( sum ) {
        let $table1 = $('.table');
        // var sum = 0;
        $table1.find('tbody tr').each(function(){
        $(this).find('th:last').each(function(){
            if(!isNaN(Number($(this).text()))){
            sum=sum+Number($(this).text());
            }
        });
        });
        return sum;
    }
}

new ATK();
