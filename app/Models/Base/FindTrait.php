<?php
//zhengxingok@gmail.com
namespace App\Models\Base;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Base\ExtendedBuilder;

trait FindTrait {
    protected static $pool = false;

    public function isStored() {
        return $this->id ? true : false;
    }

    /**
     * @return $this
     */
    public static function closePool() {
        static::$pool = false;
    }

    /**
     * @param $id
     *
     * @return $this
     */
    public static function fetch($id) {
        if (empty($id) && $id != 0) {
            return null;
        }

        $pooled = static::$pool !== false;
        if ($pooled && isset(static::$pool[$id])) {
            return static::$pool[$id];
        }

        $eloquent = static::find($id);
        if ($pooled) {
            static::$pool[$id] = $eloquent;
        }

        return $eloquent;
    }

    public static function cleanData($id) {
        if (isset(static::$pool[$id])) {
            unset(static::$pool[$id]);

            return true;
        }

        return false;
    }

    public static function multy(array $ids) {
        return static::findObj(['id' => ['in', $ids]], ['sort' => ['id' => 'DESC']]);
    }

    /**
     * @param ExtendedBuilder|array $query
     * @param $opts
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function findId($query, array $opts = null) {
        return static::buildQuery($query, $opts)->pluck(static::getColumn('id'))->all();
    }

    /**
     * @param ExtendedBuilder|array $query
     * @param $opts
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function findMaxId($query, array $opts = null) {
        return static::buildQuery($query, $opts)->pluck(static::getColumn('id'))->max();
    }

    /**
     * @param ExtendedBuilder|array $query
     * @param $opts
     *
     * @return array [\Illuminate\Database\Eloquent\Collection]
     */
    public static function findObj($query, array $opts = null) {
        if (isset($opts['size']) && $opts['size'] <= 0) {
            return [];
        }

        $result = [];
        foreach (static::buildQuery($query, $opts)->get()->all() as $obj) {
            $result[$obj->id] = $obj;
        }

        return $result;
    }

    /**
     * @param ExtendedBuilder|array $query
     * @param $opts
     *
     * @return static
     */
    public static function findOne($query, array $opts = null) {
        return static::buildQuery($query, $opts)->first();
    }

    /**
     * @return ExtendedBuilder
     */
    public static function makeQuery() {
        $eloquentName = static::class;

        return new ExtendedBuilder($eloquentName::query(), $eloquentName);
    }

    /**
     * @param $args
     * @param array|null $opts
     *
     * @return ExtendedBuilder|Builder
     *
     * @throws \Exception
     */
    private static function buildQuery($args, array $opts = null) {
        if ($args instanceof ExtendedBuilder) {
            $query = $args;
        } elseif (is_array($args)) {
            $query = self::buildWhereQuery($args);
        } else {
            $query = static::makeQuery();
        }
        if ($opts) {
            $query = self::appendOpts($query, $opts);
        }

        return $query;
    }

    private static function buildWhereQuery(array $args) {
        $query = static::makeQuery();
        foreach ($args as $key => $val) {
            if (is_array($val)) {
                list($operator, $operand) = $val;
                $query->where($key, $operator, $operand);
            } else {
                $query->where($key, $val);
            }
        }

        return $query;
    }

    /**
     * @param ExtendedBuilder $query
     * @param array           $opts
     *
     * @return ExtendedBuilder
     *
     * @throws \Exception
     */
    private static function appendOpts(ExtendedBuilder $query, array $opts) {
        foreach ($opts as $key => $val) {
            switch (strtolower($key)) {
                case 'sort':
                    self::appendSortOpt($query, $val);
                    break;
                case 'from':
                    self::appendFromOpt($query, $val);
                    break;
                case 'size':
                    self::appendSizeOpt($query, $val);
                    break;
                case 'groupby':
                    self::appendGroupOpt($query, $val);
                    break;
                default:
                    throw new \Exception('目前支持的option只有order, from, size');
            }
        }

        return $query;
    }

    private static function appendSortOpt(ExtendedBuilder $query, $val) {
        if (!is_array($val)) {
            throw new \Exception('sort 格式错误');
        }
        $query->orderBy(key($val), current($val));
    }

    private static function appendFromOpt(ExtendedBuilder $query, $from) {
        $from = intval($from);
        if (is_numeric($from) && $from > 0) {
            $query->skip($from);
        }
    }

    private static function appendSizeOpt(ExtendedBuilder $query, $size) {
        $size = intval($size);
        if (is_numeric($size) && $size > 0) {
            $query->take($size);
        }
    }

    private static function appendGroupOpt(ExtendedBuilder $query, $group) {
        $query->groupBy($group);
    }
}
