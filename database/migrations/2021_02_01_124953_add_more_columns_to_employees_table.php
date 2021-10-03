<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumnsToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('city')->after('phone')->nullable();
            $table->string('state')->after('city')->nullable();
            $table->text('address')->after('state')->nullable();
            $table->string('role')->after('recipient_code')->nullable();
            $table->timestamp('date_joined')->after('role')->nullable();
            $table->timestamp('pay_day')->after('salary')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {

            $table->dropColumn(['city', 'state', 'address', 'role', 'pay_day', 'date_joined']);

        });
    }
}
