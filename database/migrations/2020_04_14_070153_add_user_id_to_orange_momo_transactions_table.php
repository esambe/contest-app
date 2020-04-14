<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToOrangeMomoTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orange_momo_transactions', function (Blueprint $table) {
            $table->bigInteger('user_id')->after('contestant_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orange_momo_transactions', function (Blueprint $table) {
            $table->dropColumn(['user_id']);
        });
    }
}
