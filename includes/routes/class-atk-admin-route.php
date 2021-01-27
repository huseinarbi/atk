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
	
	public function handleUser( $page ) {
		if ( Flight::request()->method == 'GET' ) {
			$this->setTitle( 'Daftar User' );
			$this->users->view_data( $page );
		}
		
		if ( Flight::request()->method == 'POST' ) {
			$this->setTitle( 'Import User' );
			$this->users->import_user( $_REQUEST['data'] );
		}
	}

	public function handleEditUser( $action, $id_user ) {
		if ( $action == 'delete' ) {
			$this->setTitle( 'Delete user' );
			$this->users->delete_data( $action, $id_user );
		} else {
			$this->setTitle( 'Edit User' );
			$this->users->edit_data( $action, $id_user );
		}
	}

    public function handleBarang( $page ) { 
		if ( Flight::request()->method == 'GET' ) {
			$this->setTitle( 'Barang ATK' );
			$this->barang->view_data( $page );
		}
		
		if ( Flight::request()->method == 'POST' ) {
			$this->barang->import_barang( $_REQUEST['data'] );
		}
    }

    public function handleEditBarang( $action, $id ) {
        $this->setTitle( 'Edit Barang' );
        $this->barang->edit_data( $action, $id );
	}
	
	public function handlePengambilan() {
		if ( Flight::request()->method == 'GET' ) {
			$this->setTitle( 'Pengambilan Barang' );
			$this->barang->cart( 'pengambilan' );
		}

		if ( Flight::request()->method == 'POST' ) {
			$this->setTitle( 'Pengambilan Barang' );
			$this->barang->save_cart( 'pengambilan' );
		}
		
	}

	public function handlePenambahan() {
		if ( Flight::request()->method == 'GET' ) {
			$this->setTitle( 'Penambahan Barang' );
			$this->barang->cart( 'penambahan' );
		}

		if ( Flight::request()->method == 'POST' ) {
			$this->setTitle( 'Penambahan Barang' );
			$this->barang->save_cart( 'penambahan' );
		}
	}

	public function handlePrediksi( $page ) {
		$this->setTitle( 'Prediksi Barang' );
		$this->prediksi->view_data( $page );
	}

	public function handleDetailPrediksi( $id_barang ) {
		$this->setTitle( 'Detail Prediksi Barang' );
		$this->prediksi->view_detail( $id_barang );
	}

	public function handleLaporan( $section, $page ) {
		$this->setTitle( 'Laporan' );
		$this->laporan->view_data( $section, $page );
	}

	public function handleTutupBuku() {
		if ( Flight::request()->method == 'GET' ) {
			$this->setTitle( 'Tutup Buku' );
			$this->laporan->tutup_buku();
		}

		if ( Flight::request()->method == 'POST' ) {
			if ( $_POST['action'] == 'check_tutup_buku' ) {
				$this->laporan->check_tutup_buku( $_POST['action'] );
			}

			if ( $_POST['action'] == 'save_tutup_buku' ) {
				$this->setTitle( 'Tutup Buku' );
				$this->laporan->save_tutup_buku( $_REQUEST['date'] );
			}
		}
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
				'route'    => '/user(/page/@page:[0-9]+)',
				'callback' => array( $this, 'handleUser' ),
				'menu'     => array(
					'url'		=> get_url( '/user' ),
					'text'  	=> 'User',
					'icon'  	=> 'ni ni-circle-08',
				)
			),
			array(
				// 'route'    => '/penilaian/harian/(@add(/@idmapel))',
				'route'    => '/user/(@edit(/@id_user))',
				'callback' => array($this, 'handleEditUser'),
				'menu'     => false
			),
			
			array(
				'route'    => '/barang(/page/@page:[0-9]+)',
				'callback' => array( $this, 'handleBarang' ),
				'menu'     => array(
					'url'		=> get_url( '/barang' ),
					'text'  	=> 'Barang',
					'icon'  	=> 'ni ni-books',
					'br'		=> true
				)
			),
            array(
				'route'    => '/barang/(@edit(/@id_barang))',
				'callback' => array($this, 'handleEditBarang'),
				'menu'     => false
            ),
            array(
				'route'    => '/pengambilan',
				'callback' => array($this, 'handlePengambilan'),
				'menu'     => array(
					'url'		=> get_url( '/pengambilan' ),
					'text'  	=> 'Pengambilan',
					'icon'  	=> 'ni ni-bold-up'
				)
			),	
			array(
				'route'    => '/penambahan',
				'callback' => array($this, 'handlePenambahan'),
				'menu'     => array(
					'url'		=> get_url( '/penambahan' ),
					'text'  	=> 'Penambahan',
					'icon'  	=> 'ni ni-bold-down',
					'br'		=> true
				)
			),
			array(
				'route'    => '/prediksi(/page/@page:[0-9]+)/',
				'callback' => array($this, 'handlePrediksi'),
				'menu'     => array(
					'url'		=> get_url( '/prediksi' ),
					'text'  	=> 'Prediksi Barang',
					'icon'  	=> 'ni ni-single-copy-04',
					'br'		=> true
				)
			),
			array(
				'route'    => '/prediksi/(detail(/@id_barang))',
				'callback' => array($this, 'handleDetailPrediksi'),
				'menu'     => false
			),
			array(
				'route'    => '/laporan/(@section)(/page/@page:[0-9]+)',
				'callback' => array($this, 'handleLaporan'),
				'menu'     => array(
					'url'		=> get_url( '/laporan/bulanan' ),
					'text'  	=> 'Laporan Stok',
					'icon'  	=> 'ni ni-single-copy-04',
					// 'br'		=> true
				)
			),
			array(
				'route'    => '/tutup-buku',
				'callback' => array($this, 'handleTutupBuku'),
				'menu'     => array(
					'url'		=> get_url( '/tutup-buku' ),
					'text'  	=> 'Tutup Buku',
					'icon'  	=> 'ni ni-single-copy-04',
					'br'		=> true
				)
			)
		);
    }
}
