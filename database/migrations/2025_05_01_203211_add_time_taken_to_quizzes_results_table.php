<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimeTakenToQuizzesResultsTable extends Migration
{
    public function up()
    {
        Schema::table('quizzes_results', function (Blueprint $table) {
            $table->float('time_taken')->nullable()->after('score');
        });
    }

    public function down()
    {
        Schema::table('quizzes_results', function (Blueprint $table) {
            $table->dropColumn('time_taken');
        });
    }
}