<?php
//jinjiajun@baixing.com
namespace App\Models\Base;

trait IteratorTrait {
    public function current() {
        $key = current(static::$columnAlias);

        return $this->{$key};
    }

    public function next() {
        next(static::$columnAlias);
    }

    public function key() {
        return current(static::$columnAlias);
    }

    public function valid() {
        return (bool) current(static::$columnAlias);
    }

    public function rewind() {
        reset(static::$columnAlias);
    }
}
