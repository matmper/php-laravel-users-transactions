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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->default('UUID()')->unique()->comment('código externo do usuário');
            $table->string('name', 75);
            $table->string('email', 150)->unique();
            $table->string('document_number', 14)->unique()->comment('CPF ou CNPJ');
            $table->string('password', 250);
            $table->enum('type', ['normal', 'store'])->default('normal')->comment('tipo de conta, usuário ou lojista');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes('deleted_at', 0);
        });

        Artisan::call('db:seed', [
            '--class' => 'UserSeeder',
            '--force' => true
        ]);
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
