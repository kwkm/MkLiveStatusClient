<?php
namespace Kwkm\MkLiveStatusClient;

/**
 * Class Column
 *
 * @package Kwkm\MkLiveStatusClient
 * @author Takehiro Kawakami <take@kwkm.org>
 * @license MIT
 */
class Column
{
    private $columns;

    public function __construct($columns = array())
    {
        $this->columns = $columns;
    }

    public function add($column)
    {
        $this->columns[] = $column;

        return $this;
    }

    public function delete($column)
    {
        $index = array_search($column, $this->columns);
        if ($index !== null) {
            unset($this->columns[$index]);
        }

        return $this;
    }

    public function get()
    {
        return $this->columns;
    }
}