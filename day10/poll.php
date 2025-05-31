<?php
session_start();
if (!isset($_SESSION['poll'])) {
    $_SESSION['poll'] = [
        'interface' => 0,
        'speed' => 0,
        'service' => 0
    ];
}

$vote = isset($_POST['vote']) ? $_POST['vote'] : '';
if (in_array($vote, ['interface', 'speed', 'service'])) {
    $_SESSION['poll'][$vote]++;
}

$total = array_sum($_SESSION['poll']);
$results = [];
foreach ($_SESSION['poll'] as $key => $value) {
    $results[$key] = $total ? round(($value / $total) * 100, 2) : 0;
}

header('Content-Type: application/json');
echo json_encode($results);
?>