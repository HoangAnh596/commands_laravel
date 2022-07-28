<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// @codingStandardsIgnoreLine
class CreateCheckpointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkpoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id');
            $table->foreignId('emp_id');
            $table->foreignId('manager_id');
            $table->bigInteger('assessor_id')->nullable()->unsigned();
            $table->text('emp_assignment')->nullable();
            $table->text('emp_target')->nullable();
            $table->string('emp_result', 500)->nullable();
            $table->unsignedTinyInteger('emp_evaluate_process')->nullable();
            $table->unsignedTinyInteger('emp_evaluate_quality')->nullable();
            $table->unsignedTinyInteger('emp_evaluate_complex')->nullable();
            $table->unsignedTinyInteger('assessor_evaluate_process')->nullable();
            $table->unsignedTinyInteger('assessor_evaluate_quality')->nullable();
            $table->unsignedTinyInteger('assessor_evaluate_complex')->nullable();
            $table->unsignedTinyInteger('emp_evaluate_responsibility')->nullable();
            $table->unsignedTinyInteger('emp_evaluate_policy')->nullable();
            $table->unsignedTinyInteger('assessor_evaluate_responsibility')->nullable();
            $table->unsignedTinyInteger('assessor_evaluate_policy')->nullable();
            $table->unsignedTinyInteger('manager_evaluate_ability')->nullable();
            $table->unsignedTinyInteger('manager_evaluate_activity')->nullable();
            $table->text('emp_opinions')->nullable();
            $table->text('assessor_opinions')->nullable();
            $table->text('manager_opinions')->nullable();
            $table->float('emp_total_final', 8, 2)->nullable();
            $table->enum('status', [1, 2, 3, 4, 5, 6])->default(1);
            $table->text('note')->nullable();
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
        Schema::dropIfExists('checkpoints');
    }
}
