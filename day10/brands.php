<?php
$category = isset($_GET['category']) ? $_GET['category'] : '';
$xml = simplexml_load_file('brands.xml');

echo "<select>";
echo "<option value=''>Chọn thương hiệu</option>";
foreach ($xml->category as $cat) {
    if ((string)$cat['name'] === $category || $category === '') {
        foreach ($cat->brand as $brand) {
            echo "<option value='$brand'>$brand</option>";
        }
    }
}
echo "</select>";
?>