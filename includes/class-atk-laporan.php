<?php
/**
 * ATK_Laporan Class.
 *
 * @class       ATK_Laporan
 * @version		1.0
 * @author      huseinarbi <huseinarbi66@gmail.com>
 */

class ATK_Laporan {
    /**
	 * Singleton method
	 *
	 * @return self
	 */
	public static function init(){
		static $instance = false;

		if( ! $instance ){
			$instance = new ATK_Barang();
		}

		return $instance;
	}
	
    public function __construct() {
		$this->_getMethod = Flight::request()->method;
    }
    
    public function view_data( $section, $page ) {
        global $pdodb;

        $periode = isset( $_REQUEST['periode'] ) ? $_REQUEST['periode'] : '2020-12';

        if ( $periode ) {
            $periode = explode( '-', $periode );
        }

        if ( empty( $page ) ) {
            $page = 1;
        }

        if ( $section == 'bulanan' ) {
            $periode_bulan = isset( $_REQUEST['periode'] ) ? $_REQUEST['periode'] : false;
            
            $table  = 'laporan';
            $key    = 'id_laporan';
            $cols   = array(
                'id_laporan'            => 'ID',
                'nama_barang'           => 'Nama Barang',
                'stok_awal'             => 'Awal Bulan',
                'jumlah_pengambilan'    => 'Pengambilan',
                'jumlah_penambahan'     => 'Penambahan',
                'stok_akhir'            => 'Akhir Bulan',
                'harga_barang'  => array(
                    'laporan'   => 'Harga'
                ),
                'periode_bulan' => 'Periode'
            );

            $join   = array(
                'barang'    => 'id_barang'
            );

            $where  = array(
				'YEAR(date(laporan.periode_bulan))'  => $periode[0],
                'MONTH(date(laporan.periode_bulan))' => $periode[1]
			);
            
        } elseif ( $section == 'pengambilan' ) {

            $table  = 'transaksi';
            $key    = 'id_transaksi';
            
            $cols_view = array(
                'id_transaksi'          => 'ID',
                'nama_pegawai'          => 'Nama Pegawai',
                'nama_barang'           => 'Nama Barang',
                'jumlah'                => 'Jumlah',
                'created_at'            => array(
                    'transaksi' => 'Created At'
                )
            );

            $cols = array(
                'id_transaksi'          => 'ID',
                'nama_pegawai'          => 'Nama Pegawai',
                'nama_barang'           => 'Nama Barang',
                'SUM(jumlah) as jumlah' => 'Jumlah',
                'created_at'            => array(
                    'transaksi' => 'Created At'
                )
            );

            $join   = array(
                'barang'    => 'id_barang',
                'pegawai'   => 'id_pegawai'
            );

            $where  = array(
                'transaksi.jenis'                   => 'pengambilan',
                'YEAR(date(transaksi.created_at))'  => $periode[0],
                'MONTH(date(transaksi.created_at))' => $periode[1]
            );

            $group_by  = 'transaksi.id_barang, MONTH( transaksi.created_at )';

        } elseif ( $section == 'penambahan' ) {

            $table  = 'transaksi';
            $key    = 'id_transaksi';
            
            $cols_view   = array(
                'id_transaksi'          => 'ID',
                'nama_pegawai'          => 'Nama Pegawai',
                'nama_barang'           => 'Nama Barang',
                'jumlah'                => 'Jumlah',
                'created_at'            => array(
                    'transaksi' => 'Created At'
                )
            );

            $cols   = array(
                'id_transaksi'          => 'ID',
                'nama_pegawai'          => 'Nama Pegawai',
                'nama_barang'           => 'Nama Barang',
                'SUM(jumlah) as jumlah' => 'Jumlah',
                'created_at'            => array(
                    'transaksi' => 'Created At'
                )
            );

            $join   = array(
                'barang'    => 'id_barang',
                'pegawai'   => 'id_pegawai'
            );

            $where  = array(
                'transaksi.jenis'                   => 'penambahan',
                'YEAR(date(transaksi.created_at))'  => $periode[0],
                'MONTH(date(transaksi.created_at))' => $periode[1]
            );

            $group_by  = 'transaksi.id_barang, MONTH( transaksi.created_at )';

        }

        $sections = array(
			array(
                'title'		=> 'Bulanan',
                'fields' 	=> array(
                    array(
                        'name' 		=> 'date-periode-prediksi',
                        'label'		=> 'Periode',
                        'type'		=> 'date-month',
                        'data'		=> null,
                        'required'	=> true,
                        'data'      => isset( $_REQUEST['periode'] ) ? $_REQUEST['periode'] : ''
                    )
                ),
            ),
            array(
                'title'		=> 'Pengambilan',
                'fields' 	=> array(
                    array(
                        'name' 		=> 'date-periode-prediksi',
                        'label'		=> 'Periode',
                        'type'		=> 'date-month',
                        'data'		=> null,
                        'required'	=> true,
                        'data'      => isset( $_REQUEST['periode'] ) ? $_REQUEST['periode'] : ''
                    )
                ),
            ),
            array(
                'title'		=> 'Penambahan',
                'fields' 	=> array(
                    array(
                        'name' 		=> 'date-periode-prediksi',
                        'label'		=> 'Periode',
                        'type'		=> 'date-month',
                        'data'		=> null,
                        'required'	=> true,
                        'data'      => isset( $_REQUEST['periode'] ) ? $_REQUEST['periode'] : ''
                    )
                ),
			)
        );
        
		Flight::render('laporan', array(
			'heading' 	=> 'Laporan '.ucwords( $section ),
			'base_url' 	=> get_url('laporan/bulanan'),
			// 'more_btn' 	=> array(
			// 	'button1' 	=> array(
			// 		'title' 	=> 'Detail',
			// 		'url' 		=> 'detail',
			// 		'action' 	=> 'view'
			// 	)
			// ),
			// 'add'  		=> 'Tambah Barang',
			// 'btn_download'	=> array(
			// 	array(
			// 		'string'	=> 'Template Barang',
			// 		'id'		=> 'download_barang',
			// 		'url'		=> 'uploads/Template_Barang.xlsx'
			// 	)
            // ),
            'sections'      => $sections,
			'table' 	    => $pdodb->getTableData( array(
                'cols'      => $cols,
                'cols_view' => isset( $cols_view ) && $cols_view ? $cols_view : false,
				'page'      => $page,
				'table'     => $table,
                'key'       => $key,
                'join'      => isset( $join ) && $join ? $join : false,
                'where'     => isset( $where ) && $where ? $where : false,
                'group_by'  => isset( $group_by ) && $group_by ? $group_by : false
            )),
            'base'              => get_url().'laporan/'.$section,
            'active_section'    => $section
		));
    }

