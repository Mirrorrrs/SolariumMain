<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('namespace')->unique();
            $table->string('name');
            $table->timestamps();
        });

//        Users and user information

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('password');
            $table->foreignId('organization_id');
            $table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
            $table->integer('role')->nullable();
            $table->unsignedBigInteger("group_id")->nullable();
            $table->unsignedBigInteger("school_class_id")->nullable();
            $table->timestamps();
        });

        Schema::create('userinfo', function (Blueprint $table) {
            $table->foreignId('id')->primary();
            $table->foreign('id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('firstname')->nullable();
            $table->string('middlename')->nullable();
            $table->string('lastname')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('avatar')->nullable();
            $table->string('medical_policy')->nullable();
            $table->timestamps();
        });


        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('organization_id');
            $table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
            $table->string('human_name');
            $table->unsignedInteger("permissions")->nullable();
            $table->string('description')->nullable();
        });

        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->integer("group")->nullable();
            $table->integer("subgroup")->nullable();
            $table->unsignedBigInteger("class_teacher_id")->nullable();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('userinfo');
        Schema::dropIfExists('users');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('organizations');
        Schema::dropIfExists('school_classes');

    }
}
