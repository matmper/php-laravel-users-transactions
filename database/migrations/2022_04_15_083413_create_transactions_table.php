<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->default('UUID()')->unique()->comment('código externo da transação');
            $table->bigInteger('user_id')->unsigned()->comment('usuário que enviou transação');
            $table->bigInteger('user_id_to')->unsigned()->comment('usuário que recebeu transação');
            $table->bigInteger('amount')->comment('valor em centavos da transação');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes('deleted_at', 0);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('user_id_to')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
