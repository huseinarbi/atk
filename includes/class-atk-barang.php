<?php
/**
 * ATK_Barang Class.
 *
 * @class       ATK_Barang
 * @version		1.0
 * @author huseinarbi <huseinarbi66@gmail.com>
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
		Flight::route( '/barang/detail/view/@id', array( $this, 'atk_barang_view_detail' ) );
    }

    public function atk_barang_view_detail( $id ) {
        $sections = array(
			array(
				'title'		=> 'Detail',
				'fields' 	=> array(
					array(
						'name' 		=> 'nama_barang',
						'label'		=> 'ID Barang',
						'type' 		=> 'text',
						'data'		=> isset($id) ? $id : '',
						'required'	=> true
					)
				)
			)
		);

		Flight::render('form', array(
			'heading' 	=> 'Detail Barang',
			'sections' 	=> $sections
		));
    }

}

ATK_Barang::init();