    public function check_tutup_buku( $action ) {
        global $pdodb;

        try {

            $page   = 1;
            $table  = 'laporan';
            $key    = 'id_laporan';
            $cols   = array(
                'id_laporan'                    => 'ID',
                'max(periode_bulan) as periode' => 'Periode'
            );
            
            $laporan = $pdodb->getTableData( array(
                'cols'      => $cols,
				'page'      => $page,
				'table'     => $table,
                'key'       => $key,
                'join'      => isset( $join ) && $join ? $join : false,
                'where'     => isset( $where ) && $where ? $where : false,
                'group_by'  => isset( $group_by ) && $group_by ? $group_by : false
            ) );

            $periode = current( $laporan['data'] )['periode'];
            $periode = date( "Y-m", strtotime( $periode ) );

            $response = array(
                'success'   => true,
                'data'      => $periode
            );

        } catch ( Exception $e ) {
            $response = array(
                'success' 	=> false,
                'message'	=> $e->getMessage()
            );
        }

        Flight::json( $response );
    }

    public function tutup_buku() {
        global $pdodb;

        $periode    = isset( $_REQUEST['periode'] ) ? $_REQUEST['periode'] : '2020-12';

        if ( $periode ) {
            $periode = explode( '-', $periode );
        }

        if ( empty( $page ) ) {
            $page = 1;
        }

        $table  = 'laporan';
        $key    = 'id_laporan';
        $cols   = array(
            'id_laporan'            => 'ID',
            'nama_barang'           => 'Nama Barang',
            'stok_awal'             => 'Awal Bulan',
            'jumlah_pengambilan'    => 'Pengambilan',
            'jumlah_penambahan'     => 'Penambahan',
            'stok_akhir'            => 'Akhir Bulan',
            'harga_barang'          => array(
                'laporan'   => 'Harga'
            ),
            'periode_bulan'         => 'Periode'
        );

        $join   = array(
            'barang'    => 'id_barang'
        );

        $where  = array(
            'YEAR(date(laporan.periode_bulan))'  => $periode[0],
            'MONTH(date(laporan.periode_bulan))' => $periode[1]
        );

        $sections = array(
			array(
                'title'		=> 'Bulanan',
                'fields' 	=> array(
                    array(
                        'name' 		=> 'date-periode-tutup-buku',
                        'label'		=> 'Periode',
                        'type'		=> 'date-month',
                        'required'	=> true,
                        'data'      => isset( $_REQUEST['periode'] ) ? $_REQUEST['periode'] : '2020-12'
                    ),
                    array(
                        'name' 		=> 'save-tutup-buku',
                        'label'		=> 'Tutup Buku',
                        'type'		=> 'button-action',
                    )
                ),
            ),
        );

        Flight::render( 'laporan', array(
			'heading' 	=> 'Tutup Buku',
			'base_url' 	=> get_url('tutup-buku'),
            'sections'  => $sections,
			'table' 	=> $pdodb->getTableData( array(
                'cols'      => $cols,
                'cols_view' => isset( $cols_view ) && $cols_view ? $cols_view : false,
				'page'      => $page,
				'table'     => $table,
                'key'       => $key,
                'join'      => isset( $join ) && $join ? $join : false,
                'where'     => isset( $where ) && $where ? $where : false,
                'group_by'  => isset( $group_by ) && $group_by ? $group_by : false
            )),
            'active_section'    => 'tutup-buku'
            // 'base'  => get_url().'laporan/'.$section,
		));

    }

