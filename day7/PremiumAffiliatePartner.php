<?php
require_once 'AffiliatePartner.php';
class PremiumAffiliatePartner extends AffiliatePartner
{
protected $bonusPerOrder; // Khai báo tiền tip thêm

public function __construct($name, $email, $commissionRate, $bonusPerOrder, $isActive = true)
{   
    // Gọi constructor từ parent class để thêm thông tin tiền tip
    parent::__construct($name, $email, $commissionRate, $isActive); 
    $this->bonusPerOrder = $bonusPerOrder ;
    
}

public function calculateCommission($orderValue)//Ghi đè để cộng thêm tiền thưởng
{
    if(!$this->isActive)
    {
        return 0; // Không tính hoa hồng với partner không còn hoạt động
    }
    return parent::calculateCommission($orderValue) + $this->bonusPerOrder; // Tổng tiền được nhận
}

public function getSummary()// Thêm thông tin tiền thưởng
{
    return parent::getSummary() . sprintf("Tiền thưởng thêm cho mỗi đơn hàng: %d VNĐ" , $this->bonusPerOrder);
}
}