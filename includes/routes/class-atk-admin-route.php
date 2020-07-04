<?php
/**
 * ATK_Admin_Route Class.
 *
 * @class       ATK_Admin_Route
 * @version		1.0
 * @author huseinarbi <huseinarbi66@gmail.com>
 */

class ATK_Admin_Route extends ATK_Route {
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
					'icon'  	=> 'ni ni-circle-08',
					'br'		=> true
				)
			)
		);
    }
}
