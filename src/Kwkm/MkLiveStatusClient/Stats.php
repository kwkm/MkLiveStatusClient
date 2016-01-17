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
     * 任意のStats設定
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
     * column = value のStats設定
     *
     * @param string $column カラム名
     * @param string $value Statsする値
     * @return \Kwkm\MkLiveStatusClient\Stats
     */
    public function equal($column, $value)
    {
        return $this->set(
            sprintf("%s = %s", $column, $value)
        );
    }

    /**
     * column != value のStats設定
     *
     * @param string $column カラム名
     * @param string $value Statsする値
     * @return \Kwkm\MkLiveStatusClient\Stats
     */
    public function notEqual($column, $value)
    {
        return $this->set(
            sprintf("%s != %s", $column, $value)
        );
    }

    /**
     * 合計値の集計
     * @param string $column カラム名
     * @return \Kwkm\MkLiveStatusClient\Stats
     */
    public function sum($column)
    {
        return $this->set(
            sprintf("%s %s", 'sum', $column)
        );
    }

    /**
     * 最小値の集計
     * @param string $column カラム名
     * @return \Kwkm\MkLiveStatusClient\Stats
     */
    public function min($column)
    {
        return $this->set(
            sprintf("%s %s", 'min', $column)
        );
    }

    /**
     * 最大値の集計
     * @param string $column カラム名
     * @return \Kwkm\MkLiveStatusClient\Stats
     */
    public function max($column)
    {
        return $this->set(
            sprintf("%s %s", 'max', $column)
        );
    }

    /**
     * 平均値の集計
     * @param string $column カラム名
     * @return \Kwkm\MkLiveStatusClient\Stats
     */
    public function avg($column)
    {
        return $this->set(
            sprintf("%s %s", 'avg', $column)
        );
    }

    /**
     * 標準偏差の集計
     * @param string $column カラム名
     * @return \Kwkm\MkLiveStatusClient\Stats
     */
    public function std($column)
    {
        return $this->set(
            sprintf("%s %s", 'std', $column)
        );
    }

    /**
     * 合計値の逆数の集計
     * @param string $column カラム名
     * @return \Kwkm\MkLiveStatusClient\Stats
     */
    public function suminv($column)
    {
        return $this->set(
            sprintf("%s %s", 'suminv', $column)
        );
    }

    /**
     * 平均値の逆数の集計
     * @param string $column カラム名
     * @return \Kwkm\MkLiveStatusClient\Stats
     */
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

    /**
     * StatsOr の指定
     * @param integer $or
     * @return \Kwkm\MkLiveStatusClient\Stats
     * @throw \InvalidArgumentException if the provided argument is not of type 'integer'.
     */
    public function operatorOr($or)
    {
        $this->stats[] = sprintf("StatsOr: %d\n", $or);

        return $this;
    }

    /**
     * StatsNegate の指定
     * @return \Kwkm\MkLiveStatusClient\Stats
     * @throw \InvalidArgumentException if the provided argument is not of type 'integer'.
     */
    public function negate()
    {
        $this->stats[] = "StatsNegate:\n";

        return $this;
    }

    /**
     * Statsのリセット
     * @return $this
     */
    public function reset()
    {
        $this->stats = array();

        return $this;
    }

    /**
     * Get Stats
     * @return array
     */
    public function get()
    {
        return $this->stats;
    }
}