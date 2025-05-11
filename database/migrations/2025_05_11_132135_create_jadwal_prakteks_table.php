<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalPrakteksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwal_prakteks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dokter_profile_id')->nullable();
            $table->string('hari')->nullable();
            $table->string('jadwal_jam')->nullable();
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
        Schema::dropIfExists('jadwal_prakteks');
    }
}
