<?php
namespace Kwkm\MkLiveStatusClient;

/**
 * Class Lql
 *
 * @package Kwkm\MkLiveStatusClient
 * @author Takehiro Kawakami <take@kwkm.org>
 * @license MIT
 */
class Lql extends LqlAbstract
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
     * @return \Kwkm\MkLiveStatusClient\Lql
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
     * @param Column $column
     * @return \Kwkm\MkLiveStatusClient\Lql
     * @throw \InvalidArgumentException if the provided argument is not of type 'string'.
     */
    public function column(Column $column)
    {
        $this->lqlObject->setColumns($column->get());

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
     * 任意のフィルタ設定
     *
     * @param \Kwkm\MkLiveStatusClient\Filter $filter
     * @return \Kwkm\MkLiveStatusClient\Lql
     * @throw \InvalidArgumentException if the provided argument is not of type 'string'.
     */
    public function filter(Filter $filter)
    {
        $this->lqlObject->appendArrayQuery($filter->get());

        return $this;
    }

    /**
     * Stats の指定
     * @param \Kwkm\MkLiveStatusClient\Stats $stats
     * @return \Kwkm\MkLiveStatusClient\Lql
     * @throw \InvalidArgumentException if the provided argument is not of type 'string'.
     */
    public function stats(Stats $stats)
    {
        $this->lqlObject->appendArrayQuery($stats->get());

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