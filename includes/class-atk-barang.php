<?php
/**
 * ATK_Barang Class.
 *
 * @class       ATK_Barang
 * @version		1.0
 * @author 		huseinarbi <huseinarbi66@gmail.com>
 */

class ATK_Barang {

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
	
	public function view_data( $page ) {
		global $pdodb;

		if ( empty( $page ) ) {
            $page = 1;
        }
        
        $cols = array(
			'id_barang'     => 'ID',
			'nama_barang'	=> 'Nama Barang',
			'satuan' 	    => 'Satuan',
			'stok' 			=> 'Stok',
			'harga_barang' 	=> 'Harga',
			'created_at'	=> 'Created At',
			'modified_at'	=> 'Modified At'
		);

        Flight::render('table', array(
			'heading' 	=> 'Data Barang',
			'base_url' 	=> get_url('barang'),
			'more_btn' 	=> array(
				// 'button1' 	=> array(
				// 	'title' 	=> 'Detail',
				// 	'url' 		=> 'detail',
				// 	'action' 	=> 'view'
				// )
			),
			'add'  		=> 'Tambah Barang',
			'btn_download'	=> array(
				array(
					'string'	=> 'Template Barang',
					'id'		=> 'download_barang',
					'url'		=> 'uploads/Template_Import.xlsx'
				)
			),
			'table' 	=> $pdodb->getTableData(array(
				'cols'  => $cols,
				'page'  => $page,
				'table' => 'barang',
				'key'   => 'id_barang'
			))
		));
	}

	public function edit_data( $action, $id ) {
		global $pdodb;

		$save_error_message = array();

		if ( 'POST' === $this->_getMethod ) {

			$current_data = $_POST;

			if ( $action == 'edit' ) {
				
				$more_data	= array( 
					'modified_at' 	=> current_time( 'mysql' ) 
				);
				
			} else {

				$more_data = array(
					'created_at'	=> current_time( 'mysql' ),
					'modified_at' 	=> current_time( 'mysql' )
				);

			}

			$data_to_save = array_merge( $current_data, $more_data );

			$save_error_message	= $pdodb->saveData( array(
				'table' 		=> 'barang',
				'data'			=> $data_to_save,
				'edit' 			=> !empty( $id ) && $action === 'edit' ? array(
					'key'		=> 'id_barang',
					'key_value'	=> $id
				) : ''
			));
			
			$data_to_save_laporan = array(
				'id_barang'				=> $save_error_message['id'],
				'stok_awal'				=> $current_data['stok'],
				'jumlah_pengambilan'	=> '0',
				'jumlah_penambahan'		=> '0',
				'stok_akhir'			=> '0',
				'harga_barang'			=> $current_data['harga_barang'],
				'periode_bulan'			=> date( "Y-m", strtotime( $more_data['created_at'] ) ).'-1',
				'created_at'			=> current_time( 'mysql' ),
				'modified_at' 			=> current_time( 'mysql' )
			);

			$save_error_message	= $pdodb->saveData( array(
				'table' 		=> 'laporan',
				'data'			=> $data_to_save_laporan
			));

			if ( !empty( $save_error_message['error'] ) ) {

                Flight::addMessage( $save_error_message, 'error' );
                
			} else {

				Flight::addMessage( 'Data berhasil disimpan', 'success' );
				Flight::redirect( '/barang' );
                exit();
                
			}
		}

		if ( $id ) {
			$data_barang = $pdodb->getTableData( array(
				'cols'  => array(
					'id_barang' 	=> 'ID',
					'nama_barang'	=> 'Nama Barang',
					'satuan'		=> 'Satuan',
					'harga_barang'	=> 'Harga Barang',
					'stok'			=> 'Stok'
				),
				'page'  => '1',
				'table' => 'barang',
				'key'   => 'id_barang',
				'where' => $id
			));

			foreach ( $data_barang['data'] as $key => $field ) {
				extract( $field );
			}
		}

		$sections = array(
			array(
				'title'		=> 'Options',
				'fields' 	=> array(
					array(
						'name' 		=> 'nama_barang',
						'label'		=> 'Nama Barang',
						'type' 		=> 'text',
						'data'		=> isset( $nama_barang ) ? $nama_barang : '',
						'required'	=> true
					),
					array(
						'name' 		=> 'satuan',
						'label'		=> 'Satuan',
						'type' 		=> 'text',
						'data'		=> isset( $satuan ) ? $satuan : '',
						'required'	=> true
					),
					array(
						'name' 		=> 'harga_barang',
						'label'		=> 'Harga Barang',
						'type' 		=> 'text',
						'data'		=> isset( $harga_barang ) ? $harga_barang : '',
						'required'	=> true
					),
					array(
						'name' 		=> 'stok',
						'label'		=> 'Stok Barang',
						'type' 		=> 'text',
						'data'		=> isset( $stok ) ? $stok : '',
						'required'	=> true
					)
				)
			)
		);

		Flight::render('form', array(
			'heading' 	=> 'Add Barang',
			'sections' 	=> $sections
		));
	}

