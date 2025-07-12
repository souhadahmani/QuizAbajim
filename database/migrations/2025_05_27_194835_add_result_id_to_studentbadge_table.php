<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResultIdToStudentbadgeTable extends Migration
{
    public function up()
    {
        Schema::table('student_badges', function (Blueprint $table) {
            // Ajoute la colonne result_id comme clé étrangère
            $table->unsignedInteger('result_id')->nullable();
            $table->foreign('result_id')
                  ->references('id')
                  ->on('quizzes_results') // Nom de la table des résultats
                  ->onDelete('set null'); // Si le résultat est supprimé, mettre null
        });
    }

    public function down()
    {
        Schema::table('student_badges', function (Blueprint $table) {
            $table->dropForeign(['result_id']);
            $table->dropColumn('result_id');
        });
    }
}