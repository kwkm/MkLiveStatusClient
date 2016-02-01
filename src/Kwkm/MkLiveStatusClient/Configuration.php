<?php
namespace Kwkm\MkLiveStatusClient;

use \InvalidArgumentException;

/**
 * Class Configuration
 *
 * @package Kwkm\MkLiveStatusClient
 * @property-read string $socketType      接続方式(unix or tcp)
 * @property-read string $socketPath      ソケットファイル
 * @property-read string $socketAddress   TCP接続時のIPアドレス
 * @property-read string $socketPort      TCP接続時のポート
 * @property-read array $socketTimeout    TCP接続時のタイムアウト秒数
 * @author Takehiro Kawakami <take@kwkm.org>
 * @license MIT
 */
class Configuration
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
     * コンストラクタ
     *
     * @param array $conf
     * @throw \BadFunctionCallException
     * @throw \InvalidArgumentException
     */
    public function __construct(array $conf)
    {
        $this->assignProperty($conf);

        $this->validateProperty();
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }

        throw new InvalidArgumentException("The option '$property' is not recognised.");
    }

    /**
     * 設定をプロパティに割り当て
     *
     * @param array $conf
     */
    private function assignProperty($conf)
    {
        foreach ($conf as $key => $value) {
            $this->setProperty($key, $value);
        }
    }

    /**
     * プロパティに値をセット
     * @param $property
     * @param $value
     */
    private function setProperty($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->{$property} = $value;
        }

        throw new InvalidArgumentException("The option '$property' is not recognised.");
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

    /**
     * socketPathの設定確認
     */
    private function validatePropetrySocketPath()
    {
        if (strlen($this->socketPath) === 0) {
            throw new InvalidArgumentException("The option socketPath must be supplied for socketType 'unix'.");
        }
    }

    /**
     * socketPathが利用可能かの確認
     */
    private function checkAccessSocketPath()
    {
        if (!file_exists($this->socketPath) || !is_readable($this->socketPath) || !is_writable($this->socketPath)) {
            throw new InvalidArgumentException(
                "The supplied socketPath '{$this->socketPath}' is not accessible to this script."
            );
        }
    }

    /**
     * socketAddressの設定確認
     */
    private function validatePropetrySocketAddress()
    {
        if (strlen($this->socketAddress) === 0) {
            throw new InvalidArgumentException("The option socketAddress must be supplied for socketType 'tcp'.");
        }
    }

    /**
     * socketPortの設定確認
     */
    private function validatePropetrySocketPort()
    {
        if (strlen($this->socketPort) === 0) {
            throw new InvalidArgumentException("The option socketPort must be supplied for socketType 'tcp'.");
        }
    }
}
