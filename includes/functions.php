<?php
/**
 * get url of path
 *
 * @since  1.0.0
 *
 * @return [type] [description]
 */
function get_url( $path = '' ){
    $base_url  = Flight::request()->base;
	$protocol  = empty($_SERVER['HTTPS']) ? 'http' : 'https';
	$port      = $_SERVER['SERVER_PORT'];
	$disp_port = ($protocol == 'http' && $port == 80 || $protocol == 'https' && $port == 443) ? '' : ":$port";
	$domain    = $_SERVER['SERVER_NAME'];
	$home_url  = "${protocol}://${domain}${disp_port}${base_url}";

	return trailingslashit( $home_url ) . ltrim( $path, '/\\' );
}

function asset_url( $file ) {
    echo get_url( 'assets/' .$file );
}

/**
 * Add slash to the end of string
 *
 * @since  1.0.0
 *
 * @param  [type] $string [description]
 *
 * @return [type]         [description]
 */
function trailingslashit( $string ) {
    return untrailingslashit( $string ) . '/';
}

/**
 * Remove slash from end of string
 *
 * @since  1.0.0
 *
 * @param  [type] $string [description]
 *
 * @return [type]         [description]
 */
function untrailingslashit( $string ) {
    return rtrim( $string, '/\\' );
}

/**
 * Escape value
 *
 * @since  1.0.0
 *
 * @param  [type] $value [description]
 *
 * @return [type]        [description]
 */
function esc_attr( $value ) {
	return $value;
}

function current_time( $type, $gmt = 0 ) {

	if ( 'mysql' === $type ) {
		$type = 'Y-m-d H:i:s';
	}

	$timezone = new DateTimeZone( 'Asia/Jakarta' );
	$datetime = new DateTime( 'now', $timezone );

	return $datetime->format( $type );
}