<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentBadgesTable extends Migration
{
    public function up()
    {
        Schema::create('student_badges', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('badge_type'); // e.g., 'bronze', 'silver', 'gold', 'practice_star', 'time_master', 'milestone_10', 'streak_3'
            $table->string('badge_name'); // Display name, e.g., 'Bronze Badge', 'Practice Star'
            $table->text('description')->nullable(); // e.g., 'Scored 60–79% on an evaluation quiz'
            $table->timestamp('awarded_at');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_badges');
    }
}