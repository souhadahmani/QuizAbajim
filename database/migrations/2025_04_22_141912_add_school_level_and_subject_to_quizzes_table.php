<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSchoolLevelAndSubjectToQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            // Add school_level_id column with foreign key to school_levels table
            $table->unsignedBigInteger('school_level_id')->nullable()->after('type');
            $table->foreign('school_level_id')
                  ->references('id')
                  ->on('school_levels')
                  ->onDelete('set null');

            // Add subject_id column with foreign key to materials table
            $table->unsignedBigInteger('subject_id')->nullable()->after('school_level_id');
            $table->foreign('subject_id')
                  ->references('id')
                  ->on('materials')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['school_level_id']);
            $table->dropForeign(['subject_id']);

            // Drop columns
            $table->dropColumn('school_level_id');
            $table->dropColumn('subject_id');
        });
    }
}