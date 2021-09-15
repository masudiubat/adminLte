<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('moderator_id')->index();
            $table->unsignedBigInteger('organization_id')->index();
            $table->string('title')->nullable();
            $table->text('brief')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->tinyInteger('is_approved')->default('0');
            $table->text('executive_summary')->nullable();
            $table->string('target_url')->nullable();
            $table->tinyInteger('hide_from_client')->default('0');
            $table->timestamps();

            $table->foreign('moderator_id')->references('id')->on('users');
            $table->foreign('organization_id')->references('id')->on('organizations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
