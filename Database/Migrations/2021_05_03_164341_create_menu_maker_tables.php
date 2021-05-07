<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuMakerTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_maker', function (Blueprint $table) {
            $table->increments('id');
            $table->string('machine_name')->unique();
            $table->string('display_name');
            $table->string('menu_class')->nullable();
            $table->string('description')->nullable();
            $table->string('lang')->nullable();

            $table->timestamps();
        });

        Schema::create('menu_maker_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_id')->unsigned();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->string('unique_name')->nullable();
            $table->integer('weight')->nullable();
            $table->string('menu_text')->nullable();
            $table->json('parameters');
            $table->json('meta_data')->nullable();


            $table->string('label');
            $table->string('link');
            // $table->integer('parent')->unsigned()->default(0);
            $table->integer('sort')->default(0);
            $table->string('class')->nullable();
            // $table->integer('menu')->unsigned();
            $table->integer('depth')->default(0);
            $table->integer('role_id')->default(0);

            $table->timestamps();
        });

        Schema::table('menu_maker_items', function ($table) {
            $table->index('parent_id', 'parent_id');
            $table->index(['unique_name','menu_id'], 'unique_name');
            $table->foreign('menu_id')
                ->references('id')->on('menu_maker')
                ->onDelete('cascade');
            $table->foreign('parent_id')
                ->references('id')->on('menu_maker_items')
                ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_maker_items');
        Schema::dropIfExists('menu_maker');
    }
}
