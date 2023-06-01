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
        Schema::create('app_mail', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email_to');
            $table->string('subject');
            $table->text('content');
            $table->integer('send_attempt');
            $table->string('send_group');
            $table->integer('sent');
            $table->integer('read');
            $table->string('email_template');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_mail');
    }
};
