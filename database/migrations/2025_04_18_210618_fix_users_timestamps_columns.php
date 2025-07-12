<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixUsersTimestampsColumns extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Modifier les colonnes pour qu'elles soient de type TIMESTAMP
            $table->timestamp('created_at')->nullable()->change();
            $table->timestamp('updated_at')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Revenir en arrière si nécessaire
            $table->integer('created_at')->nullable()->change();
            $table->integer('updated_at')->nullable()->change();

        });
    }
}