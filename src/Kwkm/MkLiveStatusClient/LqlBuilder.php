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
     * @var \Kwkm\MkLiveStatusClient\Lql
     */
    private $lql;

    /**
     * @var \Kwkm\MkLiveStatusClient\Column
     */
    private $column;

    /**
     * @var \Kwkm\MkLiveStatusClient\Filter
     */
    private $filter;

    /**
     * @var \Kwkm\MkLiveStatusClient\Stats
     */
    private $stats;

    /**
     * 初期化
     *
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
     */
    public function reset()
    {
        $this->lql->reset();
        $this->column = new Column();
        $this->filter = new Filter();
        $this->stats = new Stats();

        return $this;
    }

    public function __call($method, $arguments)
    {
        if (substr($method, 0, 5) === 'stats') {
            $callMethod = lcfirst(substr($method, 5));

            if (($callMethod === 'or') || ($callMethod === 'and')) {
                $callMethod = 'operator' . ucfirst($callMethod);
            }

            call_user_func_array(array($this->stats, $callMethod), $arguments);

            $this->lql->stats($this->stats);
            $this->stats->reset();

            return $this;
        }

        if (substr($method, 0, 6) === 'filter') {
            $callMethod = lcfirst(substr($method, 6));

            if (($callMethod === 'or') || ($callMethod === 'and')) {
                $callMethod = 'operator' . ucfirst($callMethod);
            }

            call_user_func_array(array($this->filter, $callMethod), $arguments);

            $this->lql->filter($this->filter);
            $this->filter->reset();

            return $this;
        }

        trigger_error('Call to undefined method ' . get_class($this) . '::' . $method, E_USER_ERROR);
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
        $this->column->add($column);
        $this->lql->column($this->column);

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
        $this->lql->headers($boolean);

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
        $this->column = new Column($columns);
        $this->lql->column($this->column);

        return $this;
    }

    /**
     * StatsNegate の指定
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
     */
    public function statsNegate()
    {
        $this->lql->statsNegate();

        return $this;
    }

    /**
     * Negate の指定
     * @return \Kwkm\MkLiveStatusClient\LqlBuilder
     */
    public function negate()
    {
        $this->lql->negate();

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
        $this->lql->parameter($parameter);

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
        $this->lql->outputFormat($outputFormat);

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
        $this->lql->limit($limit);

        return $this;
    }

    /**
     * Lqlの実行テキストの作成
     * @return string
     */
    public function build()
    {
        return $this->lql->build();
    }

    /**
     * コンストラクタ
     */
    public function __construct($table, $authUser = null)
    {
        $this->lql = new Lql($table, $authUser);
        $this->reset();
    }
}
