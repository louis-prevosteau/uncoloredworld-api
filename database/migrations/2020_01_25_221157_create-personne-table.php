<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonneTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('personnes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 50)->nullable(false);
            $table->string('prenom', 50)->nullable(false);
            $table->string('pseudo', 50)->default('nom')->nullable(true);
            $table->boolean('actif')->default(true)->nullable(false);
            $table->string('avatar')->nullable(true);
            //stats
            /*$table->bigInteger('score')->default(0)->nullable(false);
            $table->bigInteger('high_score')->default(0)->nullable(false);
            $table->bigInteger('tirs')->default(0)->nullable(false);
            $table->bigInteger('ennemis_tues')->default(0)->nullable(false);
            $table->bigInteger('morts')->default(0)->nullable(false);
            $table->bigInteger('bonus')->default(0)->nullable(false);
            $table->bigInteger('malus')->default(0)->nullable(false);*/
            //
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('statistique_id')->unsigned()->nullable();
            $table->foreign('statistique_id')->references('id')->on('statistiques')->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('PersonneResource');
    }
}
