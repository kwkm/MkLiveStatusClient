<?php
/**
 * MkLiveStatusClient - Lql
 *
 * @author Takehiro Kawakami <take@kwkm.org>
 * @license MIT
 */
namespace Kwkm\MkLiveStatusClient;

use \InvalidArgumentException;

/**
 * Class Lql
 * @package Kwkm\MkLiveStatusClient
 */
class Lql
{
    /**
     * Lql query
     * @var array
     */
    protected $queries;

    /**
     * Acquisition table
     * @var string
     */
    protected $table;

    /**
     * Header
     * @var boolean
     */
    protected $headers;

    /**
     * Acquisition column
     * @var array
     */
    protected $columns;

    /**
     * Output Format type
     * @var string
     */
    protected $outputFormat;

    /**
     * Authentication username
     * @var string
     */
    protected $authUser;

    /**
     * Acquisition number
     * @var integer;
     */
    protected $limit;

    /**
     * 初期化
     *
     * @return \Kwkm\MkLiveStatusClient\Lql
     */
    public function reset()
    {
        $this->queries = array();
        $this->table = null;
        $this->columns = array();
        $this->authUser = null;
        $this->limit = null;

        $this->headers(true);
        $this->outputFormat('json');

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
        if (!is_string($table)) {
            throw new InvalidArgumentException("Argument 1 must be a string.");
        }
        $this->table = $table;

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
        if (!is_string($column)) {
            throw new InvalidArgumentException("Argument 1 must be a string.");
        }
        $this->columns[] = $column;

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
        if (!is_bool($boolean)) {
            throw new InvalidArgumentException("Argument 1 must be a boolean.");
        }
        if ($boolean === true) {
            $this->headers = "ColumnHeaders: on\n";
        } else {
            $this->headers = "ColumnHeaders: off\n";
        }

        return $this;
    }

    /**
     * 取得カラムの一括指定
     *
     * @param array $columns
     * @return \Kwkm\MkLiveStatusClient\Lql
     * @throw \InvalidArgumentException if the provided argument is not of type 'array'.
     */
    public function columns(array $columns)
    {
        if (!is_array($columns)) {
            throw new InvalidArgumentException("Argument 1 must be an array.");
        }
        $this->columns = $columns;

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
        if (!is_string($filter)) {
            throw new InvalidArgumentException("Argument 1 must be a string.");
        }
        $this->queries[] = sprintf("Filter: %s\n", $filter);

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
        if (!is_string($stats)) {
            throw new InvalidArgumentException("Argument 1 must be a string.");
        }
        $this->queries[] = sprintf("Stats: %s\n", $stats);

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
        if (!is_int($statsAnd)) {
            throw new InvalidArgumentException("Argument 1 must be an integer.");
        }
        $this->queries[] = sprintf("StatsAnd: %d\n", $statsAnd);

        return $this;
    }

    /**
     * StatsNegate の指定
     * @return \Kwkm\MkLiveStatusClient\Lql
     */
    public function statsNegate()
    {
        $this->queries[] = "StatsNegate:\n";

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
        if (!is_int($or)) {
            throw new InvalidArgumentException("Argument 1 must be an integer.");
        }
        $this->queries[] = sprintf("Or: %d\n", $or);

        return $this;
    }

    /**
     * Negate の指定
     * @return \Kwkm\MkLiveStatusClient\Lql
     */
    public function negate()
    {
        $this->queries[] = "Negate:\n";

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
        if (!is_string($parameter)) {
            throw new InvalidArgumentException("Argument 1 must be a string.");
        }
        if (trim($parameter) === "") {
            return $this;
        }
        $this->queries[] = $this->formatEnding($parameter);

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
        if (!is_string($outputFormat)) {
            throw new InvalidArgumentException("Argument 1 must be a string.");
        }
        $this->outputFormat = sprintf("OutputFormat: %s\n", $outputFormat);

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
        if (!is_int($limit)) {
            throw new InvalidArgumentException("Argument 1 must be an integer.");
        }
        $this->limit = sprintf("Limit: %d\n", $limit);

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
        if (!is_string($authUser)) {
            throw new InvalidArgumentException("Argument 1 must be a string.");
        }
        $this->authUser = sprintf("AuthUser: %s\n", $authUser);;

        return $this;
    }

    /**
     * Lqlの実行テキストの作成
     * @return string
     */
    public function build()
    {
        $request = sprintf("GET %s\n", $this->table);

        if ($this->columns) {
            $request .= sprintf("Columns: %s\n", implode(' ', $this->columns));
            $request .= $this->headers;
        }

        if (!is_null($this->queries)) {
            $request .= implode('', $this->queries);
        }

        if (!is_null($this->outputFormat)) {
            $request .= $this->outputFormat;
        }

        if (!is_null($this->authUser)) {
            $request .= $this->authUser;
        }

        if (!is_null($this->limit)) {
            $request .= $this->limit;
        }

        $request .= "ResponseHeader: fixed16\n";
        $request .= "\n";

        return $request;
    }

    /**
     * 終端の改行文字の付与
     * @param string $string 改行文字を付与する文字列
     * @return string           改行文字が付与された文字列
     */
    protected function formatEnding($string)
    {
        if ($string[strlen($string) - 1] !== "\n") {
            $string .= "\n";
        }

        return $string;
    }

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->reset();
    }
}