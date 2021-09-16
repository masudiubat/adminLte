<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationMemberProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_member_project', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_member_id')->index();
            $table->unsignedBigInteger('project_id')->index();
            $table->timestamps();

            $table->foreign('organization_member_id')->references('id')->on('organization_members');
            $table->foreign('project_id')->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organization_member_project');
    }
}
