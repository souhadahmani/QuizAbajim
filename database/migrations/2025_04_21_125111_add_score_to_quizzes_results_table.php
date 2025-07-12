<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScoreToQuizzesResultsTable extends Migration
{
    public function up()
    {
        Schema::table('quizzes_results', function (Blueprint $table) {
            $table->float('score')->nullable()->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('quizzes_results', function (Blueprint $table) {
            $table->dropColumn('score');
        });
    }
}