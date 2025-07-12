<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Questions;

class AddOrderToQuestionsTable extends Migration
{
    public function up()
    {
        // Add the order column
        Schema::table('quizzes_questions', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('video');
        });

        // Update existing questions with an order value based on their ID
        $questions = Questions::all();
        foreach ($questions as $index => $question) {
            $question->order = $index + 1;
            $question->save();
        }
    }

    public function down()
    {
        Schema::table('quizzes_questions', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}