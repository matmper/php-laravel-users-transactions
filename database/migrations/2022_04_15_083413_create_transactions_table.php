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
            $table->uuid('payer_id')->comment('usuário que enviou transação users.public_id');
            $table->uuid('payee_id')->comment('usuário que recebeu transação users.public_id');
            $table->bigInteger('amount')->comment('valor em centavos da transação');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes('deleted_at', 0);
            $table->foreign('payer_id')->references('public_id')->on('users');
            $table->foreign('payee_id')->references('public_id')->on('users');
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
