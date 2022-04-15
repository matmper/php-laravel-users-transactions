<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DocumentNumber implements Rule
{
    private $documentNumberType;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $documentNumberType = 'both')
    {
        $this->documentNumberType = $documentNumberType;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $documentNumber = (string) preg_replace("/[^0-9]/", "", $value);

        if ($this->documentNumberType === 'cpf' || strlen($documentNumber) === 11) {
            return $this->validateCpf($documentNumber);
        }
        
        if ($this->documentNumberType === 'cnpj' || strlen($documentNumber) === 14) {
            return $this->validateCnpj($documentNumber);
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        switch ($this->documentNumberType) {
            case 'cpf':
                return ':attribute não é um documento tipo CPF válido';
                break;
            case 'cnpj':
                return ':attribute não é um documento tipo CNPJ válido';
                break;
            default:
                return ':attribute não é um documento válido';
                break;
        }
    }

    /**
     * Valida documento tipo CPF
     *
     * @param string $cpf
     * @return boolean
     */
    private function validateCpf(string $cpf): bool
    {
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    /**
     * Valida documento tipo CNPJ
     *
     * @param string $cnpj
     * @return boolean
     */
    private function validateCnpj(string $cnpj): bool
    {
        $soma = 0;

        $soma += ($cnpj[0] * 5);
        $soma += ($cnpj[1] * 4);
        $soma += ($cnpj[2] * 3);
        $soma += ($cnpj[3] * 2);
        $soma += ($cnpj[4] * 9);
        $soma += ($cnpj[5] * 8);
        $soma += ($cnpj[6] * 7);
        $soma += ($cnpj[7] * 6);
        $soma += ($cnpj[8] * 5);
        $soma += ($cnpj[9] * 4);
        $soma += ($cnpj[10] * 3);
        $soma += ($cnpj[11] * 2);

        $d1 = $soma % 11;
        $d1 = $d1 < 2 ? 0 : 11 - $d1;

        $soma = 0;
        $soma += ($cnpj[0] * 6);
        $soma += ($cnpj[1] * 5);
        $soma += ($cnpj[2] * 4);
        $soma += ($cnpj[3] * 3);
        $soma += ($cnpj[4] * 2);
        $soma += ($cnpj[5] * 9);
        $soma += ($cnpj[6] * 8);
        $soma += ($cnpj[7] * 7);
        $soma += ($cnpj[8] * 6);
        $soma += ($cnpj[9] * 5);
        $soma += ($cnpj[10] * 4);
        $soma += ($cnpj[11] * 3);
        $soma += ($cnpj[12] * 2);

        $d2 = $soma % 11;
        $d2 = $d2 < 2 ? 0 : 11 - $d2;

        return $cnpj[12] == $d1 && $cnpj[13] == $d2;
    }
}
