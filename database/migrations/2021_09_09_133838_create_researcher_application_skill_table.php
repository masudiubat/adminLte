<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearcherApplicationSkillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('researcher_application_skill', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('researcher_application_id')->index();
            $table->unsignedBigInteger('skill_id')->index();
            $table->timestamps();

            $table->foreign('researcher_application_id')->references('id')->on('researcher_applications');
            $table->foreign('skill_id')->references('id')->on('skills');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('researcher_application_skill');
    }
}
