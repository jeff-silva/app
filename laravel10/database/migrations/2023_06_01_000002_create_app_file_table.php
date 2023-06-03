<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('app_file', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('size')->length(11)->nullable();
            $table->string('mime', 20)->length(100)->nullable();
            $table->string('ext', 10)->length(5)->nullable();
            $table->string('folder')->nullable();
            $table->binary('content')->nullable();
            $table->timestamps();
        });

        \DB::statement('ALTER TABLE app_file CHANGE content content LONGBLOB');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_file');
    }
};
