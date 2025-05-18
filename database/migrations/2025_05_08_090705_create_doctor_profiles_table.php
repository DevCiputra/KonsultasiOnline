<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_profiles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_polyclinic_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('spesialis_name')->nullable();
            $table->text('gender')->nullable();
            $table->text('biografi')->nullable();
            $table->text('link_accuity')->nullable();
            $table->text('cv_dokter')->nullable();
            $table->bigInteger('payment_konsultasi')->nullable();
            $table->integer('payment_strike')->nullable();
            $table->enum('konsultasi', ['OPEN', 'CLOSE'])->default('CLOSE');
            $table->enum('reservasi', ['OPEN', 'CLOSE'])->default('CLOSE');
            $table->enum('status_dokter', ['AKTIF', 'SIBUK'])->default('SIBUK');
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
        Schema::dropIfExists('doctor_profiles');
    }
}
