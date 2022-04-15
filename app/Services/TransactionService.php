<?php

namespace App\Services;

use App\Interfaces\Transaction;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class TransactionService implements Transaction
{
    /**
     * Usuário que irá receber a transação
     *
     * @var object
     */
    private $payee;

    /**
     * Dados do usuário que receberá a transação
     * 
     * @var object
     */
    private $payer;

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
    
    /**
     * Public construct
     *
     * @param WalletService $walletService
     * @param UserService $userService
     */
    public function __construct(
        protected WalletService $walletService,
        protected UserService $userService,
        protected MessageService $messageService
    ) {
        // usuário da sessão envia transação
        $this->payer = auth()->user();
    }

    /**
     * Realiza todo o serviço e validações de uma transação
     *
     * @param string $payeeId
     * @return self
     */
    public function handler(string $payeeId, int $amount): self
    {
        $this->amount = $amount;

        $this->payee = $this->getPayee($payeeId);

        $this->validateUser();
        $this->validateType();
        $this->validateBalance();

        return $this;
    }

    /**
     * Captura dados do usuário beneficiário (recebedor)
     *
     * @param string $payeeId
     * @return object
     */
    private function getPayee(string $payeeId): object
    {
        return $this->userService->getUserData($payeeId);
    }

    /**
     * Valida usuário para qual está enviando a transação
     *
     * @return void
     */
    private function validateUser(): void
    {
        Gate::check('user-is-different', [$this->payee->public_id]);
    }

    /**
     * Realiza validação do tipo de usuário na sessão (pf ou  pj)
     *
     * @return void
     */
    private function validateType(): void
    {
        Gate::check('send-transaction');
    }

    /**
     * Realiza validação de saldo do usuário
     *
     * @return void
     */
    private function validateBalance(): void
    {
        $this->balance = $this->walletService->getBalance($this->payer->id);

        if ($this->balance < $this->amount) {
            throw new \Exception("saldo insuficiente", 402);
        }
    }

    /**
     * Realiza o envio de mensagens ao finalizar transação
     *
     * @return self
     */
    public function transaction(): self
    {
        $this->transaction = (object) [
            'public_id' => 'teste'
        ];

        $this->authorizeCenterBank();

        return $this;
    }

    /**
     * Realiza vadalição da transação no Center Bank
     *
     * @return void
     */
    private function authorizeCenterBank(): void
    {
        $client = new \GuzzleHttp\Client([
            'verify' => config('app.env') !== 'local',
        ]);

        $response = $client->get('https://run.mocky.io/v3/' . config('keys.center_bank'));

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new \Exception('transação não autorizada pelo center bank', $response->getStatusCode());
        };
    }

    /**
     * Realiza o envio de mensagens ao finalizar transação
     *
     * @return self
     */
    public function message(): self
    {
        $this->message = $this->messageService->send($this->transaction->public_id);

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
