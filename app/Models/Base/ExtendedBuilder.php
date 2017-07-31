<?php
//caoweijie@baixing.com
namespace App\Models\Base;

use Illuminate\Database\Eloquent\Builder;

class ExtendedBuilder {
    private $builder = null;
    private $model = null;

    public function __construct(Builder $builder, $model) {
        $this->builder = $builder;
        $this->model = $model;
    }

    public function __call($name, $args) {
        if (is_callable([$this->builder, $name])) {
            $result = call_user_func_array([$this->builder, $name], $args);
        }

        return $result instanceof Builder ? $this : $result;
    }

    public function where($column, $operator = null, $value = null, $boolean = 'and') {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        if ($value instanceof Mongo) {
            $modelClass = $this->model;
            $object = new $modelClass();
            if (method_exists($object, $column)) {
                $relation = call_user_func([$object, $column]);
                $column = $relation->getForeignKey();
                $value = $value->id;
            } else {
                throw new \Exception("$column 没有 Relation 设置,无法是有对象进行搜索");
            }
        }

        if (is_scalar($operator)) {
            $model = $this->model;
            $column = $model::getColumn($column);
            switch ((string) $operator) {
                case 'in':
                    if (!is_array($value)) {
                        throw new \Exception('in 查询参数需要数组');
                    }
                    $this->builder->whereIn($column, $value);
                    break;
                case 'range':
                    if (!is_array($value)) {
                        throw new \Exception('range 查询参数需要数组');
                    }
                    if (count($value) !== 2) {
                        throw new \Exception('range 参数错误');
                    }
                    if ($value[0] < $value[1]) {
                        $this->builder->whereBetween($column, $value);
                    } elseif ($value[0] > $value[1]) {
                        $this->builder->whereNotBetween($column, [$value[1], $value[0]]);
                    } else {
                        $this->builder->where($column, $value[0]);
                    }
                    break;
                default:
                    $this->builder->where($column, $operator, $value, $boolean);
            }
        } else {
            throw new \Exception($operator);
        }

        return $this;
    }
}
