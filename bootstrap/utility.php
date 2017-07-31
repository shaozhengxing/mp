<?php
//shaozhengxing@baixing.com
//用户存放全局定义的utility函数

function remote_ip() {
    static $ip;    // 第一次计算时保存结果, 避免每次调用时再计算
    if ($ip) {
        return $ip;
    }

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    if (isset($_SERVER['HTTP_X_EBAY_CLIENT_IP'])) {
        return $_SERVER['HTTP_X_EBAY_CLIENT_IP'];
    }

    if (isset($_SERVER['REMOTE_ADDR'])) {
        return $_SERVER['REMOTE_ADDR'];    // Direct IP
    }

    return $ip = "127.0.0.1";    // CLI
}

function jsonDecode($content, $forceArray = false) {
    return json_decode(preg_replace("#[\x01-\x1F]#", '', $content), $forceArray);
}

function is_json($str){
    return !is_null(json_decode($str));
}

function dev_name() {
    preg_match_all("/\w+/", $_SERVER['HTTP_HOST'], $matches);
    return $matches[0][1];
}

function parseDate($time) {
    $day = date('d', $time);
    $month = date('m', $time);
    $month_mapping = [
        '01' => '一月',
        '02' => '二月',
        '03' => '三月',
        '04' => '四月',
        '05' => '五月',
        '06' => '六月',
        '07' => '七月',
        '08' => '八月',
        '09' => '九月',
        '10' => '十月',
        '11' => '十一月',
        '12' => '十二月',
    ];

    return [
        'time' => [
            'gap' => time_translate($time),
        ],
        'day' => [
            'num' => $day,
        ],
        'month' => [
            'num' => $month,
            'chinese' => $month_mapping[$month] ?? '',
        ],
    ];
}

function time_translate($time) {
    $dur = time() - $time;
    if ($dur < 0) {
        return date('Y年m月d日', $time);
    } else {
        if ($dur < 60) {
            return $dur . '秒前';
        } else {
            if ($dur < 3600) {
                return floor($dur / 60) . '分钟前';
            } else {
                if ($dur < 86400) {
                    return floor($dur / 3600) . '小时前';
                } else {
                    return floor($dur / 86400) . '天前';
                }
            }
        }
    }
}

function chineseStr($str) {
    preg_match_all("/[\x{4e00}-\x{9fa5}]+/u", $str, $matches);

    return count($matches) == 0 ? '' : $matches[0];
}