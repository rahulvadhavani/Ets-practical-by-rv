<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('name')->nullable();
            $table->string('email');
            $table->string('first_name', 50);
            $table->string('last_name', 50)->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->string('postcode', 10)->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->json('hobbies')->nullable();
            $table->boolean('is_super_admin')->default(0)->comment('0 = user, 1 = admin');
            $table->tinyInteger('status')->default(1)->comment('0 = inactive, 1 = active');
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->foreignId('state_id')->nullable()->constrained('states')->onDelete('set null');
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
