<?php
/**
 * ATK_Prediksi Class.
 *
 * @class       ATK_Prediksi
 * @version		1.0
 * @author huseinarbi <huseinarbi66@gmail.com>
 */

class ATK_Prediksi {

    /**
	 * Singleton method
	 *
	 * @return self
	 */
	public static function init(){
		static $instance = false;

		if( ! $instance ){
			$instance = new ATK_Prediksi();
		}

		return $instance;
	}

	public function __construct() {
		$this->_getMethod = Flight::request()->method;
	}
	
	public function view_data( $page ) {
        global $pdodb;
        DEFINE( 'DATA_PER_PAGE', 200 );

        $group          = [];
        $periode_bulan  = date("Y-m", strtotime( current_time( 'mysql' ) ) );

        if ( isset( $_REQUEST['periode'] ) ) {
            $periode_bulan = $_REQUEST['periode'];
        }

        $data_pengambilan = $pdodb->getTableData(array(
            'cols'  => array( 
                'id_barang' 	=> array(
                    'transaksi' => 'ID Barang'
                ), 
                'nama_barang'   => array(
                    'barang'    => 'Nama Barang'
                ),
                'DATE( transaksi.created_at ) as date'                                                              => 'Periode',
                'case when SUM( transaksi.jumlah ) is null then 0 else SUM( transaksi.jumlah ) end as pengambilan' 	=> 'Pengambilan'
            ),
            'page'  => '1',
            'table' => 'transaksi',
            'key'   => 'id_transaksi',
            'join'  => array(
                'barang' => 'id_barang'
            ),
            'where' => array(
                'jenis' => 'pengambilan'
            ),
            'group_by'  => 'transaksi.id_barang, MONTH( transaksi.created_at )'
        ));

        foreach ( $data_pengambilan['data'] as $key => $value ) {
            $group[$value['id_barang']][] = $value;
        }

        $final_ = $this->get_pengambilan_data_view( $group, $periode_bulan );
        $final_ = $this->get_prediction( $final_ );

        foreach ( $final_ as $id_barang => $value ) {

            foreach ( $value as $key => $last ) {
                $pred       = $last['pengambilan'];
                $akurasi    = isset( $last['R'] ) ? $last['R'] : '';
                $kriteria   = isset( $last['kriteria'] ) ? $last['kriteria'] : '';
            }

            $to_table_final[ $id_barang ] = array(
                'id_barang'     => $id_barang,
                'nama_barang'   => $group[$id_barang][0]['nama_barang'],
                'pengambilan'   => $pred,
                'akurasi'       => $akurasi,
                'kriteria'      => $kriteria
            );
        }

        if ( ! isset( $_REQUEST['periode'] ) ) {
            $to_table_final = [];
        }

		$sections = array(
			array(
				'title'		=> 'Keranjang',
				'fields' 	=> array(
					array(
						'name' 		=> 'date-periode-prediksi',
						'label'		=> 'Periode',
						'type'		=> 'date-month',
						'data'		=> null,
                        'required'	=> true,
                        'data'      => isset( $_REQUEST['periode'] ) ? $_REQUEST['periode'] : ''
					),
					array(
						'type'		=> 'table',
						'fields'	=> array( 'id_barang', 'nama_barang', 'pengambilan', 'akurasi', 'kriteria' ),
                        'required'	=> true,
                        'data'      => $to_table_final
					),
				)
			)
		);

		Flight::render('cart', array(
			'heading' 		=> 'Prediksi',
			'sections' 		=> $sections,
			'custom_button'	=> array(
				array(
					'id' 	=> 'sumbit-save-cart',
					'type'	=> '',
					'class'	=> 'btn-success',
					'label'	=> 'Simpan'
				),
				array(
					'id' 	=> 'reset',
					'type'	=> '',
					'class'	=> 'btn-danger',
					'label'	=> 'Hapus'
				),
			)
		));
    }

