<?php
//shaozhengxing@baixing.com

namespace App\Api;

class User {
    public function all() {
        return \App\Models\User::findObj([]);
    }
}