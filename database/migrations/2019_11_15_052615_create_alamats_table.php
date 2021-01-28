<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlamatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alamats', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('id_user');
            $table->string('negara');
            $table->string('kotkab');
            $table->string('daerah');
            $table->string('kelurahan');
            $table->string('alamat');
            $table->string('kodepos');
            $table->string('long');
            $table->string('lat');
            $table->integer('jarak');
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
        Schema::dropIfExists('alamats');
    }
}
