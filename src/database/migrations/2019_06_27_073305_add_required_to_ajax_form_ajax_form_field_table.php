<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequiredToAjaxFormAjaxFormFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ajax_form_ajax_form_field', function (Blueprint $table) {
            $table->boolean('required')
                ->default(0)
                ->comment("Поле обязательно для заполнения");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ajax_form_ajax_form_field', function (Blueprint $table) {
            //
        });
    }
}
