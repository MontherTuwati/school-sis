<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
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
            $table->string('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('username')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('join_date')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('status')->nullable();
            $table->string('avatar')->nullable();
            $table->string('position')->nullable();
            $table->foreignId('department_id')->nullable()->constrained();
            $table->string('role')->nullable();
            /* Users: 0=>Student, 1=> Teacher, 2=>Manager, 3=>Admin, 4=>Master(Super Admin) */
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
