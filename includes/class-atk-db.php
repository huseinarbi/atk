<?php
/**
 * ATK_Db Class.
 *
 * @class       ATK_Db
 * @version		1.0
 * @author huseinarbi <huseinarbi66@gmail.com>
 */

class ATK_Db {

    protected $db;
    protected $auth;

    public function __construct() {

        $this->db       = Flight::db();
        $this->auth     = Flight::auth();

        DEFINE( 'DATA_PER_PAGE', 1 );

    }

    public function getTableData( $args = array() ) {

		$per_page = defined( 'DATA_PER_PAGE' ) ? DATA_PER_PAGE : 10; // perpage
		$page     = intval( $args['page'] );
		$offset   = ( $page - 1 ) * $per_page;
		$join     = isset( $args['join'] ) && !empty( $args['join'] ) ? $args['join'] : false;

		if ( isset($args['join']) && is_array( $args['join'] ) ) {
			foreach ($join as $j_table => $j_field) {
				if ( is_array( $j_field ) ) { //if table join with other table join
					foreach ($j_field as $j_table2 => $j_field2) {
						$this->db->join($j_table, sprintf( '%s.%s=%s.%s', $j_table, $j_field2, $j_table2, $j_field2 ), 'LEFT');
					}
				} else {
					$this->db->join($j_table, sprintf( '%s.%s=%s.%s', $j_table, $j_field, $args['table'], $j_field ), 'LEFT');
				}

			}
		}

		if ( isset( $args['where'] ) ) {
			if ( is_array($args['where']) ) { //if where not primary key
				$where = $args['where'];
				foreach ($where as $where_key => $where_field) {
					$this->db->where($where_key, $where_field);
				}
			} else {
				$this->db->where($args['key'], $args['where']);
			}
		}

		if( isset( $args['cols'] ) ) {
			foreach ($args['cols'] as $column_key => $column) {
				if ( is_array($column) ) {
					foreach ($column as $key => $value) {
						$cols[$key.'.'.$column_key] = $value;
					}
				} else {
					$cols[$column_key] = $column;
				}
			}
		}

		try {
			if ( isset($args['group_by']) ) {
				$this->db->groupBy($args['group_by']);
			}

			if ( isset($args['order_by']) ) {
				foreach ($args['order_by'] as $key => $value) {
					$this->db->orderBy($key,$value);
				}
			}

            $data	= $this->db->withTotalCount()->get( $args['table'], array($offset, $per_page), !empty($cols) ? array_keys( $cols ) : '' );
			$error	= $this->db->getLastError();

			if ( !empty($error[2]) ) {
				throw new Exception($error[2]);
			}

		} catch (\Exception $e) {
			$error_catch = $e->getMessage();
		}

		$total_page = ceil( $this->db->totalCount / $per_page );
		$table_data = array(
			'cols' 			=> $args['cols'],
			'cols_view' 	=> !empty($args['cols_view']) ? $args['cols_view'] : '',
			'key'  			=> $args['key'],
			'data' 			=> $data,
			'pagination' 	=> array(
				'page' 		=> $page,
				'total' 	=> $total_page
			),
			'error_catch' => isset($error_catch) ? $error_catch : ''
		);

		return $table_data;
	}
	
	public function getOptions( $table, $field_id, $field_label ){
		$data		= $this->db->get( $table );
		$options 	= array();

		foreach ($data as $key => $value) {
			$options[$key] = array(
				'id' => $value[ $field_id ],
				'values' => $value[ $field_label ]
			);
		}

		return $options;
	}

