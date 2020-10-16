<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImportTablePpff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('import_table_ppffs', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('id_temp');
            $table->integer('id_fila')->unsigned();
            $table->tinyInteger('cod_reg')->unsigned();
            $table->string('cod_mod');
            $table->string('tipo_doc');
            $table->string('dni');
            $table->string('ape_p');
            $table->string('ape_m');
            $table->string('nombres');
            $table->string('grado');
            $table->string('seccion');
            $table->string('ape_p_apo');
            $table->string('ape_m_apo');
            $table->string('nombres_apo');
            $table->string('telefono1');
            $table->string('telefono2');
            $table->string('state');
            $table->string('info_error');
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
