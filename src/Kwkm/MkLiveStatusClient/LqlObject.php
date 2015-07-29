<?php
namespace Kwkm\MkLiveStatusClient;

use \InvalidArgumentException;

/**
 * Class Lql Object
 *
 * @package Kwkm\MkLiveStatusClient
 * @author Takehiro Kawakami <take@kwkm.org>
 * @license MIT
 */
class LqlObject
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
     * @var string
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

    public function __construct()
    {
        $this->reset();
    }

    public function setTable($table)
    {
        if (!is_string($table)) {
            throw new InvalidArgumentException("Argument 1 must be a string.");
        }

        $this->table = $table;
    }

    public function setColumns($columns)
    {
        if (!is_array($columns)) {
            throw new InvalidArgumentException("Argument 1 must be an array.");
        }
        $this->columns = $columns;
    }

    public function appendColumns($column)
    {
        if (!is_string($column)) {
            throw new InvalidArgumentException("Argument 1 must be a string.");
        }
        $this->columns[] = $column;
    }

    public function appendStringQuery($name, $value)
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException("Argument 1 must be a string.");
        }
        $this->queries[] = sprintf("%s: %s\n", $name, $value);
    }

    public function appendIntegerQuery($name, $value)
    {
        if (!is_int($value)) {
            throw new InvalidArgumentException("Argument 1 must be an integer.");
        }
        $this->queries[] = sprintf("%s: %d\n", $name, $value);
    }

    public function appendNoValueQuery($name)
    {
        $this->queries[] = sprintf("%s:\n", $name);
    }

    public function appendParameter($parameter)
    {
        if (!is_string($parameter)) {
            throw new InvalidArgumentException("Argument 1 must be a string.");
        }
        if (trim($parameter) !== "") {
            $this->queries[] = $this->formatEnding($parameter);
        }
    }

    public function setHeader($boolean)
    {
        if (!is_bool($boolean)) {
            throw new InvalidArgumentException("Argument 1 must be a boolean.");
        }
        if ($boolean === true) {
            $this->headers = "ColumnHeaders: on\n";
        } else {
            $this->headers = "ColumnHeaders: off\n";
        }
    }

    public function setOutputFormat($outputFormat)
    {
        if (!is_string($outputFormat)) {
            throw new InvalidArgumentException("Argument 1 must be a string.");
        }
        $this->outputFormat = sprintf("OutputFormat: %s\n", $outputFormat);
    }

    public function setLimit($limit)
    {
        if (!is_int($limit)) {
            throw new InvalidArgumentException("Argument 1 must be an integer.");
        }
        $this->limit = sprintf("Limit: %d\n", $limit);
    }

    public function setAuthUser($authUser)
    {
        if (!is_string($authUser)) {
            throw new InvalidArgumentException("Argument 1 must be a string.");
        }
        $this->authUser = sprintf("AuthUser: %s\n", $authUser);
    }

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

        $this->setHeader(true);
        $this->setOutputFormat('json');
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

    public function build()
    {
        $request = sprintf("GET %s\n", $this->table)
            . $this->getColumnsField()
            . $this->getQueriesFiled()
            . $this->getOutputFormatFiled()
            . $this->getAuthFiled()
            . $this->getLimitField()
            . "ResponseHeader: fixed16\n"
            . "\n";

        return $request;
    }

    private function getColumnsField()
    {
        if (count($this->columns) !== 0) {
            return sprintf("Columns: %s\n", implode(' ', $this->columns)) . $this->headers;
        }

        return '';
    }

    private function getQueriesFiled()
    {
        if (!is_null($this->queries)) {
            return implode('', $this->queries);
        }

        return '';
    }

    private function getOutputFormatFiled()
    {
        if (!is_null($this->outputFormat)) {
            return $this->outputFormat;
        }

        return '';
    }

    private function getAuthFiled()
    {
        if (!is_null($this->authUser)) {
            return $this->authUser;
        }

        return '';
    }

    private function getLimitField()
    {
        if (!is_null($this->limit)) {
            return $this->limit;
        }

        return '';
    }
}