    public function saveData( $args = array() ) {

		$error 		= '';

		try {

			$data 	        = $args['data'];
			$table 	        = $args['table'];
			$login	        = isset($args['login']) ? $args['login'] : '';
			$current_role   = isset($login['current_role']) ? $login['current_role'] : '';

			if ( is_array( $args['data'] ) ) {
				foreach ($args['data'] as $field => $value) {
					$data[$field] = !empty($value) ? $value : NULL;
				}
			}

			if ( isset( $args['edit'] ) ) {
				$edit = $args['edit'];

				$this->db->where($edit['key'],$edit['key_value']);

				if ($this->db->update($table, $data)) {
					if ( !empty( $login ) && !empty($current_role) ){
						$this->editUserLogin( $login['email'], $login['username'], $login['password'], $login['role'], $current_role );
					}
					
				} else {
				    throw new Exception('Data Gagal Diedit');
                }
                
			} else {

				$id = $this->db->insert($table, $data);

				if ( empty($this->db->getLastError()[2]) ) {
					if ( !empty( $login ) ) {
						$this->setUserLogin( $login['email'], $login['username'], $login['password'], $login['role'] );
					}
					$this->db->delete('users_throttling');	
				}
				
			}

			if ( !empty($this->db->getLastError()[2]) ) {
				throw new Exception($this->db->getLastError()[2]);
			}

		}  catch (\Delight\Auth\InvalidEmailException $e) {
			$error = 'Email salah.';
		} catch (\Delight\Auth\UnknownUsernameException $e) {
			$error = 'Username tidak ditemukan.';
		} catch (\Delight\Auth\InvalidPasswordException $e) {
			$error = 'Password salah';
		} catch (\Delight\Auth\EmailNotVerifiedException $e) {
			$error = 'Email belum terverifikasi.';
		} catch (\Delight\Auth\UserAlreadyExistsException $e) {
			$error = 'User sudah ada.';
		} catch (\Delight\Auth\TooManyRequestsException $e) {
			$error = 'Terlalu banyak percobaan.';
		} catch (Exception $e) {
			$error = $e->getMessage();
		}

		return $error;
    }

	public function deleteData( $args = array() ) {

		$error = false;

		try {

			$table 			= $args['table'];
			$delete 		= $args['delete'];
			$delete_login 	= !empty($args['login']) ? $args['login'] : '' ;

			if( !empty( $args['login'] ) ) {
				$this->deleteUserLogin( $delete_login['key'] );
			}

			foreach ($delete as $key => $delete_value) {
				$this->db->where($delete_value['key'], $delete_value['key_value']);
			}

			if($this->db->delete($table)) {
				throw new Exception('Data Gagal Dihapus');
			}

		} catch (\Delight\Auth\UnknownUsernameException $e) {
		    die('Unknown username');
		} catch (\Delight\Auth\AmbiguousUsernameException $e) {
		    die('Ambiguous username');
		} catch (Exception $e) {
			$error = $e->getMessage();
		}

		return $error;
    }
    
    public function setUserLogin( $email, $username, $password, $role ){

		$roles = array_flip(\Delight\Auth\Role::getMap());

		try {

			if ( !isset( $roles[$role] ) ) {
				throw new Exception('Role Tidak Valid');
			}

			$userId = $this->auth->register($email, $password, $username);
			$this->auth->admin()->addRoleForUserById($userId, $roles[$role]);

		} catch (\Exception $e) {
			throw( $e );
		}
	}

	public function editUserLogin( $email, $username, $password, $role, $current_role ) {

		$roles = array_flip(\Delight\Auth\Role::getMap());

		try {
			if ( !isset( $roles[$role] ) ) {
				throw new Exception('Role Tidak Valid');
			}
			$this->auth->admin()->removeRoleForUserByUsername($username, $roles[$current_role]);
			$this->auth->admin()->addRoleForUserByUsername($username, $roles[$role]);
			// $this->auth->admin()->addRoleForUserById($userId, $roles[$role]);
		} catch (\Exception $e) {
			throw( $e );
		}


	}

	public function deleteUserLogin( $username ) {
		try {
	    	$this->auth->admin()->deleteUserByUsername( $username );
		}
		catch (\Exception $e) {
			throw( $e );
		}
	}

    
}