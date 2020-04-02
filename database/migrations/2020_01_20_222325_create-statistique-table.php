<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatistiqueTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('statistiques', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('score')->default(0)->nullable(false);
            $table->bigInteger('high_score')->default(0)->nullable(false);
            $table->bigInteger('tirs')->default(0)->nullable(false);
            $table->bigInteger('ennemis_tues')->default(0)->nullable(false);
            $table->bigInteger('morts')->default(0)->nullable(false);
            $table->bigInteger('bonus')->default(0)->nullable(false);
            $table->bigInteger('malus')->default(0)->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('statistiques');
    }
}
