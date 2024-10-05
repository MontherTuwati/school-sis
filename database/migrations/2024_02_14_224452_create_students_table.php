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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_number')->nullable();
            $table->string('semester')->nullable();
            $table->string('gpa')->nullable();
            $table->string('total_percentage')->nullable();
            $table->string('nation_id')->nullable();
            $table->foreignId('department_id')->nullable();
            $table->foreignId('subject_id')->nullable();
            $table->string('upload')->nullable();
            $table->string('address')->nullable();
            $table->string('graduated')->default(0);
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
        Schema::dropIfExists('students');
    }
};
