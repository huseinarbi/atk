<?php
require 'vendor/autoload.php';

if ( ! file_exists( 'config.php' ) ) {
	echo "Config not found.";
	exit();
}

require 'config.php';

/**
 * setup db
 */

$db = new PDO ( 'mysql:host='. DB_HOST .';dbname=' . DB_NAME, DB_USER, DB_PASS );
Flight::register( 'db', 'PDODb', array( $db ) );
Flight::register( 'auth', '\Delight\Auth\Auth', array( $db ) );

Flight::map( 'user', function( $key = false ){
	$auth     = Flight::auth();

	$userdata = array(
		'logged_in' => $auth->isLoggedIn(),
		'id' 		=> $auth->getUserId(),
		'username' 	=> $auth->getUsername(),
		'email' 	=> $auth->getEmail(),
		'roles' 	=> $auth->getRoles(),
		'ip' 		=> $auth->getIpAddress(),
	);

	if ( !$key ) {
		return $userdata;
	}

	return isset( $userdata[ $key ] ) ? $userdata[ $key ] : null;
});

Flight::map( 'role', function() {
	$auth     	= Flight::auth();
	$roles 		= $auth->getRoles();
	
	foreach ($roles as $key => $role) {
		if ( $role == 'MODERATOR' ) {
			return 'PEGAWAI';
		} else {
			return 'ADMIN';
		}
	}
} );


/**
 * Set message to session
 *
 * @var string
 */
Flight::map( 'addMessage', function( $message, $type = 'error' ){
	if ( session_status() == PHP_SESSION_NONE ) {
		session_start();
	}

	$_SESSION['message'] = array(
		'message' => $message,
		'type'    => $type
	);
});

Flight::map( 'renderMessage', function( $type = 'error' ){
	if ( !isset( $_SESSION['message'] ) || empty( $_SESSION['message'] ) ) {
		return;
	}

	$message = $_SESSION['message'];
	unset( $_SESSION['message'] );

	Flight::render( 'partials/message', $message );
} );

// Default title
Flight::view()->set('title', 'ATK' );

/**
 * Scan directory and load it
 *
 * @since  1.0.0
 *
 * @param  [type] $dir [description]
 *
 * @return [type]      [description]
 */
function require_all_files( $dir ) {
    // require all php files
    $scan = glob( "$dir/*" );
    foreach ($scan as $path) {
        if (preg_match('/\.php$/', $path)) {
            require_once( $path );
        } elseif (is_dir($path)) {
            require_all_files($path);
        }
    }
}

require_all_files( 'includes' );


/**
 * Handle routes based on role
 *
 * @var [type]
 */
$roles = Flight::user('roles');
switch ( current( $roles ) ) {
	case 'SUPER_ADMIN':
		new ATK_Admin_Route();
        break;
	default:
		new ATK_Pegawai_Route();
		break;
}
new ATK_Project();
Flight::start();