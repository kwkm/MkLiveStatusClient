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
    /**
     * @var array
     */
    private $columns;

    /**
     * Column constructor.
     * @param array $columns カラム名
     */
    public function __construct($columns = array())
    {
        $this->columns = $columns;
    }

    /**
     * Add column
     * @param string $column カラム名
     * @return $this
     */
    public function add($column)
    {
        $this->columns[] = $column;

        return $this;
    }

    /**
     * Delete column
     * @param string $column カラム名
     * @return $this
     */
    public function delete($column)
    {
        $index = array_search($column, $this->columns);
        if ($index !== null) {
            unset($this->columns[$index]);
        }

        return $this;
    }

    /**
     * Get column
     * @return array    カラム名
     */
    public function get()
    {
        return $this->columns;
    }
}