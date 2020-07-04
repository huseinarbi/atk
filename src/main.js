import XLSX from 'xlsx';
import $ from 'jquery';
import { Printd } from 'printd';

// load scss
import './styles/main.scss';

class ATK {
  constructor() {
    this.setupPlugins();
    let searchcontainer = $(document).find('.searchid-list');
    // Delete table data
    $(document).on('click', '[data-action="delete"]', this.promptDelete);

    // import
    $(document).on('click', '#btn-import', e => $('#import-xlsx').modal('show'));
    $(document).on('hidden.bs.modal', '#import-xlsx', e => $('#import-xlsx #workbook').html(''));
    $(document).on('show.bs.modal', '#import-xlsx', e => $('#import-xlsx').removeClass('loaded'));

    $(document).on('dragenter focus click', '#input-xls', this.setActive);
    $(document).on('dragleave blur drop', '#input-xls', this.setInctive);
    $(document).on('change', '#input-xls', e => this.doParse(e.target));
    $(document).on( 'click', '#btn-close-import', this.modalClose);

    $(document).on('click', '#checkbox-insert', this.checkboxModal);
    $(document).on('click', '#btn-submit', this.checkRequired);

    //admin-guru-mengajar    
    $(document).on('click', '#mapel-master', this.addMengajar);
    $('.card-header-target').on('click', '#mapel-card-target', this.deleteMengajar);

    if(searchcontainer.length > 0 ){
      this.searchList();
    }
    $(document).ready(this.viewDataTable);
    
    //print
    $(document).on('click', '#btn-print', this.documentPrint);

  }

  documentPrint() {
    const d         = new Printd();
    let bundle_css  = $(document).find('#tableRapor').data('bundle-css');
    let main_css    = $(document).find('#tableRapor').data('main-css');
    
    const styles  = [
      bundle_css,
      main_css,
      '.table thead{display:table-header-group}',
      '.section-title {backgroud:black}'
    ]

    const el = document.getElementById('tableRapor')
    const printCallback = ({ launchPrint }) => launchPrint()

    d.print(el, styles, printCallback)
  }

  viewDataTable() {
    let user_role = $(document).find('#user-role').data('role');
    let addButton = '<button id="btn-print" class="dt-button buttons-excel buttons-html5 btn btn-success">Print</button>';

    if (user_role == 'siswa') {
      return;
    }
      if( $.fn.DataTable ){
        var rapor_title = $('#tableRapor').attr('data-title');
        $('#tableRapor').DataTable({ 
          fnDrawCallback: function( settings ){
            if( this.api().page.info().pages === 1 ){
              $('#tableRapor_paginate').hide();
            } else {
              $('#tableRapor_paginate').show();
            }
          },
         
          dom: 'Bfrtip', // multiple
          buttons: [
            {
              extend: 'excel',
              title: rapor_title,
            }
          ],
         
        searching: false, 
        info: false,
        paging: false,  
        order : []
        });
        $(document).find( '.dt-button' ).addClass( 'btn btn-success' );
        
      } else {
        console.log( 'DataTable is not function.');
      }
      $('.dt-buttons').append(addButton);
  }
  deleteMengajar() {
    let container_target  = $(this);
    let $id_mapel         = container_target.data('mapel-id');
    let mapel             = container_target.find('#mapel-title');
    let header            = $(document).find('.header');
    let tag_notif         =
    '<div class="row justify-content-center">'
    +'<div class="col-lg-5 col-md-7">'
    +    '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
    +      '<span class="alert-icon"><i class="ni ni-notification-70"></i></span>'
    +      '<span class="alert-text">'+'Mata Pelajaran '+ mapel.text()+ ' Berhasil Dihapus' +'</span>'
    +      '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
    +        '<span aria-hidden="true">×</span>'
    +      '</button>'
    +   '</div>'
    +  '</div>'
    + '</div>';
  
    Swal.fire({
      text                : 'Apakah anda yakin akan menghapus mata pelajaran?',
      icon                : 'warning',
      showCancelButton    : true,
      reverseButtons      : true,
      confirmButtonColor  : '#fb6340',
      cancelButtonColor   : '#adb5bd',
      cancelButtonText    : 'Batalkan',
      confirmButtonText   : 'Ya, Hapus!'
    }).then((result) => {
      if (result.value) {
          $.ajax({
            type      : 'POST',
            dataType  : 'JSON',
            url       : location.href,
            data      : {
              action    : 'delete',
              id_mapel  : $id_mapel,
            },
          })
          .done(function(response) {
            if( response.success === true ) {
              console.log(response.message);
              container_target.closest('#tr-target').remove();
              header.append(tag_notif);
            } else {
              console.log(response.message);	
            }
          });    
      }
    })   

  }

