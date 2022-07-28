<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// @codingStandardsIgnoreLine
class ModifyColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_training', function (Blueprint $table) {
            $table->string('emp_target_training', 1000)->nullable()->after('checkpoint_id');
        });

        Schema::table('employee_plans', function (Blueprint $table) {
            $table->string('emp_deadline', 1000)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('employee_training', 'emp_target_training')) {
            Schema::table('employee_training', function (Blueprint $table) {
                $table->dropColumn('emp_target_training');
            });
        }
    }
}
