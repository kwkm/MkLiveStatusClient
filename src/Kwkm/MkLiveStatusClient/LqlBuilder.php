<?php

namespace Kwkm\MkLiveStatusClient;

/**
 * Class LqlBuilder
 *
 * @package Kwkm\MkLiveStatusClient
 * @author Takehiro Kawakami <take@kwkm.org>
 * @license MIT
 *
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder filterSet(String $filter)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder filterEqual(String $column, String $value)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder filterMatch(String $column, String $value)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder filterNotEqual(String $column, String $value)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder filterNotMatch(String $column, String $value)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder filterLess(String $column, String $value)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder filterGreater(String $column, String $value)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder filterLessEqual(String $column, String $value)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder filterGreaterEqual(String $column, String $value)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder filterOr(Integer $or)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder filterAnd(Integer $and)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder filterNegate()
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder statsSet(String $stats)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder statsEqual(String $column, String $value)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder statsNotEqual(String $column, String $value)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder statsSum(String $column)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder statsMin(String $column)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder statsMax(String $column)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder statsAvg(String $column)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder statsStd(String $column)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder statsSuminv(String $column)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder statsAvginv(String $column)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder statsAnd(Integer $and)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder statsOr(Integer $or)
 * @method \Kwkm\MkLiveStatusClient\LqlBuilder statsNegate()
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
            $this->callPropertyMethod(5, $method, $arguments);

            return $this;
        }

        if (substr($method, 0, 6) === 'filter') {
            $this->callPropertyMethod(6, $method, $arguments);
            return $this;
        }

        trigger_error('Call to undefined method ' . get_class($this) . '::' . $method, E_USER_ERROR);
    }

    private function callPropertyMethod($lengthProperty, $method, $arguments)
    {
        $property = substr($method, 0, $lengthProperty);
        $callMethod = lcfirst(substr($method, $lengthProperty));

        if (($callMethod === 'or') || ($callMethod === 'and')) {
            $callMethod = 'operator' . ucfirst($callMethod);
        }

        call_user_func_array(array($this->$property, $callMethod), $arguments);

        $this->lql->$property($this->$property);
        $this->$property->reset();
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