	public function import_barang( $data ) {
		global $pdodb;
		$count_success	= 0;
		$type_import 	= isset( $_REQUEST['type'] ) && ! empty( $_REQUEST['type'] ) ? $_REQUEST['type'] : false;

		try {

			if ( ! $type_import ) {
				throw new Exception( 'type import not set' );
			}

			if ( $type_import == 'barang' ) {
				// to import barang
				foreach ( $data as $key => $item ) {
					$final_data_to_save[] = array(
						'id_barang'		=> $item[0],
						'nama_barang'	=> $item[1],
						'satuan'		=> $item[2],
						'harga_barang'	=> $item[3],
						'stok'			=> abs( $item[4] ) == '0' ? '0' : abs( $item[4] ) ,
						'created_at'	=> $item[5],	
						'modified_at'	=> $item[6]
					);
				}
			}
			
			if ( $type_import == 'pengambilan' || $type_import == 'penambahan' ) {
				//to import pengambilan // penambahan
				foreach ( $data as $key => $item ) {
					$final_data_to_save[] = array(
						'id_transaksi'	=> $item[0],
						'id_pegawai'	=> $item[1],
						'id_barang'		=> $item[2],
						'jenis'			=> $item[3],
						'jumlah'		=> abs( $item[4] ) == '0' ? '0' : abs( $item[4] ) ,
						'created_at'	=> $item[5]
					);
				}
			}
			
			foreach ( $final_data_to_save as $key => $value ) {

				if ( $type_import == 'barang' ) {
					$save_error_message = $this->import_barang_debug( $value );
				}

				if ( $type_import == 'pengambilan' || $type_import == 'penambahan' ) {
					$save_error_message = $this->import_pengambilan_penambahan( $value );
				}
				
				if ( !empty( $save_error_message['error'] ) ) {

					$response = array(
						'success'	=> false,
						'message'	=> $count_success . ' Data dari ' . count( $data ) . ' Berhasil di Import'
					);

					Flight::addMessage( $response['message'] );

				} else {

					$count_success++;
					$response = array(
						'success'	=> true,
						'message'	=> $count_success . ' Data dari ' . count( $data ) . ' Berhasil di Import'
					);

					Flight::addMessage( $response['message'], 'success' );
				}
			}
		} catch ( Exception $e ) {
			$response = array(
				'success' 	=> false,
				'message'	=> $e->getMessage()
			);
		}

		Flight::json( $response );
	}

	public function import_barang_debug( $value ) {
		global $pdodb;

		$save_error_message = $pdodb->saveData( array(
			'table' 		=> 'barang',
			'data'			=> $value
		));

		$data_to_save_laporan = array(
			'id_barang'				=> $save_error_message['id'],
			'stok_awal'				=> $value['stok'],
			'jumlah_pengambilan'	=> '0',
			'jumlah_penambahan'		=> '0',
			'stok_akhir'			=> '0',
			'harga_barang'			=> $value['harga_barang'],
			'periode_bulan'			=> date( "Y-m", strtotime( $value['created_at'] ) ).'-1',
			'created_at'			=> $value['created_at'],
			'modified_at'			=> $value['modified_at']
		);

		$save_error_message	= $pdodb->saveData( array(
			'table' 		=> 'laporan',
			'data'			=> $data_to_save_laporan
		));

		return $save_error_message;
	}

