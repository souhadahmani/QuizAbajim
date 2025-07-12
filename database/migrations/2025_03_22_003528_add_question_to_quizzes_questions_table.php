<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuestionToQuizzesQuestionsTable extends Migration
{
    public function up()
    {
        Schema::table('quizzes_questions', function (Blueprint $table) {
            $table->text('question')->nullable()->after('type');
        });
    }

    public function down()
    {
        Schema::table('quizzes_questions', function (Blueprint $table) {
            $table->dropColumn('question');
        });
    }
}