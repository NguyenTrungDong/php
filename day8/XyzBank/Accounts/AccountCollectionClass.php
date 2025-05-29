<?php

namespace XyzBank\Accounts;
use XyzBank\Accounts\BankAccountAbstractClass;

class AccountCollectionClass implements \IteratorAggregate
{
    private array $accounts = [];

    public function addAccount(BankAccountAbstractClass $account)
    {
        $this->accounts[] = $account; // thêm mới thông tin account vào danh sách bank accounts
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->accounts); // trả về danh sách tài khoản
    }

    public function filterHighBalanceAccounts(float $threshold = 10000000): array
    {
        return array_filter($this->accounts, fn($account) => $account->getBalance()>= $threshold);
         // lọc những tài khoản với số dư từ 10 triệu
    }
}