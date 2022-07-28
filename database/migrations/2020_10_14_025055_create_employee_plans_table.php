<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// @codingStandardsIgnoreLine
class CreateEmployeePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checkpoint_id');
            $table->string('emp_assignment', 1000)->nullable();
            $table->string('emp_criterion', 1000)->nullable();
            $table->string('emp_deadline')->nullable();
            $table->string('emp_priority')->nullable();
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
        Schema::dropIfExists('employee_plans');
    }
}
