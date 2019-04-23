<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAjaxFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ajax_forms', function (Blueprint $table) {
            $table->increments('id');
            // Ключ формы.
            $table->string('name')->unique();
            // Заголовок формы.
            $table->string('title');
            // Сообщение при успешной отправке.
            $table->string('success_message')->nullable();
            // Сообщение при недуаче.
            $table->string('fail_message')->nullable();
            // Куда отправлять сообщение.
            $table->string('email')->nullable();
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
        Schema::dropIfExists('ajax_forms');
    }
}
