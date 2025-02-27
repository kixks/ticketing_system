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
        Schema::create('qrlogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->string('security_officer_name');
    
            // Ensure the referenced table names match your actual database table names
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
    
            $table->timestamp('scanned_at');
            $table->enum('status', ['departed', 'arrived'])->default('departed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qrlogs');
    }
};
