<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('import_tables', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('id_temp');
            $table->integer('id_fila')->unsigned();
            $table->tinyInteger('cod_reg')->unsigned();
            $table->string('cod_mod');
            $table->string('dni');
            $table->string('ape_p');
            $table->string('ape_m');
            $table->string('nombres');
            $table->string('email');
            $table->string('telefono1');
            $table->string('telefono2');
            $table->string('state');
            $table->tinyInteger('created_by')->unsigned();
            $table->timestamp('created_at');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
