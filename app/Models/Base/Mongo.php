<?php
//zhengxingok@gmail.com

namespace App\Models\Base;

use \Jenssegers\Mongodb\Eloquent\Model;

abstract class Mongo extends Model implements \Iterator {
    protected $connection = 'mongodb';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'modifiedAt';

    use TimeColumnsTrait;
    use FindTrait;

    use GetterSetterTrait;
    use IteratorTrait;

    protected function beforeSave() {}
    protected function afterSave() {}

    final public function save(array $options = []) {
        $this->beforeSave();
        parent::save($options);
        $this->afterSave();

        return $this;
    }

    public static function getColumn($attributeName) {
        return $attributeName;
    }
}