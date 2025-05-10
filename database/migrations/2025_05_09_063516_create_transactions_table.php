<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('profile_id')->nullable();
            $table->bigInteger('dokter_profiles')->nullable();
            $table->bigInteger('reservation_id')->nullable();
            $table->bigInteger('total_transaction')->nullable();
            $table->string('status_transaction')->nullable();
            $table->string('payment_type')->nullable();
            $table->text('order_id')->nullable();
            $table->text('token_payment')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
