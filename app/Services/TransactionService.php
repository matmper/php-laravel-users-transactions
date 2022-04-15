<?php

namespace App\Services;

class TransactionService
{
    /**
     * Usuário da sessão, realizou requisição
     *
     * @var integer
     */
    private $id;

    /**
     * Usuário que irá receber a transação
     *
     * @var integer
     */
    private $userId;

    /**
     * Retorna em centavos o saldo inicial do usuário
     *
     * @var integer
     */
    private $balance;

    /**
     * Retorna o valor da transação em centavos (100 = R$1,00)
     *
     * @var integer
     */
    private $amount;

    /**
     * Retorna dados da transação realizada
     *
     * @var object
     */
    public $transaction;

    /**
     * Retorna dados da mensagem enviada (push)
     *
     * @var object
     */
    public $message;
    
    public function __construct(protected WalletService $walletService)
    {
        
    }

    /**
     * Realiza todo o serviço e validações de uma transação
     *
     * @param integer $userId
     * @return integer
     */
    public function init(int $userId, int $amount): self
    {
        // usuário da sessão envia transação
        $this->id = auth()->user()->id;

        $this->userId = $userId;
        $this->amount = $amount;

        $this->validateBalance();
        $this->validateType();

        return $this;
    }

    /**
     * Realiza validação de saldo do usuário
     *
     * @return void
     */
    private function validateBalance(): void
    {
        $this->balance = $this->walletService->getBalance($this->id);

        if ($this->balance < $this->amount) {
            throw new \Exception("saldo insuficiente", 402);
        }

        return;
    }

    /**
     * Realiza validação do tipo de usuário na sessão (pf ou  pj)
     *
     * @return void
     */
    private function validateType(): void
    {
        if (auth()->user()->type !== 'pf') {
            throw new \Exception("usuários tipo lojistas não podem realizar transações", 403);
        }

        return;
    }

    /**
     * Realiza o envio de mensagens ao finalizar transação
     *
     * @return object
     */
    public function transaction(): self
    {
        $this->transaction = 1;

        //https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6

        return $this;
    }

    /**
     * Realiza o envio de mensagens ao finalizar transação
     *
     * @return object
     */
    public function message(): self
    {
        $this->message = 2;

        //http://o4d9z.mocklab.io/notify

        return $this;
    }

    /**
     * Retorna o resultado em array
     *
     * @return object
     */
    public function toArray(): array
    {
        return json_decode(json_encode($this), true);
    }

    /**
     * Retorna o resultado em objeto
     *
     * @return object
     */
    public function toObject(): object
    {
        return json_decode(json_encode($this), false);
    }
}