  addMengajar() { 
    
    let ids                 = [];
    let container_master    = $(this);
    let container_target    = $('.card-header-target').find('#mapel-target');
    let mapel_id_master     = container_master.data('mapel-id');
    let $nip                = $('#nip').val();
    let $nisn               = $('#nisn').val();
    let $tingkat            = container_master.data('tingkat');
    let card_target         = $('.card-header-target').find('#table-target').find('tr').find('#mapel-card-target');
    let mapel             = container_target.find('#mapel-title');
    let header            = $(document).find('.header');
    let tag_notif         =
    '<div class="row justify-content-center">'
    +'<div class="col-lg-5 col-md-7">'
    +    '<div class="alert alert-success alert-dismissible fade show" role="alert">'
    +      '<span class="alert-icon"><i class="ni ni-notification-70"></i></span>'
    +      '<span class="alert-text">'+'Mata Pelajaran '+ mapel.text()+ ' Berhasil Ditambahkan' +'</span>'
    +      '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
    +        '<span aria-hidden="true">×</span>'
    +      '</button>'
    +   '</div>'
    +  '</div>'
    + '</div>';

    card_target.each(function(keys){
      ids[keys] = $(this).data('mapel-id');
    });

    if ( $nip == 0 || $nisn == 0 || null ) {
      Swal.fire({
        icon    : 'error',
        title   : 'Oops...',
        text    : 'Mohon isi ID terlebih dahulu',
      });
    } else if (ids.indexOf(mapel_id_master) >= 0 ) {
      Swal.fire({
        icon    : 'error',
        title   : 'Oops...',
        text    : 'Mata Pelajaran Sudah Diambil',
      });
    } else {
      $.ajax({
        type    : 'POST',
        dataType: 'JSON',
        url     : location.href,
        data    : {
          action    : 'add',
          mapel_id  : mapel_id_master,
          nip       : $nip,
          nisn      : $nisn,
          tingkat   : $tingkat,
        },
      })
      .done(function(response) {
        if( response.success === true ) {
          container_target.append(container_master.html());  
          header.append(tag_notif);
          console.log(response.message);
        } else {
          console.log(response.message);
        }
      });
    }
  }

  searchList() { 
    let searchlist  = [];
    let searchname  = $(document).find('#search_name');
    let list        = $(document).find('.searchid-list');
    let nama_siswa  = $(document).find('.searchname-list');
    let icon_search = $(document).find('#icon-check');

    list.find( '.list' ).each(function(keys){
      searchlist[keys] = $(this).data('search-id');
    });

  
    $( document ).on( 'keyup' , '.search',function(event, data=searchlist){
      console.log(data);
      var input     = parseInt($(this).val());
      var result    = data.indexOf(input); 

      if( result >= 0 ) {
          let name_result = nama_siswa.find("[data-search-id='" + data[result] +"']").text();
          searchname.val( name_result );
          icon_search.show();
          console.log(searchname);
          
      } else {
        searchname.val( '' );
        icon_search.hide();
      }
     
    });
  }

  searchFunction(searchlist) {
   
    console.log(searchlist);
    
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
}

new ERapor();
