<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearcherCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('researcher_certifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('researcher_application_id')->index()->nullable();
            $table->string('name');
            $table->timestamps();

            $table->foreign('researcher_application_id')->references('id')->on('researcher_applications');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('researcher_certifications');
    }
}
