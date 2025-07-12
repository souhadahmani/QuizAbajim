<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTimestampsInQuizzesResultsTable extends Migration
{
    public function up()
    {
        Schema::table('quizzes_results', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('quizzes_results', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable(false)->change();
        });
    }
}