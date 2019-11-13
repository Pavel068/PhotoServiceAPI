<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shares', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('photo_id');
            $table->bigInteger('user_from');
            $table->bigInteger('user_to');
            $table->timestamps();
        });

        Schema::table('shares', function (Blueprint $table) {
            $table->unique(['photo_id', 'user_from', 'user_to']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shares');
    }
}
