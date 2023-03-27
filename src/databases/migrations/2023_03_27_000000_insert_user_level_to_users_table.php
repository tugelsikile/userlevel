<?php
//namespace Tugelsikile\UserLevel\databases\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertUserLevelToUsersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::table("users", function (Blueprint  $table) {
            $table->uuid("level")->nullable();
            $table->foreign("level")->on('user_levels')->references('id')->onDelete('set null')->onUpdate('cascade');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::table('users', function (Blueprint  $table) {
            $table->dropForeign("users_level_foreign");
            $table->dropColumn("level");
        });
    }
}
