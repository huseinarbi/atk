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
			'bagian'	 	=> 'Bagian',
			'username'		=> 'Username'
		);

		$data = $pdodb->getTableData( array(
			'cols'  		=> $cols,
			'page'  		=> $page,
			'table' 		=> 'pegawai',
			'key'   		=> 'id_pegawai',
			'join'  		=> array(
				'users' => 'id'
			),
		));

        Flight::render( 'table', array(
			'heading' 		=> 'Daftar User',
			'base_url' 		=> get_url('user'),
			'add'  			=> 'Tambah User',
			'disable_import'=> true,
			// 'btn_download'	=> array(
			// 	array(
			// 		'string'	=> 'Template User',
			// 		'id'		=> 'download_user',
			// 		'url'		=> 'uploads/Template_pegawai.xlsx'
			// 	)
			// ),
			'table' 		=> $data
		));
	}

	public function delete_data( $action, $user_id = false ) {
		global $pdodb;

		$data_to_delete = array(
			'table'		=> 'pegawai',
			'login'		=> array(
				'key'	=> $this->get_user_role_data($user_id)['username']
			),
			'delete' 	=> array(
				array(
					'key'		=> 'id_pegawai',
					'key_value'	=> $user_id
				)
			)
		);

		if ( empty( $this->get_user_role_data($user_id) ) ) {
			unset( $data_to_delete['login'] );
		}

		$delete_error_message	= $pdodb->deleteData( $data_to_delete );

		if ( ! empty( $delete_error_message ) ) {
			Flight::addMessage($delete_error_message);
		} else {
			Flight::addMessage('Data berhasil dihapus', 'success');
		}

		Flight::redirect('/user');
		exit();
	}

	public function edit_data( $action, $id = false ) {
		global $pdodb;
		
		$save_error_message = array();

		if ( 'POST' === $this->_getMethod ) {

			$request_action 			= !empty($_REQUEST['action']) ? $_REQUEST['action'] : '';

			$username  		= str_replace( ' ', '_', strtolower(esc_attr( $_POST['nama_pegawai'] ) ) );
			$role 			= $_POST['roles'];
			if ( $role == 'SUPER_ADMIN' ) {
				$password 		= 'admin';
			} else {
				$password 		= '123456';
			}
			
			$email     		= sprintf('%s@local.com', $username);

			$data_pegawai	= array(
				'nama_pegawai'	=> $_POST['nama_pegawai'],
				'bagian'		=> $_POST['bagian']
			);

			$save_error_message	= $pdodb->saveData( array(
				'table' 		=> 'pegawai',
				'data'			=> $data_pegawai,
				'edit' 			=> ! empty( $id ) && $action === 'edit' ? array( //to /Edit
					'key'		=> 'id_pegawai',
					'key_value'	=> $id
				) : '',
				'login'		=> array(
					'email' 	=> $email,
					'username' 	=> $username,
					'password' 	=> $password,
					'role' 		=> $role
				)
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

			$user_roles = Flight::user('roles');

			if ( $user_roles ) {
				$roles = current( $user_roles );

				if ( $roles == 'SUPER_ADMIN' ) {
					$role = 'Admin';
				} else {
					$role = 'Pegawai';
				}
			}

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
					),
					array(
						'name'		=> 'roles',
						'label'		=> 'Status',
						'type'		=> 'select-option',
						'value'		=> array(
							array(
								'id'		=> 'SUPER_ADMIN',
								'values'	=> 'Admin'
							),
							array(
								'id'		=> 'EDITOR',
								'values'	=> 'Pegawai'
							)
						),
						'data'		=> isset($role) ? $role : '',
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

	public function get_user_role_data( $id_pegawai ) {
		global $pdodb;

		if ( empty( $page ) ) {
			$page = 1;
		}

		$cols = array(
			'id_pegawai'    => 'ID',
			'nama_pegawai'	=> 'Nama Pegawai',
			'bagian'	 	=> 'Bagian',
			'username'		=> 'Username'
		);

		$data = $pdodb->getTableData( array(
			'cols'  		=> $cols,
			'page'  		=> $page,
			'table' 		=> 'pegawai',
			'key'   		=> 'id_pegawai',
			'join'  		=> array(
				'users' => 'id'
			),
		));

		$data = current( $data['data'] );

		return $data;
	}
}

ATK_Users::init();