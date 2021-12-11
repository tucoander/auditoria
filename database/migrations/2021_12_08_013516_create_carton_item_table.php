<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartonItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carton_item', function (Blueprint $table) {
            $table->foreignId('carton_id')->constrained()->references('id')->on('carton');
            $table->foreignId('product_id')->constrained()->references('id')->on('product');
            $table->integer('packed_quantity');
            $table->integer('audit_quantity');
            $table->integer('remaining_quantity');
            $table->integer('exceed_quantity');
            $table->integer('damaged_quantity');
            $table->boolean('items_status');
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
        Schema::dropIfExists('carton_item');
    }
}
