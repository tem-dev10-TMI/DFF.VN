<?php 

function timeAgo($datetime) {
    date_default_timezone_set('Asia/Ho_Chi_Minh'); // VN time
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) return "Vừa xong";
    if ($diff < 3600) return floor($diff/60) . " phút trước";
    if ($diff < 86400) return floor($diff/3600) . " giờ trước";
    return date("d/m/Y H:i", $time);
}
?>