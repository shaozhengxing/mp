<?php
//jinjiajun@baixing.com

namespace App\Models\Base;

trait TimeColumnsTrait {
    //使用时间戳而不是时间字符串
    public function freshTimestamp() {
        return time();
    }

    public function freshTimestampString() {
        return time();
    }

    //createdAt和updatedAt不作为默认使用时间字符串的类型
    public function getDates() {
        $defaults = [];

        return $this->timestamps ? array_merge($this->dates, $defaults) : $this->dates;
    }
}
