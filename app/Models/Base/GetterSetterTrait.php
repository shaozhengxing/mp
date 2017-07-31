<?php
//jinjiajun@baixing.com

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait GetterSetterTrait {
    public function setAttribute($key, $value) {
        if ($value instanceof Mongo || $value instanceof Mysql) {
            $this->setRelation($key, $value);
        } else {
            parent::setAttribute($key, $value);
        }
    }

    public function setRelation($relation, $value) {
        if (method_exists($this, $relation)) {
            /* @var BelongsTo $relation */
            $relationShip = call_user_func([$this, $relation]);
            if ($relationShip instanceof BelongsTo) {
                $key = $relationShip->getForeignKey();
                if (!$value) {
                    parent::setAttribute($key, null);
                } else {
                    parent::setAttribute($key, $value->id);
                }
            }
            parent::setRelation($relation, $value);
        }
    }
}
