<?php

// Dữ liệu giả định
$employees = [
    ['id' => 101, 'name' => 'Nguyễn Văn A', 'base_salary' => 5000000],
    ['id' => 102, 'name' => 'Trần Thị B', 'base_salary' => 6000000],
    ['id' => 103, 'name' => 'Lê Văn C', 'base_salary' => 5500000],
];

$timesheet = [
    101 => ['2025-03-01', '2025-03-02', '2025-03-04', '2025-03-05'],
    102 => ['2025-03-01', '2025-03-03', '2025-03-04'],
    103 => ['2025-03-02', '2025-03-03', '2025-03-04', '2025-03-05', '2025-03-06'],
];

$adjustments = [
    101 => ['allowance' => 500000, 'deduction' => 200000],
    102 => ['allowance' => 300000, 'deduction' => 100000],
    103 => ['allowance' => 400000, 'deduction' => 150000],
];

define('STANDARD_WORKING_DAYS', 22);

/**
 * 1. Tính ngày công thực tế
 * @param array $timesheet Bảng chấm công
 * @return array Mảng chứa số ngày công của từng nhân viên
 */
function calculateWorkingDays($timesheet) {
    return array_map(fn($days) => count($days), $timesheet);
}

/**
 * 2. Tính lương thực lĩnh
 * @param array $employees Danh sách nhân viên
 * @param array $timesheet Bảng chấm công
 * @param array $adjustments Phụ cấp và khấu trừ
 * @return array Mảng chứa lương thực lĩnh của từng nhân viên
 */
function calculateNetSalary($employees, $timesheet, $adjustments) {
    $workingDays = calculateWorkingDays($timesheet);
    $result = [];
    
    foreach ($employees as $emp) {
        $id = $emp['id'];
        $baseSalary = $emp['base_salary'];
        $days = isset($workingDays[$id]) ? $workingDays[$id] : 0;
        $allowance = $adjustments[$id]['allowance'] ?? 0; // Phụ cấp
        $deduction = $adjustments[$id]['deduction'] ?? 0; // Khấu trừ
        
        // Lương = (lương cơ bản / 22) * ngày công + phụ cấp - khấu trừ
        $netSalary = round(($baseSalary / STANDARD_WORKING_DAYS) * $days + $allowance - $deduction);
        $result[$id] = $netSalary;
    }
    
    return $result;
}

/**
 * 3. Tạo báo cáo tổng hợp bảng lương
 * @param array $employees Danh sách nhân viên
 * @param array $timesheet Bảng chấm công
 * @param array $adjustments Phụ cấp và khấu trừ
 * @return array Mảng báo cáo lương
 */
function generatePayrollTable($employees, $timesheet, $adjustments) {
    $workingDays = calculateWorkingDays($timesheet);
    $netSalaries = calculateNetSalary($employees, $timesheet, $adjustments);
    $report = [];
    
    foreach ($employees as $emp) {
        $id = $emp['id'];
        $name = $emp['name'];
        $baseSalary = $emp['base_salary'];
        $days = $workingDays[$id] ?? 0;
        $allowance = $adjustments[$id]['allowance'] ?? 0;
        $deduction = $adjustments[$id]['deduction'] ?? 0;
        $netSalary = $netSalaries[$id] ?? 0;
        
        // Sử dụng compact để tạo mảng kết quả
        $report[] = compact('id', 'name', 'days', 'baseSalary', 'allowance', 'deduction', 'netSalary');
    }
    
    return $report;
}

/**
 * 4. Tìm nhân viên có ngày công cao nhất và thấp nhất
 * @param array $timesheet Bảng chấm công
 * @param array $employees Danh sách nhân viên
 * @return array Thông tin nhân viên có ngày công cao nhất và thấp nhất
 */
