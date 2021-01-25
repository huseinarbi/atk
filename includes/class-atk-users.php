<?php
/**
 * ATK_Users Class.
 *
 * @class       ATK_Users
 * @version		1.0
 * @author huseinarbi <huseinarbi66@gmail.com>
 */

class ATK_Users {
    /**
	 * Singleton method
	 *
	 * @return self
	 */
	public static function init(){
		static $instance = false;

		if( ! $instance ){
			$instance = new ATK_Users();
		}

		return $instance;
	}
	
    public function __construct() {
		$this->_getMethod = Flight::request()->method;
	}

    public function view_data( $page = false ) {
		global $pdodb;

        if ( empty( $page ) ) {
			$page = 1;
		}

		$cols = array(
			'id_pegawai'    => 'ID',
			'nama_pegawai'	=> 'Nama Pegawai',
			'bagian'	 	=> 'Bagian'
		);

        Flight::render( 'table', array(
			'heading' 		=> 'Daftar User',
			'base_url' 		=> get_url('user'),
			'add'  			=> 'Tambah User',
			'btn_download'	=> array(
				array(
					'string'	=> 'Template User',
					'id'		=> 'download_user',
					'url'		=> 'uploads/Template_pegawai.xlsx'
				)
			),
			'table' 		=> $pdodb->getTableData( array(
				'cols'  		=> $cols,
				'page'  		=> $page,
				'table' 		=> 'pegawai',
				'key'   		=> 'id_pegawai'
			))
		));
	}
	
	public function edit_data( $action, $id = false ) {
		global $pdodb;
		
		$save_error_message = array();

		if ( 'POST' === $this->_getMethod ) {

			$save_error_message	= $pdodb->saveData( array(
				'table' 		=> 'pegawai',
				'data'			=> $_POST,
				'edit' 			=> ! empty( $id ) && $action === 'edit' ? array( //to /Edit
					'key'		=> 'id_pegawai',
					'key_value'	=> $id
				) : ''
			));

			if ( ! empty( $save_error_message['error'] ) ) {
				Flight::addMessage( $save_error_message, 'error' );
			} else {
				Flight::addMessage( 'Data berhasil disimpan', 'success' );
				Flight::redirect( '/user' );
				exit();
			}
		}

		if ($id) {
			$data_users = $pdodb->getTableData( array(
				'cols'  => array( 
					'id_pegawai' 	=> 'ID', 
					'nama_pegawai' 	=> 'Nama Pegawai',
					'bagian' 		=> 'Bagian',
					'email'			=> 'Email'
				),
				'page'  => '1',
				'table' => 'pegawai',
				'key'   => 'id_pegawai',
				'join'  => array(
					'users' => 'id'
				),
				'where' => $id
			));

			foreach ( $data_users['data'] as $key => $field ) {
				extract( $field );
			}
		}

		$sections = array(
			array(
				'title'		=> 'Options',
				'fields' 	=> array(
					array(
						'name' 		=> 'nama_pegawai',
						'label'		=> 'Nama Pegawai',
						'type' 		=> 'text',
						'data'		=> isset( $nama_pegawai ) ? $nama_pegawai : '',
						'required'	=> true
					),
					array(
						'name' 		=> 'bagian',
						'label'		=> 'Bagian',
						'type' 		=> 'text',
						'data'		=> isset( $bagian ) ? $bagian : '',
						'required'	=> true
					)
				)
			)
		);

		Flight::render( 'form', array(
			'heading' 	=> 'Add Pegawai',
			'sections' 	=> $sections
		));
	}

	public function import_user( $data ) {
		echo '<pre>';
		print_r( $data );
		exit();
	}
}

ATK_Users::init();