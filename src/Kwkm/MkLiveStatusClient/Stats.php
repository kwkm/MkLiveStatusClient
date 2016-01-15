<?php
namespace Kwkm\MkLiveStatusClient;

/**
 * Class Stats
 *
 * @package Kwkm\MkLiveStatusClient
 * @author Takehiro Kawakami <take@kwkm.org>
 * @license MIT
 */
class Stats
{
    private $stats;

    public function __construct()
    {
        $this->reset();
    }

    /**
     * 任意のフィルタ設定
     *
     * @param string $stats
     * @return \Kwkm\MkLiveStatusClient\Stats
     * @throw \InvalidArgumentException if the provided argument is not of type 'string'.
     */
    public function set($stats)
    {
        $this->stats[] = sprintf("Stats: %s\n", trim($stats));

        return $this;
    }

    /**
     * column = value のフィルタ設定
     *
     * @param string $column カラム名
     * @param string $value フィルタする値
     * @return \Kwkm\MkLiveStatusClient\Stats
     */
    public function equal($column, $value)
    {
        return $this->set(
            sprintf("%s = %s", $column, $value)
        );
    }

    /**
     * column != value のフィルタ設定
     *
     * @param string $column カラム名
     * @param string $value フィルタする値
     * @return \Kwkm\MkLiveStatusClient\Stats
     */
    public function notEqual($column, $value)
    {
        return $this->set(
            sprintf("%s != %s", $column, $value)
        );
    }

    public function sum($column)
    {
        return $this->set(
            sprintf("%s %s", 'sum', $column)
        );
    }

    public function min($column)
    {
        return $this->set(
            sprintf("%s %s", 'min', $column)
        );
    }

    public function max($column)
    {
        return $this->set(
            sprintf("%s %s", 'max', $column)
        );
    }

    public function avg($column)
    {
        return $this->set(
            sprintf("%s %s", 'avg', $column)
        );
    }

    public function std($column)
    {
        return $this->set(
            sprintf("%s %s", 'std', $column)
        );
    }

    public function suminv($column)
    {
        return $this->set(
            sprintf("%s %s", 'suminv', $column)
        );
    }

    public function avginv($column)
    {
        return $this->set(
            sprintf("%s %s", 'avginv', $column)
        );
    }

    /**
     * StatsAnd の指定
     * @param integer $and
     * @return \Kwkm\MkLiveStatusClient\Stats
     * @throw \InvalidArgumentException if the provided argument is not of type 'integer'.
     */
    public function operatorAnd($and)
    {
        $this->stats[] = sprintf("StatsAnd: %d\n", $and);

        return $this;
    }

    public function reset()
    {
        $this->stats = array();
    }

    public function get()
    {
        return $this->stats;
    }
}