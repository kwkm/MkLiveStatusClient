<?php
/**
 * MkLiveStatusClient - Client
 *
 * @author Takehiro Kawakami <take@kwkm.org>
 * @license MIT
 */
namespace Kwkm\MkLiveStatusClient;

use \BadFunctionCallException;
use \InvalidArgumentException;
use \RuntimeException;

/**
 * Class Client
 * @package Kwkm\MkLiveStatusClient
 */
class Client
{
    /**
     * 接続方式(unix or tcp)
     * @var string
     */
    protected $socketType = "unix";

    /**
     * ソケットファイル
     * @var string
     */
    protected $socketPath = "/var/run/nagios/rw/live";

    /**
     * TCP接続時のIPアドレス
     * @var string
     */
    protected $socketAddress = "";

    /**
     * TCP接続時のポート
     * @var string
     */
    protected $socketPort = "";

    /**
     * TCP接続時のタイムアウト秒数
     * @var array
     * @link http://php.net/manual/en/function.socket-set-option.php
     */
    protected $socketTimeout = array();

    /**
     * ソケットのリソース
     * @var resource
     */
    protected $socket = null;

    /**
     * コンストラクタ
     *
     * @param array $conf
     * @throw \BadFunctionCallException
     * @throw \InvalidArgumentException
     */
    public function __construct(array $conf)
    {
        if (!function_exists("socket_create")) {
            throw new BadFunctionCallException("The PHP function socket_create is not available.");
        }

        $this->assignProperty($conf);

        $this->validateProperty();

        $this->reset();
    }

    /**
     * 設定をプロパティに割り当て
     *
     * @param array $conf
     */
    private function assignProperty($conf)
    {
        foreach ($conf as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            } else {
                throw new InvalidArgumentException("The option '$key' is not recognised.");
            }
        }
    }

    /**
     * プロパティの値の妥当性を確認
     */
    private function validateProperty()
    {
        switch ($this->socketType) {
            case "unix":
                $this->validatePropetrySocketPath();
                $this->checkAccessSocketPath();
                break;
            case "tcp":
                $this->validatePropetrySocketAddress();
                $this->validatePropetrySocketPort();
                break;
            default:
                throw new InvalidArgumentException("Socket Type is invalid. Must be one of 'unix' or 'tcp'.");
        }
    }

    private function validatePropetrySocketPath()
    {
        if (strlen($this->socketPath) === 0) {
            throw new InvalidArgumentException("The option socketPath must be supplied for socketType 'unix'.");
        }
    }

    private function checkAccessSocketPath()
    {
        if (!file_exists($this->socketPath) || !is_readable($this->socketPath) || !is_writable($this->socketPath)) {
            throw new InvalidArgumentException("The supplied socketPath '{$this->socketPath}' is not accessible to this script.");
        }
    }

    private function validatePropetrySocketAddress()
    {
        if (strlen($this->socketAddress) === 0) {
            throw new InvalidArgumentException("The option socketAddress must be supplied for socketType 'tcp'.");
        }
    }

    private function validatePropetrySocketPort()
    {
        if (strlen($this->socketPort) === 0) {
            throw new InvalidArgumentException("The option socketPort must be supplied for socketType 'tcp'.");
        }
    }

    /**
     * Lqlの実行
     *
     * @param \Kwkm\MkLiveStatusClient\Lql $lql
     * @return array
     * @throw \RuntimeException
     */
    public function execute(Lql $lql)
    {
        $result = $this->executeRequest($lql->build());

        $this->verifyStatusCode($result);

        $response = json_decode(utf8_encode($result['response']));

        if (is_null($response)) {
            throw new RuntimeException("The response was invalid.");
        }

        return $response;
    }

    private function executeRequest($query)
    {
        $this->openSocket();
        socket_write($this->socket, $query);
        // Read 16 bytes to get the status code and body size
        $header = $this->readSocket(16);
        $status = substr($header, 0, 3);
        $length = intval(trim(substr($header, 4, 11)));
        $response = $this->readSocket($length);
        $this->closeSocket();

        return array(
            'status' => $status,
            'length' => $length,
            'response' => $response,
        );
    }

    private function verifyStatusCode($response)
    {
        // Check for errors. A 200 response means request was OK.
        // Any other response is a failure.
        if ($response['status'] != "200") {
            throw new RuntimeException("Error response from Nagios MK Livestatus: " . $response['response']);
        }
    }

    /**
     * リセット処理
     */
    public function reset()
    {
        $this->closeSocket();
    }

    /**
     * 接続の開始
     *
     * @throw \RuntimeException
     */
    protected function openSocket()
    {
        if (!is_null($this->socket)) {
            // Assume socket still good and continue
            return;
        }

        switch ($this->socketType) {
            case 'unix':
                $this->openUnixSocket();
                break;
            case 'tcp':
                $this->openTcpSocket();
                break;
        }
    }

    /**
     * Unixソケットの接続を開始
     */
    private function openUnixSocket()
    {
        $this->socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
        if (!$this->socket) {
            $this->socket = null;
            throw new RuntimeException("Could not create socket.");
        }

        if (false === socket_connect($this->socket, $this->socketPath)) {
            $this->closeSocket();
            throw new RuntimeException("Unable to connect to socket.");
        }

        $this->setSocketTimeout();
    }

    /**
     * Tcpソケットの接続を開始
     */
    private function openTcpSocket()
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!$this->socket) {
            $this->socket = null;
            throw new RuntimeException("Could not create socket.");
        }

        if (false === socket_connect($this->socket, $this->socketAddress, $this->socketPort)) {
            $this->closeSocket();
            throw new RuntimeException("Unable to connect to socket.");
        }

        socket_set_option($this->socket, SOL_TCP, TCP_NODELAY, 1);

        $this->setSocketTimeout();
    }

    /**
     * ソケットタイムアウトの設定
     */
    private function setSocketTimeout()
    {
        if (count($this->socketTimeout) !== 0) {
            socket_set_option($this->socket, SOCK_STREAM, SO_RCVTIMEO, $this->socketTimeout);
            socket_set_option($this->socket, SOCK_STREAM, SO_SNDTIMEO, $this->socketTimeout);
        }
    }

    /**
     * 接続の切断
     */
    protected function closeSocket()
    {
        if (is_resource($this->socket)) {
            socket_close($this->socket);
        }
        $this->socket = null;
    }

    /**
     * ソケットの読み出し
     *
     * @param integer $length
     * @return string
     * @throw \RuntimeException
     */
    protected function readSocket($length)
    {
        $offset = 0;
        $socketData = "";
        while ($offset < $length) {
            if (false === ($data = socket_read($this->socket, $length - $offset))) {
                throw new RuntimeException(
                    "Problem reading from socket: "
                    . socket_strerror(socket_last_error($this->socket))
                );
            }
            $dataLen = strlen($data);
            $offset += $dataLen;
            $socketData .= $data;
            if ($dataLen == 0) {
                break;
            }
        }

        return $socketData;
    }

}
