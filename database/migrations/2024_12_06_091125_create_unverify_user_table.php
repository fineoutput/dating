<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnverifyUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unverify_user', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable(); 
            $table->string('number_verify')->nullable(); 
            $table->string('name')->nullable(); 
            $table->string('email')->nullable(); 
            $table->string('email_verify')->nullable(); 
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
        Schema::dropIfExists('unverify_user');
    }
}
