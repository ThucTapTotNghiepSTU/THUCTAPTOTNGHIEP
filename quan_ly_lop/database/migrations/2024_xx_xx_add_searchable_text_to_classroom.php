<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('classroom', function (Blueprint $table) {
            $table->text('searchable_text')->nullable();
            $table->fullText('searchable_text');
        });
    }

    public function down()
    {
        Schema::table('classroom', function (Blueprint $table) {
            $table->dropColumn('searchable_text');
        });
    }
}; 