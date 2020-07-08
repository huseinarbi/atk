<?php
/**
 * ATK_Admin_Route Class.
 *
 * @class       ATK_Admin_Route
 * @version		1.0
 * @author huseinarbi <huseinarbi66@gmail.com>
 */

class ATK_Admin_Route extends ATK_Route {

    public function handleDashboard() {

		$this->setTitle('Dashboard');
        Flight::render('dashboard');
        
	}
	
	public function handlePegawai( $page ) {

		if (empty( $page ) ) {
			$page = 1;
		}

		$this->setTitle( 'Daftar Pegawai' );

		$cols = array(
			'id_pegawai'    => 'ID',
			'nama_pegawai'	=> 'Nama Pegawai',
			'bagian'	 	=> 'Bagian'
		);

        Flight::render('table', array(
			'heading' 	=> 'Daftar Pegawai',
			'base_url' 	=> get_url('pegawai'),
			'add'  		=> 'Tambah Pegawai',
			'btn_download'	=> array(
				array(
					'string'	=> 'Template pegawai',
					'id'		=> 'download_pegawai',
					'url'		=> 'uploads/Template_pegawai.xlsx'
				)
			),
			'table' 	=> $this->db->getTableData(array(
				'cols'  => $cols,
				'page'  => $page,
				'table' => 'pegawai',
				'key'   => 'id_pegawai'
			))
		));

	}

    public function handleBarang($page) { 

        if (empty($page)) {
            $page = 1;
        }
        
        $this->setTitle('Barang ATK');

        $cols = array(
			'id_barang'     => 'ID',
			'nama_barang'	=> 'Nama Barang',
			'jumlah_barang' => 'Jumlah',
			'satuan' 	    => 'Satuan',
			'harga' 	    => 'Harga'
		);

        Flight::render('table', array(
			'heading' 	=> 'Data Barang',
			'base_url' 	=> get_url('barang'),
			'more_btn' 	=> array(
				'button1' 	=> array(
					'title' 	=> 'Detail',
					'url' 		=> 'detail',
					'action' 	=> 'view'
				)
			),
			'add'  		=> 'Tambah Barang',
			'btn_download'	=> array(
				array(
					'string'	=> 'Template Barang',
					'id'		=> 'download_barang',
					'url'		=> 'uploads/Template_Barang.xlsx'
				)
			),
			'table' 	=> $this->db->getTableData(array(
				'cols'  => $cols,
				'page'  => $page,
				'table' => 'barang',
				'key'   => 'id_barang'
			))
		));
    }

    public function handleEditBarang( $id ) {
        
        $save_error_message = array();

		if ('POST' === $this->_getMethod()) {

			$save_error_message  	= $this->db->saveData(array(
				'table' 	=> 'mapel',
				'data'		=> $_POST,
				'edit' 		=> !empty($id) ? array( //to /Edit
					'key'		=> 'id_mapel',
					'key_value'	=> $id
				) : ''
			));

			if (!empty($save_error_message)) {

                Flight::addMessage($save_error_message, 'error');
                
			} else {

				Flight::addMessage('Data berhasil disimpan', 'success');
				Flight::redirect('/mapel');
                exit();
                
			}
		}

		if ($id) {
			$data_mapel = $this->db->getTableData(array(
				'cols'  => '',
				'page'  => '1',
				'table' => 'mapel',
				'key'   => 'id_mapel',
				'join'  => array(
					'peminatan' => 'id_peminatan'
				),
				'where' => $id
			));

			foreach ($data_mapel['data'] as $key => $field) {
				extract($field);
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
						'data'		=> isset($tingkat) ? $tingkat : '',
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
	
	public function handlePengambilan() {

		$sections = array(
			array(
				'title'		=> 'Keranjang',
				'fields' 	=> array(
					array(
						'name' 		=> 'id',
						'label'		=> 'Pegawai',
						'type'		=> 'select',
						'full'		=> false,
						'value'		=> $this->db->getOptions('users', 'id', 'username'),
						'data'		=> null,
						'required'	=> true
					),
					array(
						'name' 		=> 'id_barang',
						'label'		=> 'ID Barang / Nama Barang',
						'type'		=> 'text',
						'full'		=> false,
						'data'		=> null,
						'required'	=> true
					),
					array(
						'name' 		=> 'id_barang',
						'label'		=> 'ID Barang / Nama Barang',
						'type'		=> 'text',
						'full'		=> false,
						'data'		=> null,
						'required'	=> true
					),
					array(
						'type'		=> 'table',
						'fields'	=> array('id_barang', 'nama_barang', 'jumlah', 'harga_satuan', 'total'),
						'required'	=> true
					),
					
					array(
						'name' 		=> 'total_harga',
						'label'		=> 'Total Harga',
						'type'		=> 'label',
						'full'		=> false,
						'data'		=> null,
						'required'	=> true
					),
				)
			)
		);

		Flight::render('form', array(
			'heading' 		=> 'Pengambilan Barang',
			'sections' 		=> $sections,
			'custom_button'	=> array(
				array(
					'id' 	=> 'submit',
					'type'	=> 'submit',
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
    
    public function getRoutes() {
        return array(
			array(
				'route'    => '/',
				'callback' => array( $this, 'handleDashboard' ),
				'menu'     => array(
					'text'    	=> 'Dashboard',
					'icon'     	=> 'ni ni-tv-2',
					'br'		=> true
				)
			),
			array(
				'route'    => '/pegawai(/page/@page:[0-9]+)',
				'callback' => array( $this, 'handlePegawai' ),
				'menu'     => array(
					'url'		=> get_url( '/pegawai' ),
					'text'  	=> 'Pegawai',
					'icon'  	=> 'ni ni-circle-08',
				)
            ),
			array(
				'route'    => '/barang(/page/@page:[0-9]+)',
				'callback' => array( $this, 'handleBarang' ),
				'menu'     => array(
					'url'		=> get_url( '/barang' ),
					'text'  	=> 'Barang',
					'icon'  	=> 'ni ni-circle-08',
					'br'		=> true
				)
			),
            array(
				'route'    => '/barang/(add|edit(/@id_barang))',
				'callback' => array($this, 'handleEditBarang'),
				'menu'     => false
            ),
            array(
				'route'    => '/pengambilan',
				'callback' => array($this, 'handlePengambilan'),
				'menu'     => array(
					'url'		=> get_url( '/pengambilan' ),
					'text'  	=> 'Pengambilan',
					'icon'  	=> 'ni ni-circle-08'
				)
			),
			array(
				'route'    => '/penambahan',
				'callback' => array($this, 'handlePenambahan'),
				'menu'     => array(
					'url'		=> get_url( '/penambahan' ),
					'text'  	=> 'Penambahan',
					'icon'  	=> 'ni ni-circle-08',
					'br'		=> true
				)
			),
			array(
				'route'    => '/laporan-stok',
				'callback' => array($this, 'handleLaporanStok'),
				'menu'     => array(
					'url'		=> get_url( '/laporan-stok' ),
					'text'  	=> 'Laporan Stok',
					'icon'  	=> 'ni ni-circle-08',
					'br'		=> true
				)
			)
		);
    }
}
