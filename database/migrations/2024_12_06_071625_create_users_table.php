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
            $table->string('number')->nullable()->unique(); 
            $table->string('name')->nullable(); 
            $table->string('email')->nullable()->unique(); 
            $table->string('age')->nullable(); 
            $table->string('gender')->nullable(); 
            $table->string('looking_for')->nullable(); 
            $table->string('interest')->nullable(); 
            $table->string('profile_image')->nullable();
            $table->string('state')->nullable(); 
            $table->string('city')->nullable(); 
            $table->string('status')->nullable(); 
            $table->string('password')->nullable(); 
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
