<?php
namespace Kwkm\MkLiveStatusClient;

/**
 * Class Lql
 *
 * @package Kwkm\MkLiveStatusClient
 * @author Takehiro Kawakami <take@kwkm.org>
 * @license MIT
 */
class Lql
{
    /**
     * @var \Kwkm\MkLiveStatusClient\LqlObject
     */
    private $lqlObject;

    /**
     * 初期化
     *
     * @return \Kwkm\MkLiveStatusClient\Lql
     */
    public function reset()
    {
        $this->lqlObject = new LqlObject();

        return $this;
    }

    /**
     * 取得テーブルの設定
     *
     * @param string $table
     * @return \Kwkm\MkLiveStatusClient\Lql
     * @throw \InvalidArgumentException if the provided argument is not of type 'string'.
     */
    public function table($table)
    {
        $this->lqlObject->setTable($table);

        return $this;
    }

    /**
     * 取得カラムの指定
     *
     * @param string $column
     * @return \Kwkm\MkLiveStatusClient\Lql
     * @throw \InvalidArgumentException if the provided argument is not of type 'string'.
     */
    public function column($column)
    {
        $this->lqlObject->appendColumns($column);

        return $this;
    }

    /**
     * ヘッダ情報を取得するかの設定
     *
     * @param boolean $boolean
     * @return \Kwkm\MkLiveStatusClient\Lql
     * @throw \InvalidArgumentException if the provided argument is not of type 'boolean'.
     */
    public function headers($boolean)
    {
        $this->lqlObject->setHeader($boolean);

        return $this;
    }

    /**
     * 取得カラムの一括指定
     *
     * @param array $columns
     * @return \Kwkm\MkLiveStatusClient\Lql
     * @throw \InvalidArgumentException if the provided argument is not of type 'array'.
     */
    public function columns($columns)
    {
        $this->lqlObject->setColumns($columns);

        return $this;
    }

    /**
     * 任意のフィルタ設定
     *
     * @param string $filter
     * @return \Kwkm\MkLiveStatusClient\Lql
     * @throw \InvalidArgumentException if the provided argument is not of type 'string'.
     */
    public function filter($filter)
    {
        $this->lqlObject->appendStringQuery('Filter', $filter);

        return $this;
    }

    /**
     * column = value のフィルタ設定
     *
     * @param string $column カラム名
     * @param string $value フィルタする値
     * @return \Kwkm\MkLiveStatusClient\Lql
     */
    public function filterEqual($column, $value)
    {
        return $this->filter(
            sprintf("%s = %s", $column, $value)
        );
    }

    /**
     * column ~ value のフィルタ設定
     *
     * @param string $column カラム名
     * @param string $value フィルタする値
     * @return \Kwkm\MkLiveStatusClient\Lql
     */
    public function filterMatch($column, $value)
    {
        return $this->filter(
            sprintf("%s ~ %s", $column, $value)
        );
    }

    /**
     * column != value のフィルタ設定
     *
     * @param string $column カラム名
     * @param string $value フィルタする値
     * @return \Kwkm\MkLiveStatusClient\Lql
     */
    public function filterNotEqual($column, $value)
    {
        return $this->filter(
            sprintf("%s != %s", $column, $value)
        );
    }

    /**
     * column !~ value のフィルタ設定
     *
     * @param string $column カラム名
     * @param string $value フィルタする値
     * @return \Kwkm\MkLiveStatusClient\Lql
     */
    public function filterNotMatch($column, $value)
    {
        return $this->filter(
            sprintf("%s !~ %s", $column, $value)
        );
    }

    /**
     * column < value のフィルタ設定
     *
     * @param string $column カラム名
     * @param string $value フィルタする値
     * @return \Kwkm\MkLiveStatusClient\Lql
     */
    public function filterLess($column, $value)
    {
        return $this->filter(
            sprintf("%s < %s", $column, $value)
        );
    }

