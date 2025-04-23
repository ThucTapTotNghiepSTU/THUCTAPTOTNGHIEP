<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait HandlesSqliteMigrations
{
    protected function setUp(): void
    {
        parent::setUp();

        // Skip fulltext index creation for SQLite
        if (DB::connection()->getDriverName() === 'sqlite') {
            $this->beforeApplicationDestroyed(function () {
                Schema::dropIfExists('classroom');
                Schema::create('classroom', function ($table) {
                    $table->char('class_id', 36)->primary();
                    $table->string('class_code')->unique();
                    $table->char('course_id', 36);
                    $table->string('semester');
                    $table->integer('year');
                    $table->enum('status', ['Active', 'Completed', 'Cancelled'])->default('Active');
                    $table->timestamp('created_at')->nullable();
                    $table->timestamp('updated_at')->nullable();
                    $table->text('searchable_text')->nullable();

                    $table->foreign('course_id')->references('course_id')->on('course');
                });
            });
        }
    }
}
