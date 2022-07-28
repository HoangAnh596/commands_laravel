<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// @codingStandardsIgnoreLine
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('username', 255);
            $table->string('email', 255);
            $table->string('firstname', 255)->nullable();
            $table->string('lastname', 255)->nullable();
            $table->foreignId('department_id')->nullable();
            $table->string('employee_code', 150)->nullable();
            $table->string('job_position', 150)->nullable();
            $table->string('job_rank', 150)->nullable();
            $table->string('contract_type', 150)->nullable();
            $table->date('join_date')->nullable();
            $table->timestamps();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
