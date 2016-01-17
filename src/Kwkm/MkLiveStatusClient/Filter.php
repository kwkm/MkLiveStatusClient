<?php
namespace Kwkm\MkLiveStatusClient;

/**
 * Class Filter
 *
 * @package Kwkm\MkLiveStatusClient
 * @author Takehiro Kawakami <take@kwkm.org>
 * @license MIT
 */
class Filter
{
    private $filters;

    public function __construct()
    {
        $this->reset();
    }

    /**
     * 任意のフィルタ設定
     *
     * @param string $filter
     * @return \Kwkm\MkLiveStatusClient\Filter
     * @throw \InvalidArgumentException if the provided argument is not of type 'string'.
     */
    public function set($filter)
    {
        $this->filters[] = trim(sprintf("Filter: %s", trim($filter))) . "\n";

        return $this;
    }

    /**
     * column = value のフィルタ設定
     *
     * @param string $column カラム名
     * @param string $value フィルタする値
     * @return \Kwkm\MkLiveStatusClient\Filter
     */
    public function equal($column, $value)
    {
        return $this->set(
            sprintf("%s = %s", $column, $value)
        );
    }

    /**
     * column ~ value のフィルタ設定
     *
     * @param string $column カラム名
     * @param string $value フィルタする値
     * @return \Kwkm\MkLiveStatusClient\Filter
     */
    public function match($column, $value)
    {
        return $this->set(
            sprintf("%s ~ %s", $column, $value)
        );
    }

    /**
     * column != value のフィルタ設定
     *
     * @param string $column カラム名
     * @param string $value フィルタする値
     * @return \Kwkm\MkLiveStatusClient\Filter
     */
    public function notEqual($column, $value)
    {
        return $this->set(
            sprintf("%s != %s", $column, $value)
        );
    }

    /**
     * column !~ value のフィルタ設定
     *
     * @param string $column カラム名
     * @param string $value フィルタする値
     * @return \Kwkm\MkLiveStatusClient\Filter
     */
    public function notMatch($column, $value)
    {
        return $this->set(
            sprintf("%s !~ %s", $column, $value)
        );
    }

    /**
     * column < value のフィルタ設定
     *
     * @param string $column カラム名
     * @param string $value フィルタする値
     * @return \Kwkm\MkLiveStatusClient\Filter
     */
    public function less($column, $value)
    {
        return $this->set(
            sprintf("%s < %s", $column, $value)
        );
    }

    /**
     * column > value のフィルタ設定
     *
     * @param string $column カラム名
     * @param string $value フィルタする値
     * @return \Kwkm\MkLiveStatusClient\Filter
     */
    public function greater($column, $value)
    {
        return $this->set(
            sprintf("%s > %s", $column, $value)
        );
    }

    /**
     * column <= value のフィルタ設定
     *
     * @param string $column カラム名
     * @param string $value フィルタする値
     * @return \Kwkm\MkLiveStatusClient\Filter
     */
    public function lessEqual($column, $value)
    {
        return $this->set(
            sprintf("%s <= %s", $column, $value)
        );
    }

    /**
     * column >= value のフィルタ設定
     *
     * @param string $column カラム名
     * @param string $value フィルタする値
     * @return \Kwkm\MkLiveStatusClient\Filter
     */
    public function greaterEqual($column, $value)
    {
        return $this->set(
            sprintf("%s >= %s", $column, $value)
        );
    }

    /**
     * Or の指定
     * @param integer $or
     * @return \Kwkm\MkLiveStatusClient\Filter
     * @throw \InvalidArgumentException if the provided argument is not of type 'integer'.
     */
    public function operatorOr($or)
    {
        $this->filters[] = sprintf("Or: %d\n", $or);

        return $this;
    }

    /**
     * And の指定
     * @param integer $and
     * @return \Kwkm\MkLiveStatusClient\Filter
     * @throw \InvalidArgumentException if the provided argument is not of type 'integer'.
     */
    public function operatorAnd($and)
    {
        $this->filters[] = sprintf("And: %d\n", $and);

        return $this;
    }

    public function negate()
    {
        $this->filters[] = "Negate:\n";

        return $this;
    }

    public function reset()
    {
        $this->filters = array();
    }

    public function get()
    {
        return $this->filters;
    }
}
