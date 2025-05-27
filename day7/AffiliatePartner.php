<?php 

class AffiliatePartner
{   
    // Khai báo các thông tin của CTV
    protected $name;
    protected $email;
    protected $commissionRate;
    protected $isActive;
    const PLATFORM_NAME = "VietLink Affiliate";

    public function __construct($name, $email, $commissionRate, $isActive = true)
    {
        $this->name = $name;
        $this->email = $email;
        $this->commissionRate = $commissionRate;
        $this->isActive = $isActive;
    }
    
    public function __destruct() // Mỗi lần in ra thông tin của ctv
    {
        
        echo "<script>console.log('Giải phóng bộ nhớ')</script>"; // Hiển thị khi đối tượng kết thúc phạm vi, chạy xong 
                                                                    // Hoặc bị xóa bằng unset() hoặc khi toàn bộ script kết thúc
    }

    public function calculateCommission($orderValue)
    {
       if(!$this->isActive) // Kiểm tra trạng thái ctv
       {
        return 0;  // Nếu như không hoạt động sẽ không có hoa hồng
       }else{  
        return $orderValue * ($this->commissionRate /100); // Trả về tiền hoa hồng được nhận
       }
    }

    public function getSummary() // Trả về thông tin của Partner
    {   
        $status = $this->isActive ? 'Active' : 'Inactive'; // Kiểm tra trạng thái
        //Sprintf(string format, mixed ...values): string dùng để định dạng chuỗi văn bản theo 1 khuôn mẫu
        return sprintf( // In ra thông tin QTV
            "Platform: %s\nName %s\nEmail %s\ncommisstion Rate %.2f%%\nStatus: %s\n",
            self::PLATFORM_NAME,
            $this->name,
            $this->email,
            $this->commissionRate,
            $status,

        );
    }

    public function isActive() // Kiểm tra trạng thái
    {
        return $this->isActive;
    }

    public function getName() // Lấy tên QTV
    {
        return $this->name;
    }
}