	public function import_pengambilan_penambahan( $value ) {
		global $pdodb;

		$stok_akhir = '';

		unset($value['id_transaksi']);

		// insert table transaksi
		$save_error_message = $pdodb->saveData( array(
			'table' 		=> 'transaksi',
			'data'			=> $value
		));

		// to update stok barang
		$barang = $pdodb->getTableData(array(
			'cols'  => array(
				'id_barang'		=> 'ID',
				'stok'			=> 'Stok Barang'
			),
			'page'  => 1,
			'table' => 'barang',
			'key'   => 'id_barang',
			'where' => $value['id_barang']
		));

		$stok				= current($barang['data'])['stok'];
		$jumlah_			= $value['jumlah'];

		if ( $value['jenis'] == 'pengambilan' ) {
			$modified_at 	= array();
			$new_stok		= $stok - $jumlah_;

			$update_data = array(
				'stok'		=> $new_stok
			);
		}

		if ( $value['jenis'] == 'penambahan' ) {
			$new_stok		= $stok + $jumlah_;
			$modified_at 	= array ( 
				'modified_at'	=> current_time( 'mysql' )
			);

			$update_data = array(
				'stok'			=> $new_stok,
				// 'harga_barang'	=> $value['harga_barang']
			);
		}
	
		$save_error_message	= $pdodb->saveData( array(
			'table' 		=> 'barang',
			'data'			=> array_merge( $update_data, $modified_at ),
			'edit' 			=> !empty( $value['id_barang'] ) ? array( //to /Edit
				'key'		=> 'id_barang',
				'key_value'	=> $value['id_barang']
			) : ''
		));

		$this->create_new_periode_laporan( $value );
		$this->update_laporan_periode( $value );

		return $save_error_message;

	}

	public function create_new_periode_laporan( $value ) {
		global $pdodb;
		
		$select_from_laporan = $pdodb->getTableData( array(
			'cols'  => array(
				'id_laporan'			=> 'ID',
				'stok_awal'				=> 'Stok Awal',
				'jumlah_pengambilan'	=> 'Pengambilan',
				'jumlah_penambahan'		=> 'Penambahan',
			),
			'page'  => 1,
			'table' => 'laporan',
			'key'   => 'id_laporan',
			'where' => array(
				'id_barang'		=> $value['id_barang'],
				'periode_bulan'	=> date( "Y-m", strtotime( $value['created_at'] ) ).'-1'
			)
		) );

		$select_from_laporan = current( $select_from_laporan['data'] );

		if ( empty( $select_from_laporan ) ) {
		
			$stok_akhir = $pdodb->getTableData( array(
				'cols'  => array(
					'id_barang'			=> 'ID',
					'stok_akhir'		=> 'Stok Akhir',
					'harga_barang'		=> 'Harga Barang'
				),
				'page'  => 1,
				'table' => 'laporan',
				'key'   => 'id_laporan',
				'where' => array(
					'id_barang'		=> $value['id_barang'],
					'periode_bulan'	=> date( "Y-m", strtotime( "-1 month", strtotime( $value['created_at'] ) ) ).'-1',
				)
			) );

			$stok_akhir = current( $stok_akhir['data'] );

			$data_to_save	= array(
				'id_barang'		=> $value['id_barang'],
				'stok_awal'		=> $stok_akhir['stok_akhir'],
				'harga_barang'	=> isset( $value['harga_barang'] ) && ! empty( $value['harga_barang'] ) ? $value['harga_barang'] : $stok_akhir['harga_barang'],
				'periode_bulan'	=> date( "Y-m", strtotime( $value['created_at'] ) ).'-1',
				'created_at'	=> date( "Y-m", strtotime( $value['created_at'] ) ).'-1 12:12:12',
				'modified_at'	=> date( "Y-m", strtotime( $value['created_at'] ) ).'-1 12:12:12',
			);
			
			$save_error_message = $pdodb->saveData( array(
				'table' 		=> 'laporan',
				'data'			=> $data_to_save
			));
		}
	}

