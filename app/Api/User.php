<?php
//shaozhengxing@baixing.com

namespace App\Api;

class User {
    public function all() {
        return array_values(\App\Models\User::findObj([]));
    }
}