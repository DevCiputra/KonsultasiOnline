<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            // INI PROFILE ID PASIEN
            $table->bigInteger('profile_id')->nullable();
            // INI PROFILE ID DOKTER
            $table->bigInteger('dokter_profile_id')->nullable();
            $table->string('tanggal_konsultasi')->nullable();
            $table->string('waktu_konsultasi')->nullable();
            $table->text('link_pertemuan')->nullable();
            $table->text('catatan_konsultasi')->nullable();
            $table->text('keluhan_utama')->nullable();
            $table->date('date_konsultasi_log')->nullable();

            // INI ADALAH STATUS APPROVE DARI DOKTER ('MENUNGGU', 'TERIMA', 'TOLAK')
            $table->string('status_approve')->nullable();
            $table->string('reservation_code')->nullable();
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
        Schema::dropIfExists('reservations');
    }
}