function findMinMaxWorkingDays($timesheet, $employees) {
    $workingDays = calculateWorkingDays($timesheet);
    $sortedDays = $workingDays;
    sort($sortedDays); // Sắp xếp để lấy min/max
    
    $minDays = $sortedDays[0];
    $maxDays = end($sortedDays);
    
    $employeeNames = array_column($employees, 'name', 'id');
    
    $minEmployee = array_keys($workingDays, $minDays)[0];
    $maxEmployee = array_keys($workingDays, $maxDays)[0];
    
    return [
        'min' => ['name' => $employeeNames[$minEmployee], 'days' => $minDays],
        'max' => ['name' => $employeeNames[$maxEmployee], 'days' => $maxDays]
    ];
}

/**
 * 5. Cập nhật dữ liệu nhân viên và chấm công
 * @param array &$employees Danh sách nhân viên
 * @param array &$timesheet Bảng chấm công
 * @param array $newEmployees Nhân viên mới
 * @param int $employeeId ID nhân viên cần cập nhật chấm công
 * @param string $action Hành động: push, unshift, pop, shift
 * @param string|null $date Ngày công perspective
 * @return array Cập nhật dữ liệu và trả về mảng kết quả
 */
function updateEmployeeAndTimesheet(&$employees, &$timesheet, $newEmployees, $employeeId, $action, $date = null) {
    // Gộp nhân viên mới
    if (!empty($newEmployees)) {
        $employees = array_merge($employees, $newEmployees);
    }
    
    // Cập nhật chấm công
    if (isset($timesheet[$employeeId])) {
        switch ($action) {
            case 'push':
                if ($date) array_push($timesheet[$employeeId], $date);
                break;
            case 'unshift':
                if ($date) array_unshift($timesheet[$employeeId], $date);
                break;
            case 'pop':
                array_pop($timesheet[$employeeId]);
                break;
            case 'shift':
                array_shift($timesheet[$employeeId]);
                break;
        }
    }
    
    return ['employees' => $employees, 'timesheet' => $timesheet];
}

/**
 * 6. Lọc nhân viên có số ngày công >= 4
 * @param array $timesheet Bảng chấm công
 * @param array $employees Danh sách nhân viên
 * @return array Danh sách nhân viên thỏa mãn
 */
function filterEmployeesByWorkingDays($timesheet, $employees) {
    $workingDays = calculateWorkingDays($timesheet);
    $filtered = array_filter($workingDays, fn($days) => $days >= 4, ARRAY_FILTER_USE_KEY);
    
    $employeeNames = array_column($employees, 'name', 'id');
    $result = [];
    
    foreach (array_keys($filtered) as $id) {
        $result[$id] = ['name' => $employeeNames[$id], 'days' => $filtered[$id]];
    }
    
    return $result;
}

/**
 * 7. Kiểm tra và xác thực dữ liệu
 * @param array $timesheet Bảng chấm công
 * @param array $adjustments Phụ cấp và khấu trừ
 * @param int $employeeId ID nhân viên
 * @param string $date Ngày cần kiểm tra
 * @return array Kết quả kiểm tra
 */
function validateData($timesheet, $adjustments, $employeeId, $date) {
    $isWorking = isset($timesheet[$employeeId]) && in_array($date, $timesheet[$employeeId]);
    $hasAdjustments = array_key_exists($employeeId, $adjustments);
    
    return [
        'isWorking' => $isWorking,
        'hasAdjustments' => $hasAdjustments
    ];
}

/**
 * 8. Làm sạch dữ liệu chấm công
 * @param array &$timesheet Bảng chấm công
 * @return array Bảng chấm công đã làm sạch
 */
function cleanTimesheet(&$timesheet) {
    foreach ($timesheet as &$days) {
        $days = array_unique($days);
        $days = array_values($days); // Đánh lại chỉ số
    }
    return $timesheet;
}

/**
 * Tính tổng quỹ lương
 * @param array $employees Danh sách nhân viên
 * @param array $timesheet Bảng chấm công
 * @param array $adjustments Phụ cấp và khấu trừ
 * @return int Tổng quỹ lương
 */
function getTotalSalary($employees, $timesheet, $adjustments) {
    $netSalaries = calculateNetSalary($employees, $timesheet, $adjustments);
    return array_sum($netSalaries);
}

