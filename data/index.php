<?php
include('../db.php');
include('Calendar.php');
date_default_timezone_set('PRC');

// 获取传入参数data，type
if (isset($_GET['data']) && !empty($_GET['data'])) {
    $data = strtotime($_GET['data']);
    if (!$data) {
        $data = strtotime(date("Y-m-d"));
    }
} else {
    $data = strtotime(date("Y-m-d"));
}
$data = getDate($data);
if (isset($_GET['type']) && !empty($_GET['type'])) {
    if ($_GET['type'] == 'solar' || $_GET['type'] == 'lunar') {
        $type = $_GET['type'];
    } else {
        $type = 'solar';
    }
} else {
    $type = 'solar';
}



function get_rt_data($data, $type)
{
    $calendar = new Calendar();
    if ($type == 'solar') {
        // 阳历->阴历
        $result=$calendar->solar($data['year'], $data['mon'], $data['mday']);
    } elseif ($type == 'lunar') {
        // 阴历->阳历
        $result=$calendar->lunar($data['year'], $data['mon'], $data['mday']);
    }
    return $result;
}

echo (json_encode(get_rt_data($data, $type)));



// echo ($type);
// echo(date("Y-m-d"));
// print_r($data);
// echo (json_encode($data));

// $db = new db;
// $selectResult = $db->selectById("hitokoto", "26");
// echo(var_dump("查询结果：", mysqli_fetch_assoc($selectResult)));

// echo(var_dump($result));
// echo (json_encode($result));
