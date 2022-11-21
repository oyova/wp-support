<?php

namespace Oyova\WpSupport;

use Illuminate\Database\Capsule\Manager as DatabaseManager;

class Eloquent {

	public function boot(): void {

		$database_manager = new DatabaseManager();

		$database_manager->addConnection(
			array(
				'driver'    => 'mysql',
				'host'      => DB_HOST,
				'database'  => DB_NAME,
				'username'  => DB_USER,
				'password'  => DB_PASSWORD,
				'charset'   => defined( 'DB_CHARSET' ) ?
					DB_CHARSET :
					'utf8mb4',
				'collation' => defined( 'DB_COLLATE' ) ?
					DB_COLLATE :
					'utf8mb4_unicode_ci',
				'prefix'    => 'wp_',
			)
		);

		$database_manager->setAsGlobal();
		$database_manager->bootEloquent();
	}
}
