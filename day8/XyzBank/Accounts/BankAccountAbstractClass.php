<?php 

namespace XyzBank\Accounts;

abstract class BankAccountAbstractClass 
{
    protected $accountNumber; // Số tài khoản
    protected $ownerName; // Tên chủ thẻ
    protected $balance; // số dư tài khoản

    public function __construct(string $accountNumber, string $onwnerName, float $balance)
    {
        $this->accountNumber = $accountNumber;
        $this->ownerName = $onwnerName;
        $this->balance = $balance;
        BankClass::incrementTotalAccounts(); // hàm cộng thêm số lượng tài khoản mỗi lần thêm mới
    }

    public function getBalance(): float
    {
        return $this->balance; // trả về số dư tài khoản
    }

    public function getOwnerName(): string
    {
        return $this->ownerName; // trả về tên chủ sở hữu
    }

    public abstract function deposit(float $amount);// tiền gửi

    public abstract function withdraw(float $amount); // tiền rút

    public abstract function getAccountType(): string; // loại thẻ
    
    
}
