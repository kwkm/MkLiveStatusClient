<?php
namespace Kwkm\MkLiveStatusClient;

/**
 * Class Parser
 *
 * @package Kwkm\MkLiveStatusClient
 * @author Takehiro Kawakami <take@kwkm.org>
 * @license MIT
 */
class Parser
{
    /**
     * ヘッダをキーとした配列として読み込む
     *
     * @param array $response
     * @return array
     */
    public function get($response)
    {
        $header = array_shift($response);
        $result = array();
        foreach ($response as $row) {
            $work = array();
            foreach ($row as $key => $value) {
                $work[$header[$key]] = $value;
            }
            $result[] = $work;
        }

        return $result;
    }

}