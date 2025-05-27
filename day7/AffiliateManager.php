<?php 
require_once 'AffiliatePartner.php';
require_once 'PremiumAffiliatePartner.php';
class AffiliateManager 
{
    private $partners = []; // Mảng thông tin các QTV 
    protected $partner;
    protected $orderValue;

    public function addPartner(AffiliatePartner $partner)
    {
        $this->partners[] = $partner; // Thêm thông tin của QTV mới
        return $partner->getSummary(); // Trả về thông tin
    }

    public function listPartners() // Danh sách các cộng tác viên
    {
        if(empty($this->partners))
        {
            return "Chưa có Cộng Tác Viên nào!";
            
        }
        // str_repeat(string : chuỗi muốn lặp, times: số lần lặp) - Trả về chuỗi theo số lần lặp mong muốn
        $output = "Danh sách Các CTV Affiliate:\n" . str_repeat("=", 50) . "\n";
        foreach($this->partners as $index => $partner)
        {
            $output .= "Partner" . ($index + 1) . ":\n"; // Tên
            $output .= $partner->getSummary();   // Thông tin chi tiết
            $output .= str_repeat("-", 30) . "\n"; // vẽ chuỗi 
        }
        return $output;
    }

    public function totalCommission($orderValue)
    {
        $total = 0; // Giá trị khởi đầu = 0
        $output = "";
        foreach ($this->partners as $partner) // In ra từng thông tin của các QTV
        {
            if ($partner->isActive()) // Kiểm tra trạng thái
            {
                $commission = $partner->calculateCommission($orderValue); // Tiền hoa hồn được nhận
                $total += $commission;   
                $output .= sprintf( // In ra số tiền hoa hông được nhận
                    "Hoa hồng cho %s: %d VNĐ\n",
                    $partner->getName() ?? "Unknow",
                    $commission
                );
            }
        }
        $output .= sprintf("Tổng hoa hồng cho tất cả cộng tác viên là: %d VNĐ\n", $total);
        return [$total, $output];
    }

    public function getPartners() // lấy danh sách các ctv
    {
        return $this->partners;
    }
    
}