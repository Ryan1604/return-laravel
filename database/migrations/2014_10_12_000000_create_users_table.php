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
            $table->smallIncrements('id');
            $table->string('sn', 25)->unique();
            $table->string('name', 50);
            $table->string('phone_number', 25)->unique();
            $table->enum('gender', ['M' , 'F'])->default('M');
            $table->date('dob');
            $table->string('address');
            $table->string('username', 25)->unique();
            $table->string('email', 50)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('password_changed_at')->nullable();
            $table->string('password', 60);
            $table->string('profile_url', 50)->nullable();
            $table->rememberToken();
            $table->string('session_id', 40)->nullable();
            $table->unsignedTinyInteger('role_id');
            $table->unsignedTinyInteger('faculty_id')->nullable();
            $table->enum('disabled', [0, 1])->default(0);
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('faculty_id')->references('id')->on('faculties')->onDelete('set null');
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
