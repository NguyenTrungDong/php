<?php 

namespace XyzBank\Accounts;

class BankClass
{   
    const BANK_NAME = "Ngân hàng XYZ";
    private static $totalAccounts = 0;

    static public function getBankName(): string
    {   
        $bank_name = self::BANK_NAME; // tên ngân hàng
        return $bank_name;
    }

    static public function getTotalAccounts(): int
    {
        return self::$totalAccounts; // tổng số account trong ngân hàng
    }

    static public function incrementTotalAccounts()
    {
        return self::$totalAccounts++; // tăng lên mỗi lần thêm tạo account
    }

}