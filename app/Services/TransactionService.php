<?php

namespace App\Services;

use App\Exceptions\Transactions\BankAuthorizeException;
use App\Exceptions\Transactions\InsufficienteBalanceException;
use App\Interfaces\Transaction;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class TransactionService implements Transaction
{
    /**
     * Payee user collection
     *
     * @var User
     */
    private $payee;

    /**
     * Payer user collection
     *
     * @var User
     */
    private $payer;

    /**
     * Payer user balance (cents, 100 = R$1,00)
     *
     * @var integer
     */
    private $balance;

    /**
     * Transaction amount (cents, 100 = R$1,00)
     *
     * @var integer
     */
    private $amount;

    /**
     * Transaction stored data
     *
     * @var object
     */
    public $transaction;

    /**
     * Message store data (push)
     *
     * @var object
     */
    public $message;
    
    public function __construct(
        protected WalletService $walletService,
        protected UserService $userService,
        protected MessageService $messageService,
        protected TransactionRepository $transactionRepository
    ) {
        $this->payer = auth()->user();

        $this->transaction = null;
        $this->message = null;
    }

    /**
     * Init a new transaction
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
     * Get payer user data and set into $this->payee
     *
     * @param string $payeeId uuid
     * @return void
     */
    private function getPayee(string $payeeId): void
    {
        $this->payee = $this->userService->getUserData($payeeId);
    }

    /**
     * Validate if payer is different than payee
     *
     * @return void
     */
    private function validateUser(): void
    {
        Gate::check('user-is-different', [$this->payee->public_id]);
    }

    /**
     * Check if user type can create a new transaction
     *
     * @return void
     */
    private function validateType(): void
    {
        Gate::check('send-transaction');
    }

    /**
     * Validate payer user balance
     *
     * @return void
     */
    private function validateBalance(): void
    {
        $this->balance = $this->walletService->getBalance($this->payer->id);

        if ($this->balance < $this->amount) {
            throw new InsufficienteBalanceException;
        }
    }

    /**
     * Process and store transaction
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
     * Authorize transaction request into main bank api
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
            throw new BankAuthorizeException($response->getStatusCode());
        };
    }

    /**
     * Create a new transaction into database
     *
     * @return void
     */
    private function storeTransaction(): void
    {
        DB::beginTransaction();

        try {
            $this->transaction = $this->transactionRepository->create([
                'public_id' => \Illuminate\Support\Str::uuid()->toString(),
                'payer_id' => $this->payer->public_id,
                'payee_id' => $this->payee->public_id,
                'amount' => $this->amount,
            ]);

            if (empty($this->transaction->public_id)) {
                throw new \Exception('error to store transaction and create row', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $payerWallet = $this->walletService->create([
                'amount' => -$this->amount,
                'user_id' => $this->payer->id,
                'name' => 'transação entre usuários',
                'transaction_id' => $this->transaction->public_id,
            ]);

            if (empty($payerWallet->id)) {
                throw new \Exception('error to store payer wallet', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $payeeWallet = $this->walletService->create([
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
     * Create transaction response data
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
     * Create a new notification message
     *
     * @return self
     */
    public function message(): self
    {
        $this->message = $this->messageService->send($this->transaction->public_id);

        return $this;
    }

    /**
     * Array response result
     *
     * @return object
     */
    public function toArray(): array
    {
        return json_decode(json_encode($this), true);
    }

    /**
     * Object response result
     *
     * @return object
     */
    public function toObject(): object
    {
        return json_decode(json_encode($this), false);
    }
}
