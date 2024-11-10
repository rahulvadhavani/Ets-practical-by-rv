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
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->string('file_name', 100)->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_type', 100)->nullable();
            $table->unsignedBigInteger('uploadable_id');
            $table->string('uploadable_type')->nullable();
            $table->string('file_usage')->nullable()->default('other'); // Specifies whether it's 'profile', 'documents', 'other'
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            // Index for polymorphic fields
            $table->index(['uploadable_id', 'uploadable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploads');
    }
};
