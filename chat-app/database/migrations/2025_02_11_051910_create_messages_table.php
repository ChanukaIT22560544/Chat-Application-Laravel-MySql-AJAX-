<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // sender's id
            $table->string('username');            // sender's username
            $table->text('message');               // message text
            $table->timestamps();                  // created_at will serve as the sending time
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