/**
 * In bảng lương ra màn hình
 * @param array $payrollTable Bảng lương
 */
function printPayrollTable($payrollTable) {
    echo str_pad('Mã NV', 10) . str_pad('Họ tên', 20) . str_pad('Ngày công', 12) . 
         str_pad('Lương CB', 15) . str_pad('Phụ cấp', 12) . str_pad('Khấu trừ', 12) . 
         str_pad('Lương TL', 15) . "<br>";
    echo str_repeat('-', 96) . "<br>";
    
    foreach ($payrollTable as $row) {
        echo str_pad($row['id'], 10) . 
             str_pad($row['name'], 20) . 
             str_pad($row['days'], 12) . 
             str_pad(number_format($row['baseSalary']), 15) . 
             str_pad(number_format($row['allowance']), 12) . 
             str_pad(number_format($row['deduction']), 12) . 
             str_pad(number_format($row['netSalary']), 15) . "<br>";
    }
}

// Thực thi các chức năng
echo "1. Tính ngày công thực tế:<br>";
$workingDays = calculateWorkingDays($timesheet);
print_r($workingDays);

echo "<br>2. Tính lương thực lĩnh:<br>";
$netSalaries = calculateNetSalary($employees, $timesheet, $adjustments);
print_r($netSalaries);

echo "<br>3. Báo cáo tổng hợp bảng lương:<br>";
$payrollTable = generatePayrollTable($employees, $timesheet, $adjustments);
printPayrollTable($payrollTable);

echo "<br>4. Nhân viên có ngày công cao nhất và thấp nhất:<br>";
$minMax = findMinMaxWorkingDays($timesheet, $employees);
echo "Nhân viên làm nhiều nhất: {$minMax['max']['name']} ({$minMax['max']['days']} ngày công)<br>";
echo "Nhân viên làm ít nhất: {$minMax['min']['name']} ({$minMax['min']['days']} ngày công)<br>";

echo "<br>5. Cập nhật dữ liệu nhân viên và chấm công:<br>";
$newEmployees = [['id' => 104, 'name' => 'Phạm Văn D', 'base_salary' => 5200000]];
$updated = updateEmployeeAndTimesheet($employees, $timesheet, $newEmployees, 101, 'push', '2025-03-07');
$employees = $updated['employees'];
$timesheet = $updated['timesheet'];
echo "Danh sách nhân viên sau khi cập nhật:<br>";
print_r($employees);
echo "Bảng chấm công sau khi thêm ngày 2025-03-07 cho nhân viên 101:<br>";
print_r($timesheet[101]);

echo "<br>6. Lọc nhân viên có số ngày công >= 4:<br>";
$filteredEmployees = filterEmployeesByWorkingDays($timesheet, $employees);
foreach ($filteredEmployees as $emp) {
    echo "- {$emp['name']} ({$emp['days']} ngày công)<br>";
}

echo "<br>7. Kiểm tra và xác thực dữ liệu:<br>";
$validation = validateData($timesheet, $adjustments, 102, '2025-03-03');
echo "Trần Thị B có đi làm vào ngày 2025-03-03: " . ($validation['isWorking'] ? 'Có' : 'Không') . "<br>";
echo "Thông tin phụ cấp của nhân viên 101 tồn tại: " . ($validation['hasAdjustments'] ? 'Có' : 'Không') . "<br>";

echo "<br>8. Làm sạch dữ liệu chấm công:<br>";
$timesheet[101][] = '2025-03-01'; // Thêm ngày trùng lặp
echo "Trước khi làm sạch (nhân viên 101):<br>";
print_r($timesheet[101]);
$cleanedTimesheet = cleanTimesheet($timesheet);
echo "Sau khi làm sạch (nhân viên 101):<br>";
print_r($cleanedTimesheet[101]);

echo "<br>Tổng quỹ lương tháng 03/2025: " . number_format(getTotalSalary($employees, $timesheet, $adjustments)) . " VND<br>";

?>