    /**
     * column > value のフィルタ設定
     *
     * @param string $column カラム名
     * @param string $value フィルタする値
     * @return \Kwkm\MkLiveStatusClient\Lql
     */
    public function filterGreater($column, $value)
    {
        return $this->filter(
            sprintf("%s > %s", $column, $value)
        );
    }

    /**
     * column <= value のフィルタ設定
     *
     * @param string $column カラム名
     * @param string $value フィルタする値
     * @return \Kwkm\MkLiveStatusClient\Lql
     */
    public function filterLessEqual($column, $value)
    {
        return $this->filter(
            sprintf("%s <= %s", $column, $value)
        );
    }

    /**
     * column >= value のフィルタ設定
     *
     * @param string $column カラム名
     * @param string $value フィルタする値
     * @return \Kwkm\MkLiveStatusClient\Lql
     */
    public function filterGreaterEqual($column, $value)
    {
        return $this->filter(
            sprintf("%s >= %s", $column, $value)
        );
    }

    /**
     * Stats の指定
     * @param string $stats
     * @return \Kwkm\MkLiveStatusClient\Lql
     * @throw \InvalidArgumentException if the provided argument is not of type 'string'.
     */
    public function stats($stats)
    {
        $this->lqlObject->appendStringQuery('Stats', $stats);

        return $this;
    }

    /**
     * StatsAnd の指定
     * @param integer $statsAnd
     * @return \Kwkm\MkLiveStatusClient\Lql
     * @throw \InvalidArgumentException if the provided argument is not of type 'integer'.
     */
    public function statsAnd($statsAnd)
    {
        $this->lqlObject->appendIntegerQuery('StatsAnd', $statsAnd);

        return $this;
    }

    /**
     * StatsNegate の指定
     * @return \Kwkm\MkLiveStatusClient\Lql
     */
    public function statsNegate()
    {
        $this->lqlObject->appendNoValueQuery('StatsNegate');

        return $this;
    }

    /**
     * Or の指定
     * @param integer $or
     * @return \Kwkm\MkLiveStatusClient\Lql
     * @throw \InvalidArgumentException if the provided argument is not of type 'integer'.
     */
    public function lor($or)
    {
        $this->lqlObject->appendIntegerQuery('Or', $or);

        return $this;
    }

    /**
     * Negate の指定
     * @return \Kwkm\MkLiveStatusClient\Lql
     */
    public function negate()
    {
        $this->lqlObject->appendNoValueQuery('Negate');

        return $this;
    }

    /**
     * パラメータの指定
     * @param string $parameter
     * @return \Kwkm\MkLiveStatusClient\Lql
     * @throw \InvalidArgumentException if the provided argument is not of type 'string'.
     */
    public function parameter($parameter)
    {
        $this->lqlObject->appendParameter($parameter);

        return $this;
    }

    /**
     * OutputFormat の指定
     * @param string $outputFormat
     * @return \Kwkm\MkLiveStatusClient\Lql
     * @throw \InvalidArgumentException if the provided argument is not of type 'string'.
     */
    public function outputFormat($outputFormat)
    {
        $this->lqlObject->setOutputFormat($outputFormat);

        return $this;
    }

    /**
     * Limit の指定
     * @param integer $limit
     * @return \Kwkm\MkLiveStatusClient\Lql
     * @throw \InvalidArgumentException if the provided argument is not of type 'integer'.
     */
    public function limit($limit)
    {
        $this->lqlObject->setLimit($limit);

        return $this;
    }

    /**
     * AuthUser の指定
     * @param string $authUser
     * @return \Kwkm\MkLiveStatusClient\Lql
     * @throw \InvalidArgumentException if the provided argument is not of type 'string'.
     */
    public function authUser($authUser)
    {
        $this->lqlObject->setAuthUser($authUser);

        return $this;
    }

    /**
     * Lqlの実行テキストの作成
     * @return string
     */
    public function build()
    {
        return $this->lqlObject->build();
    }

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->reset();
    }
}