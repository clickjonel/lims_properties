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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('property_no');
            $table->string('measurement_unit');
            $table->text('particulars');
            $table->decimal('unit_cost',20,10);
            $table->string('status');
            $table->text('remarks')->nullable();
            $table->string('category');
            $table->unsignedBigInteger('mr_to');
            $table->date('acquisition_date')->nullable();
            $table->text('specs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
