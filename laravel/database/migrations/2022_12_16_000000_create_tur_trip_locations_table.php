<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up()
	{
		\DB::statement('CREATE TABLE `tur_trip_locations` (
		  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) DEFAULT NULL,
		  `route` varchar(255) DEFAULT NULL,
		  `number` varchar(10) DEFAULT NULL,
		  `district` varchar(100) DEFAULT NULL,
		  `city` varchar(100) DEFAULT NULL,
		  `state` varchar(100) DEFAULT NULL,
		  `state_code` varchar(5) DEFAULT NULL,
		  `country` varchar(100) DEFAULT NULL,
		  `country_code` varchar(5) DEFAULT NULL,
		  `lat` decimal(10,8) DEFAULT NULL,
		  `lng` decimal(11,8) DEFAULT NULL,
		  `created_at` datetime DEFAULT NULL,
		  `updated_at` datetime DEFAULT NULL,
		  `deleted_at` datetime DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci');
	}

	public function down()
	{
		Schema::dropIfExists('tur_trip_locations');
	}
};
