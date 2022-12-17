<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up()
	{
		\DB::statement('CREATE TABLE `tur_vehicle_model` (
		  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) DEFAULT NULL,
		  `brand_id` bigint unsigned DEFAULT NULL,
		  `created_at` datetime DEFAULT NULL,
		  `updated_at` datetime DEFAULT NULL,
		  `deleted_at` datetime DEFAULT NULL,
		  PRIMARY KEY (`id`),
		  KEY `FK_tur_vehicle_model_tur_vehicle_brand` (`brand_id`),
		  CONSTRAINT `FK_tur_vehicle_model_tur_vehicle_brand` FOREIGN KEY (`brand_id`) REFERENCES `tur_vehicle_brand` (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci');
	}

	public function down()
	{
		Schema::dropIfExists('tur_vehicle_model');
	}
};
