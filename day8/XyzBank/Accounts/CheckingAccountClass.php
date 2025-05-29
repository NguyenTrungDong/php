<?php 

namespace XyzBank\Accounts;

use InvalidArgumentException;

class CheckingAccountClass extends BankAccountAbstractClass
{
    use TransactionLoggerTrait;
    protected $accountNumber;
    public function deposit(float $amount)
    {
        if ($amount <=0)
        {
            throw new InvalidArgumentException("Số tiềng gửi phải lớn hơn 0");
        }
        $this->balance += $amount;
        $this->logTransaction("Gửi tiền", $amount, $this->balance);
    }

        public function withdraw(float $amount)
    {
        if ($amount <= 0)
        {
            throw new InvalidArgumentException("Số tiền rút phải lớn hơn 0");
        }
        if ($amount > $this->balance)
        {
            throw new \RuntimeException("Số dư tài khoản không đủ");
        }
        $this->balance -= $amount;
        $this->logTransaction("Rút tiền", $amount, $this->balance);
    }

    public function getAccountType(): string
    {
        return "Thanh toán"; // loại tài khoản
    }

    public function getAccountNumber()
    {
        return $this->accountNumber;
    }
}