<?php
/**
 * ATK_Route Class.
 *
 * @class       ATK_Route
 * @version		1.0
 * @author huseinarbi <huseinarbi66@gmail.com>
 */


abstract class ATK_Route {
    
    protected $routes;

    protected $db;

    protected $need_login = true;
    
    public function __construct() {
        
        $this->db   = new ATK_Db();
        $this->auth = $this->getRoutes();

        Flight::before( 'start', array( $this, 'checkLogin' ) );
		Flight::route( '/login', array( $this, 'handleLogin' ) );
		Flight::route( '/logout', array( $this, 'handleLogout' ) );

		// Setup routes
		foreach ( $this->routes as $key => $data) {
			Flight::route( $data['route'], $data['callback'] );
		}

		// Setup menus
        Flight::view()->set('menu', $this->getMenu() );
        
    }

    public function getRoutes() {
		return array();
	}

	public function setTitle( $title ) {
		Flight::view()->set( 'title', $title );
	}

    protected function getMenu(){
		$menu = array();

		foreach ($this->routes as $data) {

			if ( !isset( $data['menu'] ) || empty( $data['menu'] ) ) {
				continue;
			}

			if ( !isset( $data['menu']['url'] ) ) {
				$data['menu']['url'] =  get_url($data['route']);
			}

			$menu[] = $data['menu'];
		}

		return $menu;
    }
    
    public function handleLogin(){
		$message = false;
		if ( 'POST' === $this->_getMethod() ) {
			$auth = Flight::auth();

			try {
				$username = isset( $_POST['username'] ) ? esc_attr( $_POST['username'] ) : '';
				$password = isset( $_POST['password'] ) ? esc_attr( $_POST['password'] ) : '';
				$remember = isset( $_POST['remember'] ) && 'on' === $_POST['remember'];

				if ( empty( $username ) ) {
					throw new Exception('Silahkan masukkan User ID');
				}

				if ( empty( $password ) ) {
					throw new Exception('Silahkan masukkan Password');
				}

				// keep logged in for one year
				$rememberDuration = $remember ? 60 * 60 * 24 * 365.25 : null;
				$auth->loginWithUsername($username, $password);

				Flight::redirect( '/' );

			} catch (\Delight\Auth\InvalidEmailException $e) {
				$message = 'Email salah.';
			} catch (\Delight\Auth\UnknownUsernameException $e) {
				$message = 'Username tidak ditemukan.';
			} catch (\Delight\Auth\InvalidPasswordException $e) {
				$message = 'Password salah';
			} catch (\Delight\Auth\EmailNotVerifiedException $e) {
				$message = 'Email belum terverifikasi.';
			} catch (\Delight\Auth\UserAlreadyExistsException $e) {
				$message = 'User sudah ada.';
			} catch (\Delight\Auth\TooManyRequestsException $e) {
				$message = 'Terlalu banyak percobaan.';
			} catch (Exception $e) {
				$message = $e->getMessage();
			}
		}

		Flight::render( 'login', array( 'message' => $message ) );
	}

	public function checkLogin( &$params, $output ){
		$request = Flight::request();
		if ( !$this->need_login ) {
			return;
		}

		if ( '/login' === $request->url ) {
			return;
		}

		if ( !Flight::user( 'logged_in' ) ){
			Flight::redirect( '/login' );
		}
	}

	public function handleLogout(){
		Flight::auth()->logOut();
		Flight::redirect( '/login' );
	}

	protected function _getMethod() {
		return Flight::request()->method;
	}

}

