<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('apps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('os_id');
            $table->foreign('os_id')->references('id')->on('oses');
            $table->string('username');
            $table->string('password');
            $table->unique(['id', 'os_id']);
        });

        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('u_id');
            $table->unsignedBigInteger('os_id');
            $table->foreign('os_id')->references('id')->on('oses');
            $table->string('language', 2);
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id');
            $table->foreign('device_id')->references('id')->on('devices');
            $table->unsignedBigInteger('app_id');
            $table->foreign('app_id')->references('id')->on('apps');
            $table->string('token', 64)->nullable();
            $table->string('receipt')->nullable();
            $table->enum('status', ['pending', 'active', 'expired', 'cancelled'])->default('pending');
            $table->timestamp('expires_at')->nullable();
            $table->unique(['device_id', 'app_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('devices');
        Schema::dropIfExists('apps');
        Schema::dropIfExists('oses');
    }
}
