<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUserGradeInQuizzesResultsTable extends Migration
{
    public function up()
    {
        Schema::table('quizzes_results', function (Blueprint $table) {
            $table->string('user_grade')->change();
        });
    }

    public function down()
    {
        Schema::table('quizzes_results', function (Blueprint $table) {
            $table->integer('user_grade')->change();
        });
    }
}