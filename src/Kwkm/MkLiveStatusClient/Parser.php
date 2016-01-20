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
    public function decode($response)
    {
        return json_decode($response, true);
    }

    /**
     * ヘッダをキーとした配列として読み込む
     *
     * @param string $response
     * @return array
     */
    public function get($response)
    {
        $json = $this->decode($response);
        $header = array_shift($json);
        $result = array();
        foreach ($json as $row) {
            $work = array();
            foreach ($row as $key => $value) {
                $work[$header[$key]] = $value;
            }
            $result[] = $work;
        }

        return $result;
    }

}
