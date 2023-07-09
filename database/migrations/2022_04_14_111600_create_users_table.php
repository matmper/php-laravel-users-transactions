<?php

use App\Enums\TypeEnum;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->default('UUID()')->unique()->comment('código externo da transação');
            $table->string('name', 75);
            $table->string('email')->unique();
            $table->string('document_number', 14)->unique()->comment('CPF ou CNPJ');
            $table->string('password');
            $table->enum('type', [TypeEnum::PESSOA_JURIDICA, TypeEnum::PESSOA_FISICA])
                ->default(TypeEnum::PESSOA_JURIDICA)
                ->comment('tipo de conta, usuário ou lojista');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes('deleted_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
