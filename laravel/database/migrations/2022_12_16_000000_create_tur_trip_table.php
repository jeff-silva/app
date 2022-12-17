<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up()
	{
		\DB::statement('CREATE TABLE `tur_trip` (
		  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) DEFAULT NULL,
		  `vehicle_id` bigint unsigned DEFAULT NULL,
		  `driver_id` bigint unsigned DEFAULT NULL,
		  `created_at` datetime DEFAULT NULL,
		  `updated_at` datetime DEFAULT NULL,
		  `deleted_at` datetime DEFAULT NULL,
		  PRIMARY KEY (`id`),
		  KEY `FK_tur_trip_tur_vehicle` (`vehicle_id`),
		  KEY `FK_tur_trip_tur_driver` (`driver_id`),
		  CONSTRAINT `FK_tur_trip_tur_driver` FOREIGN KEY (`driver_id`) REFERENCES `tur_driver` (`id`),
		  CONSTRAINT `FK_tur_trip_tur_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `tur_vehicle` (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci');
	}

	public function down()
	{
		Schema::dropIfExists('tur_trip');
	}
};
