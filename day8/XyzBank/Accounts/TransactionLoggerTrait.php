<?php 

namespace XyzBank\Accounts;

trait TransactionLoggerTrait
{
    public function logTransaction(string $type, float $amount, float $newBalance): void // In ra thông tin của giao dịch 
    {   
       
        $timestamp = date("Y-m-d H:i:s");
        $formattedAmount = number_format($amount, 2, ",", "."); // số tiền
        $formattedBalance = number_format($newBalance,2,",", "." ); // số dư tài khoản
        $transactionLogs[] =   "[$timestamp] Giao dịch gửi tiền: $amount | Số dư mới: " . $newBalance . "VNĐ";
    }
}