<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusUsersObsToCartonitemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carton_item', function (Blueprint $table) {
            $table->string('audit_user')->nullable();
            $table->string('observations')->nullable();
            $table->string('audit_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cartonitem', function (Blueprint $table) {
            $table->dropColumn('audit_user');
            $table->dropColumn('observations');
            $table->dropColumn('audit_status');
        });
    }
}
