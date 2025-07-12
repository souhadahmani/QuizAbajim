
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialQuizTable extends Migration
{
    public function up()
    {
        Schema::create('material_quiz', function (Blueprint $table) {
            $table->unsignedInteger('quiz_id'); // Changed to unsignedInteger (INT UNSIGNED)
            $table->unsignedInteger('material_id'); // Changed to unsignedInteger (INT UNSIGNED)
            $table->timestamps();

            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');

            $table->primary(['quiz_id', 'material_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('material_quiz');
    }
}