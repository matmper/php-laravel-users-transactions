<?php

namespace App\Services;

use App\Interfaces\Transaction;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\DB;
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
        protected MessageService $messageService,
        protected TransactionRepository $transactionRepository
    ) {
        // usuário da sessão envia transação
        $this->payer = auth()->user();

        $this->transaction = null;
        $this->message = null;
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

        $this->getPayee($payeeId);

        $this->validateUser();
        $this->validateType();
        $this->validateBalance();

        return $this;
    }

    /**
     * Captura dados do usuário beneficiário (recebedor)
     *
     * @param string $payeeId uuid
     * @return void
     */
    private function getPayee(string $payeeId): void
    {
        $this->payee = $this->userService->getUserData($payeeId);
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
            throw new \Exception("insufficient ballance", 402);
        }
    }

    /**
     * Realiza o envio de mensagens ao finalizar transação
     *
     * @return self
     */
    public function transaction(): self
    {
        $this->authorizeCenterBank();
        $this->storeTransaction();
        $this->setTransaction();

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
            throw new \Exception('transaction not authorized into bank api', $response->getStatusCode());
        };
    }

    /**
     * Realiza a criação interna da transação, salvando dados no banco relacional
     *
     * @return void
     */
    private function storeTransaction(): void
    {
        DB::beginTransaction();

        try {
            $this->transaction = $this->transactionRepository->insert([
                'public_id' => \Illuminate\Support\Str::uuid()->toString(),
                'payer_id' => $this->payer->public_id,
                'payee_id' => $this->payee->public_id,
                'amount' => $this->amount,
            ]);

            if (empty($this->transaction->public_id)) {
                throw new \Exception('error to store transaction and create row', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $payerWallet = $this->walletService->insert([
                'amount' => -$this->amount,
                'user_id' => $this->payer->id,
                'name' => 'transação entre usuários',
                'transaction_id' => $this->transaction->public_id,
            ]);

            if (empty($payerWallet->id)) {
                throw new \Exception('error to store payer wallet', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $payeeWallet = $this->walletService->insert([
                'amount' => $this->amount,
                'user_id' => $this->payee->id,
                'name' => 'recebimento de transação',
                'transaction_id' => $this->transaction->public_id,
            ]);

            if (empty($payeeWallet->id)) {
                throw new \Exception('error to store payee wallet', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Define o que será considerado no retorno da variável $transaction
     *
     * @return void
     */
    private function setTransaction(): void
    {
        $this->transaction = (object) [
            'public_id' => $this->transaction->public_id,
            'amount' => $this->amount,
            'payer' => $this->payer->public_id,
            'payee' => $this->payee->public_id,
        ];
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
