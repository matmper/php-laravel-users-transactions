<?php

namespace App\Interfaces;

interface Transaction
{
    /**
     * Realiza todo o serviço e validações de uma transação
     *
     * @param integer $userId
     * @return self
     */
    public function handler(string $payeeId, int $amount): self;

    /**
     * Realiza o envio de mensagens ao finalizar transação
     *
     * @return self
     */
    public function transaction(): self;

    /**
     * Realiza o envio de mensagens ao finalizar transação
     *
     * @return self
     */
    public function message(): self;

    /**
     * Retorna o resultado em array
     *
     * @return object
     */
    public function toArray(): array;

    /**
     * Retorna o resultado em objeto
     *
     * @return object
     */
    public function toObject(): object;
}
