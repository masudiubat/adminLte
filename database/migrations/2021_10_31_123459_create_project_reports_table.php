<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('project_id')->index();
            $table->unsignedBigInteger('report_category_id')->index();
            $table->unsignedBigInteger('project_scope_id')->index();
            $table->string('title')->nullable();
            $table->string('vulnerability_location')->nullable();
            $table->text('description')->nullable();
            $table->text('step_to_reproduce')->nullable();
            $table->text('security_impact')->nullable();
            $table->text('recommended_fix')->nullable();
            $table->string('cvss')->nullable();
            $table->string('severity')->nullable();
            $table->tinyInteger('is_approved')->default('0');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('report_category_id')->references('id')->on('report_categories');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('project_scope_id')->references('id')->on('project_scopes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_reports');
    }
}
