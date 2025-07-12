<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdjustQuizzesResultsSchema extends Migration
{
    public function up()
    {
        Schema::table('quizzes_results', function (Blueprint $table) {
            // Change score to integer
            $table->integer('score')->nullable()->change();
            // Keep time_taken as double, but ensure it's nullable
            $table->double('time_taken', 8, 2)->nullable()->change();
            // Change results to JSON
            $table->json('results')->nullable()->change();
            // Ensure user_grade is nullable
            $table->string('user_grade')->nullable()->change();
            // Ensure status is nullable
            $table->enum('status', ['passed', 'failed', 'waiting'])->nullable()->change();
            // Ensure created_at and updated_at have defaults
            $table->timestamp('created_at')->nullable()->useCurrent()->change();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate()->change();
        });
    }

    public function down()
    {
        Schema::table('quizzes_results', function (Blueprint $table) {
            $table->double('score', 8, 2)->nullable()->change();
            $table->double('time_taken', 8, 2)->nullable()->change();
            $table->text('results')->nullable()->change();
            $table->string('user_grade')->nullable(false)->change();
            $table->enum('status', ['passed', 'failed', 'waiting'])->nullable(false)->change();
            $table->timestamp('created_at')->nullable()->change();
            $table->timestamp('updated_at')->nullable()->change();
        });
    }
}