	public function update_laporan_periode( $value ) {
		global $pdodb;

		$data_pengambilan_to_laporan = $pdodb->getTableData( array(
			'cols'  => array(
				'SUM(jumlah) as jumlah'	=> 'ID',
			),
			'page'  => 1,
			'table' => 'transaksi',
			'key'   => 'id_transaksi',
			'where' => array(
				'id_barang'			=> $value['id_barang'],
				'jenis'				=> 'pengambilan',
				'date(created_at)'	=> date( "Y-m", strtotime( $value['created_at'] ) ).'-1'
			)
		) );

		$data_pengambilan_to_laporan	= current( $data_pengambilan_to_laporan['data'] );
		$data_penambahan_to_laporan 	= $pdodb->getTableData( array(
			'cols'  => array(
				'SUM(jumlah) as jumlah'	=> 'ID'
			),
			'page'  => 1,
			'table' => 'transaksi',
			'key'   => 'id_transaksi',
			'where' => array(
				'id_barang'			=> $value['id_barang'],
				'jenis'				=> 'penambahan',
				'date(created_at)'	=> date( "Y-m", strtotime( $value['created_at'] ) ).'-1'
			)
		) );

		$data_penambahan_to_laporan 	= current( $data_penambahan_to_laporan['data'] );


		$select_from_laporan1 = $pdodb->getTableData( array(
			'cols'  => array(
				'id_laporan'			=> 'ID',
				'stok_awal'				=> 'Stok Awal',
				'jumlah_pengambilan'	=> 'Pengambilan',
				'jumlah_penambahan'		=> 'Penambahan'
			),
			'page'  => 1,
			'table' => 'laporan',
			'key'   => 'id_laporan',
			'where' => array(
				'id_barang'		=> $value['id_barang'],
				'periode_bulan'	=> date( "Y-m", strtotime( $value['created_at'] ) ).'-1'
			)
		) );

		$select_from_laporan1 = current( $select_from_laporan1['data'] );

		$save_error_message	= $pdodb->saveData( array(
			'table' 		=> 'laporan',
			'data'			=> array(
				'jumlah_pengambilan'	=> !empty ( $data_pengambilan_to_laporan['jumlah'] ) ? $data_pengambilan_to_laporan['jumlah'] : '0',
				'jumlah_penambahan'		=> !empty ( $data_penambahan_to_laporan['jumlah'] ) ? $data_penambahan_to_laporan['jumlah'] : '0'
			),
			'edit' 			=> array(
				'key'		=> 'id_laporan',
				'key_value'	=> $select_from_laporan1['id_laporan']
			)
		) );

		$select_from_laporan2 = $pdodb->getTableData( array(
			'cols'  => array(
				'id_laporan'			=> 'ID',
				'stok_awal'				=> 'Stok Awal',
				'jumlah_pengambilan'	=> 'Pengambilan',
				'jumlah_penambahan'		=> 'Penambahan'
			),
			'page'  => 1,
			'table' => 'laporan',
			'key'   => 'id_laporan',
			'where' => array(
				'id_barang'		=> $value['id_barang'],
				'periode_bulan'	=> date( "Y-m", strtotime( $value['created_at'] ) ).'-1'
			)
		) );

		$select_from_laporan2 	= current( $select_from_laporan2['data'] );
		$stok_akhir 			= $select_from_laporan2['stok_awal'] - $select_from_laporan2['jumlah_pengambilan'] + $select_from_laporan2['jumlah_penambahan'];

		$save_error_message	= $pdodb->saveData( array(
			'table' 		=> 'laporan',
			'data'			=> array(
				'stok_akhir'	=> $stok_akhir
			),
			'edit' 			=> array(
				'key'		=> 'id_laporan',
				'key_value'	=> $select_from_laporan2['id_laporan']
			)
		));
	}

