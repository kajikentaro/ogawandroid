<?php
$array = [
    'main categoly' => [
        'popular',
        'music',
        'variety',
        'enjoy'
    ],
    'URL' => [
        'http://hoge.com',
        'http://hoge.com',
        'http://hoge.com',
        'http://hoge.com',
    ],
    'join_member_count' => [
        '22',
        '22',
        '22',
        '22',
        '22',
    ]
];
// Origin null is not allowed by Access-Control-Allow-Origin.とかのエラー回避の為、ヘッダー付与
header("Access-Control-Allow-Origin: *");
echo json_encode($array);
?>
