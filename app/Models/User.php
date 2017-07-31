<?php
//shaozhengxing@baixing.com

namespace App\Models;

use App\Models\Base\Mongo;

class User extends Mongo {
    protected $collection = 'user';

    protected static $pool = [];
}