    public function view_detail( $id_barang ) {
        global $pdodb;

        $group          = [];
        $periode_bulan  = date( "Y-m", strtotime( current_time( 'mysql' ) ) );

        if ( isset( $_REQUEST['periode'] ) ) {
            $periode_bulan = $_REQUEST['periode'];
        }

        $data_pengambilan = $pdodb->getTableData( array(
            'cols'      => array( 
                'id_barang' 	=> array(
                    'transaksi' => 'ID Barang'
                ), 
                'nama_barang'   => array(
                    'barang'    => 'Nama Barang'
                ),
                'DATE( transaksi.created_at ) as date'      => 'Periode',
                'SUM( transaksi.jumlah ) as pengambilan' 	=> 'Pengambilan'
            ),
            'page'      => '1',
            'table'     => 'transaksi',
            'key'       => 'id_transaksi',
            'join'      => array(
                'barang'    => 'id_barang'
            ),
            'where'     => array(
                'jenis'                 => 'pengambilan',
                'transaksi.id_barang'   => $id_barang
            ),
            'group_by'  => 'transaksi.id_barang, MONTH( transaksi.created_at )'
        ));

        foreach ( $data_pengambilan['data'] as $key => $value ) {
            $group[$value['id_barang']][] = $value;
        }

        $final_ = $this->get_pengambilan_data_view( $group, $periode_bulan );
        $final_ = $this->get_prediction( $final_ );

        foreach ( $final_ as $id_barang => $value ) {

            foreach ( $value as $key => $detail ) {
                if ( isset( $detail['a'] ) && isset( $detail['b'] ) ) {
                    $a          = $detail['a'];
                    $b          = $detail['b'];
                    $periode    = $detail['periode'];
                    $akurasi    = isset( $detail['R'] ) ? $detail['R'] : '';
                    $kriteria   = isset( $detail['kriteria'] ) ? $detail['kriteria'] : '';
                }
                
                $pred[] = $detail['pengambilan'];
            }

            $to_table_final = array(
                'id_barang'     => $id_barang,
                'nama_barang'   => $group[$id_barang][0]['nama_barang'],
                'periode'       => $pred
            );

            $detail_prediction = array(
                'a'     => $a,
                'b'     => $b,
                'y'     => $a.'+'.$b.'*'.$periode
            );

        }

        $value_to_chart = implode( ';', $to_table_final['periode'] );

		$sections = array(
			array(
				'title'		=> 'Detail',
				'fields' 	=> array(
                    array(
                        'name'          => 'data-chart',
						'type'		    => 'chart',
                        'required'	    => true,
                        'style'         => '',
                        'hidden'        => true,
                        'pengambilan'   => $value_to_chart,
                        'koefisien'     => $detail_prediction['a'],
                        'prediksi'      => end($to_table_final['periode'])
                    ),
                    array(
                        'name' 		    => 'konstanta',
						'label'		    => 'Konstanta (a)',
						'type' 		    => 'label',
						'data'		    => $detail_prediction['a'],
						'editable'	    => true,
                        'required'	    => true,
                        // 'disable'   => true,
                        'style'         => ''
                    ),
                    array(
                        'name' 		    => 'koefisien',
						'label'		    => 'Koefisien (b)',
						'type' 		    => 'label',
						'data'		    => $detail_prediction['b'],
						'editable'	    => true,
                        'required'	    => true,
                        // 'disable'   => true,
                        'style'         => ''
                    ),
                    array(
                        'name' 		    => 'r',
						'label'		    => 'Korelasi R',
						'type' 		    => 'label',
						'data'		    => $akurasi,
						'editable'	    => true,
                        'required'	    => true,
                        // 'disable'   => true,
                        'style'         => ''
                    ),
                    array(
                        'name' 		    => 'kriteria',
						'label'		    => 'Kriteria',
						'type' 		    => 'label',
						'data'		    => $kriteria,
						'editable'	    => true,
                        'required'	    => true,
                        // 'disable'   => true,
                        'style'         => ''
                    ),
					array(
						'type'		    => 'table-detail',
                        'required'	    => true,
                        'data'          => $to_table_final
					)
				)
			)
		);

		Flight::render( 'cart', array(
			'heading' 		=> 'Prediksi',
			'sections' 		=> $sections,
			'custom_button'	=> ''
		));
    }

    public function get_pengambilan_data_view( $group, $periode_bulan ) {
        /**
         * create array periodik
         */

        foreach ( $group as $key_group => $barang ) {
            $period         = [];
            $pengambilan    = [];
            
            $period = new DatePeriod(
                new DateTime( reset( $barang )['date'] ),
                new DateInterval( 'P1M' ),
                new DateTime( $periode_bulan )
            );

            foreach ( $period as $key => $value ) {
                
                $date_period  = $value->format('Y-m');

                foreach ( $barang as $keys => $values ) {
                 
                    if ( $date_period == date( "Y-m", strtotime( $values['date'] ) ) ) {
                        $pengambilan[$key] = $values['pengambilan'];
                    }

                }

                $final_[$key_group][$key] = array(
                    'date'              => $date_period,
                    'id_barang'         => current( $barang )['id_barang'],
                    'nama_barang'       => current( $barang )['nama_barang'],
                    'periode'           => $x = $key+1,
                    'pengambilan'       => $y = isset( $pengambilan[$key] ) ? $pengambilan[$key] : 0,
                    'x_kuadrat'         => $x*$x,
                    'y_kuadrat'         => $y*$y,
                    'xy'                => $x*$y
                );
            }

        }

        return $final_;
    }
    
