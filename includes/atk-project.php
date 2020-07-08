<?php
/**
 * Plugin Name: ATK
 * Plugin URI: http://atk.deft
 * Description: Custom site development for atk.deft
 * Version: 1.0
 * Author: Husein Arbi
 * Author URI: http://husein.me
 *
 * Text Domain: atk
 *
 * @package ATK
 * @category Plugin
 * @author Husein Arbi
 */

 final class ATK_Project {
     /**
	 * ATK Constructor.
	 */
	public function __construct(){
        $this->includes();
    }
    
    public function includes() {
        include_once( 'class-atk-barang.php' );
    }
 }