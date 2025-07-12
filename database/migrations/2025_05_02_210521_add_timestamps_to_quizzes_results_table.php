<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToQuizzesResultsTable extends Migration
{
    public function up()
    {
        Schema::table('quizzes_results', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down()
    {
        Schema::table('quizzes_results', function (Blueprint $table) {
            $table->dropColumn([ 'updated_at']);
        });
    }
}