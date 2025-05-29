<?php 
namespace XyzBank\Accounts;

class SavingAccountClass extends BankAccountAbstractClass implements InterestBearingInterface
{   
    use TransactionLoggerTrait;
    private const INTEREST_RATE = 5/100; // 5% / 1 năm
    private const MINIMUM_BALANCE = 1000000; // 1 Triệu
    protected $accountNumber; // Số tài khoản

    public function deposit($amount)
    {
        if($amount <0)
        {
            throw new \InvalidArgumentException("Số tiền gửi phải lớn hơn 0");  
        }
        $this->balance += $amount; // cộng tiền gửi
        $this->logTransaction("Gửi tiền", $amount, $this->balance); // ghi ra biên lai giao dịch
    }

        public function withdraw(float $amount) // Tiền rút
    {
        if ($amount <= 0)
        {
            throw new \InvalidArgumentException("Số tiền rút phải lớn hơn 0");

        }
        $newBalance = $this->balance - $amount; // trừ tài khoản
        if ($newBalance < self::MINIMUM_BALANCE)
        {
            throw new \RuntimeException("Số dư rút phải >= 1000.000 VNĐ"); 

        }
        $this->balance = $newBalance; // cập nhật tài khoản
        $this->logTransaction("Rút tiền", $amount, $this->balance); // ghi ra biên lai giao dịch
    }

    public function getAccountType(): string 
    {
        return  "Tiết kiệm"; // Loại tài khoản
    }

    public function getAccountNumber(): string
    {
        return $this->accountNumber;    // số tài khoản 
    }
    public function calculateAnnualInterest()
    {
       return $this->balance * self::INTEREST_RATE; // só tiền lợi nhuận khi gửi 1 năm
    }

}
