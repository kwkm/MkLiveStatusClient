<?php

namespace Kwkm\MkLiveStatusClient;

/**
 * Class LqlBuilder
 *
 * @package Kwkm\MkLiveStatusClient
 * @author Takehiro Kawakami <take@kwkm.org>
 * @license MIT
 */
class LqlBuilder extends LqlAbstract
{
    /**
     * @var \Kwkm\MkLiveStatusClient\LqlObject
     */
    private $lqlObject;

    /**
     * @var string
     */
    private $table;

    /**
     * @var string
     */
    private $authUser;

    /**
     * 初期化
     *
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
     */
    public function reset()
    {
        $this->lqlObject = new LqlObject();
        $this->lqlObject->setTable($this->table);
        if (!is_null($this->authUser)) {
            $this->lqlObject->setAuthUser($this->authUser);
        }

        return $this;
    }

    /**
     * 取得カラムの指定
     *
     * @param string $column
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
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
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
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
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
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
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
     * @throw \InvalidArgumentException if the provided argument is not of type 'string'.
     */
    public function filter($filter)
    {
        $this->lqlObject->appendStringQuery('Filter', trim($filter));

        return $this;
    }

    /**
     * column = value のフィルタ設定
     *
     * @param string $column カラム名
     * @param string $value フィルタする値
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
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
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
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
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
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
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
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
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
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
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
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
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
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
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
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
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
     * @throw \InvalidArgumentException if the provided argument is not of type 'string'.
     */
    public function stats($stats)
    {
        $this->lqlObject->appendStringQuery('Stats', $stats);

        return $this;
    }

    /**
     * column = value のStats設定
     *
     * @param string $column カラム名
     * @param string $value フィルタする値
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
     */
    public function statsEqual($column, $value)
    {
        return $this->stats(
            sprintf("%s = %s", $column, $value)
        );
    }

    /**
     * column != value のStats設定
     *
     * @param string $column カラム名
     * @param string $value フィルタする値
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
     */
    public function statsNotEqual($column, $value)
    {
        return $this->filter(
            sprintf("%s != %s", $column, $value)
        );
    }

    /**
     * StatsAnd の指定
     * @param integer $statsAnd
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
     * @throw \InvalidArgumentException if the provided argument is not of type 'integer'.
     */
    public function statsAnd($statsAnd)
    {
        $this->lqlObject->appendIntegerQuery('StatsAnd', $statsAnd);

        return $this;
    }

    /**
     * StatsOr の指定
     * @param integer $statsAnd
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
     * @throw \InvalidArgumentException if the provided argument is not of type 'integer'.
     */
    public function statsOr($statsOr)
    {
        $this->lqlObject->appendIntegerQuery('StatsOr', $statsOr);

        return $this;
    }

    /**
     * StatsNegate の指定
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
     */
    public function statsNegate()
    {
        $this->lqlObject->appendNoValueQuery('StatsNegate');

        return $this;
    }

    /**
     * Or の指定
     * @param integer $or
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
     * @throw \InvalidArgumentException if the provided argument is not of type 'integer'.
     */
    public function filterOr($or)
    {
        $this->lqlObject->appendIntegerQuery('Or', $or);

        return $this;
    }

    /**
     * And の指定
     * @param integer $and
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
     * @throw \InvalidArgumentException if the provided argument is not of type 'integer'.
     */
    public function filterAnd($and)
    {
        $this->lqlObject->appendIntegerQuery('And', $and);

        return $this;
    }

    /**
     * Negate の指定
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
     */
    public function negate()
    {
        $this->lqlObject->appendNoValueQuery('Negate');

        return $this;
    }

    /**
     * パラメータの指定
     * @param string $parameter
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
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
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
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
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
     * @throw \InvalidArgumentException if the provided argument is not of type 'integer'.
     */
    public function limit($limit)
    {
        $this->lqlObject->setLimit($limit);

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
    public function __construct($table, $authUser = null)
    {
        $this->table = $table;
        $this->authUser = $authUser;
        $this->reset();
    }
}