	public function cart( $jenis_transaksi ) {
		global $pdodb;

		$barang = $pdodb->getTableData(array(
			'cols'  => array(
				'id_barang'		=> 'ID',
				'nama_barang'	=> 'Nama Barang',
				'harga_barang'	=> 'Harga Barang',
				'stok'			=> 'Stok Barang'
			),
			'page'  => 1,
			'table' => 'barang',
			'key'   => 'id_barang'
		));

		foreach ( $barang['data'] as $key => $value ) {
			$barang_search[$key] = array(
				'search_name'	=> $value['nama_barang'],
				'search_id'		=> $value['id_barang'],
				'harga_barang'	=> $value['harga_barang'],
				'stok'			=> $value['stok']
			);
		}

		$sections = array(
			array(
				'title'		=> 'Keranjang',
				'fields' 	=> array(
					array(
						'name' 		=> 'jenis_transaksi',
						'label'		=> 'Jenis Transaksi',
						'type' 		=> 'text',
						'data'		=> $jenis_transaksi,
						'required'	=> true,
						'class'		=> 'hidden'
					),
					array(
						'name' 		=> 'id_pegawai',
						'label'		=> 'Pegawai',
						'type'		=> 'select',
						'full'		=> false,
						'value'		=> $pdodb->getOptions( 'pegawai', 'id_pegawai', 'nama_pegawai' ),
						'data'		=> null,
						'required'	=> true
					),
					array(
						'name' 			=> 'barang',
						'id'			=> 'search_id',
						'label'			=> 'Barang',
						'type' 			=> 'search-box',
						'data_list'		=> isset( $barang_search ) ? $barang_search : '',
						'data_search'	=> isset( $barang_search ) ? $barang_search : '',
						'data'			=> isset( $id_barang ) ? $id_barang : '',
						'editable'		=> isset( $id_barang ) ? false : true,
						'required'		=> true
					),
					array(
						'type'		=> 'table',
						'fields'	=> array( '', 'id_barang', 'nama_barang', 'jumlah', 'harga_satuan', 'total' ),
						'required'	=> true
					),
					
					array(
						'name' 		=> 'total_harga',
						'label'		=> 'Total Harga',
						'type'		=> 'label',
						'full'		=> false,
						'data'		=> null,
						'required'	=> false,
						'editable'	=> false
					),
				)
			)
		);

		Flight::render( 'cart', array(
			'heading' 		=> ucwords( $jenis_transaksi. ' Barang' ),
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

	public function save_cart( $jenis_transaksi ) {
		global $pdodb;

		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'save_cart' ) {
			
			try {
			
				$id_pegawai			= isset( $_REQUEST['id_pegawai'] ) && ! empty( $_REQUEST['id_pegawai'] ) ? $_REQUEST['id_pegawai'] : false;
				$data 				= isset( $_REQUEST['data'] ) && ! empty( $_REQUEST['data'] ) ? $_REQUEST['data'] : false;

				if ( ! $id_pegawai ) {
					throw new Exception( 'Invalid Pegawai' );
				}

				if ( ! $data ) {
					throw new Exception( 'Invalid Data Barang' );
				}

				foreach ( $data['headers'] as $key => $value ) {
					$value = strtolower( str_replace( ' ','_', $value) );

					if ( strstr( $value, 'id_barang' ) == $value ) {
						$id_barang_key = $key;
					}

					if ( strstr( $value, 'jumlah' ) == $value ) {
						$jumlah_key = $key;
					}

					if ( strstr( $value, 'harga_satuan' ) == $value ) {
						$harga_satuan_key = $key;
					}
				}

				if ( ! isset( $id_barang_key ) ) {
					throw new Exception( 'Invalid ID Barang' );
				}

				if ( ! isset( $jumlah_key ) ) {
					throw new Exception( 'Invalid Jumlah Barang' );
				}

				foreach ( $data['value'] as $key => $value ) {
					if ( $key === $id_barang_key ) {
						$id_barang = $value;
					}

					if ( $key === $jumlah_key ) {
						$jumlah_barang = $value;
					}

					if ( $key === $harga_satuan_key ) {
						$harga_satuan = $value;
					}
				}

				foreach ( $id_barang as $key => $value ) {
					$new_data = array(
						'id_pegawai'	=> $id_pegawai,
						'id_barang' 	=> $id_barang[$key],
						'jumlah'		=> $jumlah_barang[$key],
						'jenis'			=> $jenis_transaksi,
						'created_at'	=> current_time( 'mysql' )
					);

					$barang = $pdodb->getTableData( array(
						'cols'  => array(
							'id_barang'		=> 'ID',
							'stok'			=> 'Stok Barang'
						),
						'page'  => 1,
						'table' => 'barang',
						'key'   => 'id_barang',
						'where' => $new_data['id_barang']
					));

					$stok				= current( $barang['data'] )['stok'];
					$$jenis_transaksi	= $new_data['jumlah'];

					if ( $jenis_transaksi == 'pengambilan' ) {
						$modified_at = array();
						$new_stok	= $stok - $$jenis_transaksi;
					}

					if ( $jenis_transaksi == 'penambahan' ) {
						$new_stok	= $stok + $$jenis_transaksi;

						$modified_at 	= array ( 
							'modified_at'	=> current_time( 'mysql' )
						);
					}

					$update_data = array(
						'stok'			=> $new_stok,
						'harga_barang'	=> $harga_satuan[$key],
					);

					// if ( ! $this->check_laporan( $jenis_transaksi, $id_barang[$key], date("Y-m", strtotime( current_time( 'mysql' ) ) ) ) ) {
					// 	throw new Exception( 'Belum Tutup Buku Bulan Sebelumnya' );
					// }

					$save_error_message	= $pdodb->saveData( array(
						'table' 		=> 'barang',
						'data'			=> array_merge( $update_data, $modified_at ),
						'edit' 			=> ! empty( $new_data['id_barang'] ) ? array( //to /Edit
							'key'		=> 'id_barang',
							'key_value'	=> $new_data['id_barang']
						) : ''
					));

					$save_error_message	= $pdodb->saveData( array(
						'table' 		=> 'transaksi',
						'data'			=> $new_data
					));
				}
	
				if ( ! empty( $save_error_message['error'] ) ) {
	
					throw new Exception( $save_error_message );
					
				} else {
	
					$response = array(
						'success'	=> true,
						'message'	=> 'Transaksi Berhasil Disimpan'
					);

					Flight::addMessage( 'Transaksi Berhasil Disimpan', 'success' );

				}

			} catch ( Exception $e ) {
				$response = array(
					'success' 	=> false,
					'message'	=> $e->getMessage()
				);
			}
	
			Flight::json( $response );
			
		}
	}

	public function check_laporan( $jenis, $id_barang, $periode ) {

		global $pdodb;

		if ( $periode ) {
            $periode = explode( '-', $periode );
        }

		$cols = array(
			'id_laporan'			=> 'ID',
			'id_barang'				=> 'ID Barang',
			'jumlah_pengambilan'	=> 'Jumlah Pengambilan',
			'jumlah_penambahan'		=> 'Jumlah Penamabahan',
			'periode_bulan'			=> 'Periode'
		);

		$where = array(
			'id_barang'								=> $id_barang,
			'YEAR(date(laporan.periode_bulan))'  	=> $periode[0],
            'MONTH(date(laporan.periode_bulan))' 	=> $periode[1]
		);

		$laporan = $pdodb->getTableData( array(
			'cols'  	=> $cols,
			'page'  	=> 1,
			'table' 	=> 'laporan',
			'key'   	=> 'id_laporan',
			'join'  	=> isset( $join ) && $join ? $join : false,
			'where' 	=> isset( $where ) && $where ? $where : false,
			'group_by' 	=> isset( $group_by ) && $group_by ? $group_by : false
		));

		if ( empty( $laporan['data'] ) ) {
			return false;
		} else {
			return true;
		}
	}

}

ATK_Barang::init();
