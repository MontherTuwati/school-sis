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
        Schema::create('graduates', function (Blueprint $table) {
            $table->id();
            $table->string('graduate_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('semester')->nullable();
            $table->string('class')->nullable();
            $table->string('gpa')->nullable();
            $table->foreignId('department_id')->nullable()->constrained();
            $table->string('nation_id')->nullable();
            $table->string('has graduated')->default(0);
            /* 0=>Not Graduated 1=>Graduated */
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
        Schema::dropIfExists('graduates');
    }
};
