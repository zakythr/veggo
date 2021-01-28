<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('id_user');
            $table->string('id_kategori');
            $table->string('nama');
            $table->string('kode');
            $table->string('jenis');
            $table->string('satuan');
            $table->integer('bobot');
            $table->integer('harga_beli');
            $table->integer('harga_jual');
            $table->string('deskripsi');
            $table->integer('diskon');
            $table->integer('jenis_diskon');
            $table->integer('show_etalase');
            $table->integer('is_paket');
            $table->integer('ketersediaan');
            $table->integer('stok');
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
        Schema::dropIfExists('barangs');
    }
}
