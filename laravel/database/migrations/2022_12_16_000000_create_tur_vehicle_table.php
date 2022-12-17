<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up()
	{
		\DB::statement('CREATE TABLE `tur_vehicle` (
		  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) DEFAULT NULL,
		  `type_id` bigint unsigned DEFAULT NULL,
		  `brand_id` bigint unsigned DEFAULT NULL,
		  `model_id` bigint unsigned DEFAULT NULL,
		  `created_at` datetime DEFAULT NULL,
		  `updated_at` datetime DEFAULT NULL,
		  `deleted_at` datetime DEFAULT NULL,
		  PRIMARY KEY (`id`),
		  KEY `FK_tur_vehicle_tur_vehicle_type` (`type_id`),
		  KEY `FK_tur_vehicle_tur_vehicle_brand` (`brand_id`),
		  KEY `FK_tur_vehicle_tur_vehicle_model` (`model_id`),
		  CONSTRAINT `FK_tur_vehicle_tur_vehicle_brand` FOREIGN KEY (`brand_id`) REFERENCES `tur_vehicle_brand` (`id`),
		  CONSTRAINT `FK_tur_vehicle_tur_vehicle_model` FOREIGN KEY (`model_id`) REFERENCES `tur_vehicle_model` (`id`),
		  CONSTRAINT `FK_tur_vehicle_tur_vehicle_type` FOREIGN KEY (`type_id`) REFERENCES `tur_vehicle_type` (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci');
	}

	public function down()
	{
		Schema::dropIfExists('tur_vehicle');
	}
};
