<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// @codingStandardsIgnoreLine
class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title', 500);
            $table->date('start_date');
            $table->date('end_date');
            $table->date('deadline_manager_assign');
            $table->date('deadline_manager_approve');
            $table->date('deadline_assessor_complete');
            $table->date('deadline_emp_complete');
            $table->string('created_by', 100);
            $table->string('modified_by', 100);
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
        Schema::dropIfExists('campaigns');
    }
}