    public function save_tutup_buku( $date ) {

        global $pdodb;
        $response = '';

        DEFINE( 'DATA_PER_PAGE', 200 );

        if ( $date ) {
            $periode = explode( '-', $date );
        }
        
        $cols   = array(
            'id_laporan'    => 'ID',
            'id_barang'     => 'ID Barang',
            'stok_akhir'    => 'Stok Akhir',
            'periode_bulan' => 'Periode Bulan'
        );

        $where  = array(
            'YEAR(date(laporan.periode_bulan))'  => $periode[0],
            'MONTH(date(laporan.periode_bulan))' => $periode[1]
        );

        $laporan = $pdodb->getTableData(array(
            'cols'      => $cols,
            'page'      => 1,
            'table'     => 'laporan',
            'key'       => 'id_laporan',
            'join'      => isset( $join ) && $join ? $join : false,
            'where'     => isset( $where ) && $where ? $where : false,
            'group_by'  => isset( $group_by ) && $group_by ? $group_by : false
        ));

        // echo '<pre>';
        // print_r($laporan['data']);
        // exit();

        try {
            foreach ( $laporan['data'] as $key => $value ) {
                $data = array(
                    'id_barang'     => $value['id_barang'],
                    'stok_awal'     => $value['stok_akhir'],
                    'periode_bulan' => date( "Y-m", strtotime( "+1 month", strtotime($value['periode_bulan']) ) ).'-01',
                    'created_at'    => current_time( 'mysql' ),
                    'modified_at'   => current_time( 'mysql' )
                );

                // echo '<pre>';
                // print_r($data);

                // exit();
                
                $save_error_message	= $pdodb->updateData( array(
                    'table' 		=> 'laporan',
                    'data'			=> $data,
                    'where' 		=> array(
                        array(
                            'key'		=> 'id_barang',
                            'key_value'	=> $value['id_barang']
                        ),
                        array(
                            'key'       => 'periode_bulan',
                            'key_value' => $data['periode_bulan']
                        )
                    )
                ));

                if ( ! empty( $save_error_message['error'] ) ) {
                    throw new Exception('Error Save');
                }
            }

            $response   = array(
                'success'   => true,
                'message'   => 'Data Tersimpan'
            );

        } catch (Exception $e) {
            $response   = array(
                'success'   => true,
                'message'   => $e->getMessage()
            );
        }
 
        echo '<pre>';
        print_r($response);
        exit();
        
	}

}

ATK_Laporan::init();