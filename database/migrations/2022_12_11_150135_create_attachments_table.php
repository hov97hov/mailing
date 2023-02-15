<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('image_name')->nullable();
            $table->string('image_path')->nullable();
            $table->string('image_bottom_name')->nullable();
            $table->string('image_bottom_path')->nullable();
            $table->string('checked_image')->nullable();
            $table->string('checked_image_path')->nullable();
            $table->unsignedBigInteger('message_id');
            $table->foreign('message_id')
                ->references('id')
                ->on('messages')
                ->onDelete('cascade');
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
        Schema::dropIfExists('attachments');
    }
}
