<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrangeMomoTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orange_momo_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pay_token');
            $table->string('notif_token');
            $table->bigInteger('contest_id');
            $table->bigInteger('contestant_id');
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
        Schema::dropIfExists('orange_momo_transactions');
    }
}
