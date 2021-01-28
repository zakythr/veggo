<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('id_user');
            $table->string('id_alamat');
            $table->string('id_kurir');
            $table->string('nomor_invoice');
            $table->integer('total_bayar');
            $table->integer('status');
            $table->string('bukti_transfer');
            $table->dateTime('tanggal_pre_order');
            $table->string('keterangan');
            $table->string('tipe_transaksi');
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
        Schema::dropIfExists('transaksis');
    }
}
