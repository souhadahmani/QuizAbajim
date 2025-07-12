<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExplanationToQuestionsTable extends Migration
{
    public function up()
    {
        Schema::table('quizzes_questions', function (Blueprint $table) {
            $table->text('explanation')->nullable()->after('video');
        });
    }

    public function down()
    {
        Schema::table('quizzes_questions', function (Blueprint $table) {
            $table->dropColumn('explanation');
        });
    }
}