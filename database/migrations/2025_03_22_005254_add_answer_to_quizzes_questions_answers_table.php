<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnswerToQuizzesQuestionsAnswersTable extends Migration
{
    public function up()
    {
        Schema::table('quizzes_questions_answers', function (Blueprint $table) {
            // Add the 'answer' column after 'question_id'
            $table->text('answer')->after('question_id');
        });
    }

    public function down()
    {
        Schema::table('quizzes_questions_answers', function (Blueprint $table) {
            // Reverse the change: remove 'answer'
            $table->dropColumn('answer');
        });
    }
}