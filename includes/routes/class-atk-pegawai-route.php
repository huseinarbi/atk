<?php
/**
 * ATK_Admin_Route Class.
 *
 * @class       ATK_Admin_Route
 * @version		1.0
 * @author huseinarbi <huseinarbi66@gmail.com>
 */

class ATK_Pegawai_Route extends ATK_Route {

    public function handleDashboard() {

		$this->setTitle('Dashboard');
        Flight::render('dashboard');
        
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
				'route'    => '/prediksi(/page/@page:[0-9]+)',
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
			)
		);
    }
}
