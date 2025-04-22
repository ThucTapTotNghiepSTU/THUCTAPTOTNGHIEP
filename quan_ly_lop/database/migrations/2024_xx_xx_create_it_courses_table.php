<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('it_courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description');
            $table->string('image_url');
            $table->string('category'); // Web, Mobile, AI, Network, etc.
            $table->integer('credits');
            $table->text('learning_outcomes');
            $table->text('prerequisites')->nullable();
            $table->text('recommended_books')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('it_courses');
    }
}; 