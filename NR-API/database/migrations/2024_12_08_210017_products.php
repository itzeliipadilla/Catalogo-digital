<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products',function (Blueprint $table) {
            $table->id();
            $table->string('name',40);
            $table->string('unit',20);
            $table->text('description');
            $table->string('dimension',30);
            $table->boolean('active');
            $table->string('image');
            $table->unsignedBigInteger('id_family');
            $table->foreign('id_family')->references('id')->on('families');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
