<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentReplyUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_reply_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comment_reply_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->tinyInteger('is_seen')->default('0');
            $table->timestamps();

            $table->foreign('comment_reply_id')->references('id')->on('comment_replies')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_reply_user');
    }
}