    public function get_prediction( $data ) {
        
        foreach ( $data as $id_barang => $periode ) {
            $prediction         = [];
            $n                  = count( $periode );
            $a                  = 0;
            $b                  = 0;
            $sigma_x            = 0;
            $sigma_y            = 0;
            $sigma_x_kuadrat    = 0;
            $sigma_y_kuadrat    = 0;
            $sigma_xy           = 0;
            
            foreach ( $periode as $key => $value ) {
                $sigma_x            = $sigma_x + $value['periode'];
                $sigma_y            = $sigma_y + $value['pengambilan'];
                $sigma_x_kuadrat    = $sigma_x_kuadrat + $value['x_kuadrat'];
                $sigma_y_kuadrat    = $sigma_y_kuadrat + $value['y_kuadrat'];
                $sigma_xy           = $sigma_xy + $value['xy'];
            }

            $a = $this->get_constanta( $n, $sigma_x, $sigma_y, $sigma_x_kuadrat, $sigma_y_kuadrat, $sigma_xy );
            $b = $this->get_koefisien( $n, $sigma_x, $sigma_y, $sigma_x_kuadrat, $sigma_y_kuadrat, $sigma_xy );

            $y_aksen    = $this->get_regresion( $a, $b, $n+1 );
            $R          = $this->get_korelasi( $n, $sigma_x, $sigma_y, $sigma_x_kuadrat, $sigma_y_kuadrat, $sigma_xy );

            $prediction[] = array(
                'a'             => $a,
                'b'             => $b,
                'periode'       => $n+1,
                'pengambilan'   => $y_aksen,
                'R'             => $R,
                'kriteria'      => $this->get_kriteria( $R ) 
            );

            $final_[$id_barang] = array_merge( $periode, $prediction );
        }

        return $final_;

    }

    public function get_constanta( $n, $sigma_x, $sigma_y, $sigma_x_kuadrat, $sigma_y_kuadrat, $sigma_xy ) {
        $xx = ( ( $sigma_y * $sigma_x_kuadrat ) - ( $sigma_x * $sigma_xy ) );
        $yy = ( ( $n * $sigma_x_kuadrat ) - ( $sigma_x * $sigma_x ) );

        if ( $yy === 0 ) {
            return 0;
        }
        
        return $a =  ($xx / $yy);
    }

    public function get_koefisien( $n, $sigma_x, $sigma_y, $sigma_x_kuadrat, $sigma_y_kuadrat, $sigma_xy ) {
        $xx = ( ( $n * $sigma_xy ) - ( $sigma_x * $sigma_y ) );
        $yy = ( ( $n * $sigma_x_kuadrat ) - ( $sigma_x * $sigma_x ) );

        if ( $yy === 0 ) {
            return 0;
        }

        return $b = ($xx / $yy);
    }

    public function get_regresion( $a, $b, $x ) {
        return round( $y_aksen = $a + ( $b * $x ) );
    }

    public function get_korelasi( $n, $sigma_x, $sigma_y, $sigma_x_kuadrat, $sigma_y_kuadrat, $sigma_xy ) {

        $a = ( ( $n * $sigma_xy ) - ( $sigma_x * $sigma_y ) ) ;

        $x = ( $n * $sigma_x_kuadrat ) - ( $sigma_x * $sigma_x );
        $y = ( $n * $sigma_y_kuadrat ) - ( $sigma_y * $sigma_y );

        $b = sqrt( ( $x ) * ( $y ) );

        if ( $a == 0 && $b == 0) {
            return 0;
        }

        return round ( $R = $a / $b, 2 );
    }

    public function get_kriteria( $R ) {

        if ( 0.8 <= $R && $R ) {
            return 'Sangat Kuat';
        } else if ( 0.6 <= $R && $R <= 0.79 ) {
            return 'Kuat';
        } else if ( 0.4 <= $R && $R <= 0.59 ) {
            return 'Cukup Kuat';
        } else if ( 0.2 <= $R && $R <= 0.39 ) {
            return 'Rendah';
        } else {
            return 'Sangat Rendah';
        }
    }

}

ATK_Prediksi::init();
