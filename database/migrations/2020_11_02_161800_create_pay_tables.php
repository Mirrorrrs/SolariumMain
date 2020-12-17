<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->comment('ID ОО')->index();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->string('name')->comment('Название начисления');
            $table->string('description')->comment('Краткое описание');
            $table->integer('points')->comment('Количество баллов');
            $table->timestamps();
        });
        Schema::create('pay_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->comment('Истец')->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreignId('reward_id')->comment('Название вознаграждения');
            $table->foreign('reward_id')->references('id')->on('pay_rewards');
            $table->string('comment')->nullable()->comment('Комментарий к запросу');
            $table->timestamps();
        });
        Schema::create('pay_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->comment('ID пользователя')->index();
            $table->foreignId('executor_id')->nullable()->comment('ID исполнителя')->index();
            $table->string('executor')->nullable()->comment('Перекрывающий исполнитель');
            $table->foreignId('reward_id')->nullable()->comment('ID начисления');
            $table->string('reward')->nullable()->comment('Перекрывающее наименование');
            $table->string('description')->nullable()->comment('Перекрывающее описание');
            $table->integer('points')->comment('Перекрывающее количество баллов');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reward_id')->references('id')->on('pay_rewards')->onDelete('set null');
            $table->foreign('executor_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
        Schema::create('pay_users', function (Blueprint $table) {
            $table->foreignId('id')->comment('ID основного пользователя')->primary();
            $table->foreign('id')->references('id')->on('users')->onDelete('cascade');
            $table->string('title')->nullable()->comment('Перекрывает отображение основной должности');
            $table->string('group')->nullable()->comment('Группа');
            $table->string('subgroup')->nullable()->comment('Саб-группа');
            $table->integer('points')->default(0)->comment('Количество баллов');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pay_users');
        Schema::dropIfExists('pay_transactions');
        Schema::dropIfExists('pay_requests');
        Schema::dropIfExists('pay_rewards');
    }
}
