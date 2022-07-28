<?php
// @codingStandardsIgnoreFile

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnsPlansTrainingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_plans', function (Blueprint $table) {
            $table->longText('emp_assignment')->change();
            $table->longText('emp_criterion')->change();
            $table->longText('emp_deadline')->change();
            $table->longText('emp_priority')->change();
        });

        Schema::table('employee_training', function (Blueprint $table) {
            $table->longText('emp_target_training')->change();
            $table->longText('emp_demand_training')->change();
            $table->longText('emp_content_training')->change();
            $table->longText('emp_format_training')->change();
            $table->longText('emp_time_training')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
