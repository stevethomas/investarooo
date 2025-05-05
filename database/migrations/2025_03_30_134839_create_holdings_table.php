<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('holdings', function (Blueprint $table) {
            $table->id();
            $table->string('ticker');
            $table->unsignedInteger('units');
            $table->decimal('purchase', places: 3);
            $table->decimal('yield', places: 3)->nullable();
            $table->string('dividend_frequency')->nullable();
            $table->decimal('drp_weight', places: 3)->nullable();
            $table->string('registry')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
};
