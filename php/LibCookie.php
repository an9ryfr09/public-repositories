<?php
/**
 * @desc cookie安全处理模块
 * @author an9ryfr09 <an9ryfr09@gmail.com>
 * @package LibCookie.php
 * @version v1.0
 */
class Cookie{

	/**
	 * @const string COOKIE_PATH cookie路径默认值
	 * @const string COOKIE_DOMAIN cookie作用域
	 * @const integer COOKIE_EXPIRE cookie过期时间
	 * @const string COOKIE_SALT 混淆码
	 * @const string AUTH_KEY_NAME cookie的key名称
	 * @const boolean HTTPONLY 是否只支持http协议
	 * @const boolean SECURE 是否开启https模式
	 */
	const COOKIE_PATH = '/';
	const COOKIE_DOMAIN = '';
	const COOKIE_EXPIRE = 86400;
	const COOKIE_SALT = 'ylmf-cookie-salt';
    const AUTH_KEY_NAME = 'ylmf_auth';
    const HTTPONLY  = false;
    const SECURE    = false;


	/**
	 * @var string $_path cookie作用路径
	 * @var string $_domain cookie作用域
	 * @var int $_expire cookie过期时间
	 */
	protected $_path, $_domain, $_expire;


	/**
	 * @desc 构造方法，初始化一些值
	 * @param string $path cookie作用路径
	 * @param string $domain cookie作用域
	 * @param int $expire cookie过期时间
	 */
	public function __construct( $path = null, $domain = null, $expire = null ){

		$this->setPath($path)
				->setDomain($domain)
				->setExpire($expire);
	}

	/**
	 * @desc 设置cookie作用路径
	 * @param string $path cookie作用路径
	 * @return \Cookie 对象本身
	 */
	protected function setPath( $path ){

		if( !isset($path) || empty($path) ){
			$this->_path = self::COOKIE_PATH;
		}
		else{
			$this->_path = $path;
		}

		return $this;
	}

	/**
	 * @desc 设置cookie作用域
	 * @param string $domain cooki作用域
	 * @return \Cookie 对象本身
	 */
	protected function setDomain( $domain ){

		if( !isset( $domain ) || empty( $domain ) ){
			$this->_domain = self::COOKIE_DOMAIN;
		}
		else{
			$this->_domain = $domain;
		}

		return $this;
	}

	/**
	 * @desc 设置cookie过期时间
	 * @param integer $expire cookie过期时间
	 * @return \Cookie 对象本身
	 */
	protected function setExpire( $expire ){
		if( !isset( $expire ) || empty( $expire ) ){
			$this->_expire = self::COOKIE_EXPIRE;
		}
		else{
			$this->_expire = $expire;
		}

		return $this;
	}

	/**
	 * @desc cookie的加密方法
	 * @param string $value 未加密cookie值
	 * @return string $value 加密后的cookie值
	 */
    protected function encode( $value ){
		return YlmfMcrypt::encrypt( $value );
    }

	/**
	 * @desc cookie的解密方法
	 * @param string $value 加密后的cookie值
	 * @return string $value 解密后的cookie值
	 */
    protected function decode( $value ){
		return YlmfMcrypt::decrypt( $value );
    }

	/**
	 * @desc 设置一个cookie值
	 * @param string $key cookie的索引名
	 * @param string $value 要设置的cookie值
	 * @param int $time cookie的过期时间
	 * @return boolean 设置cookie是否成功
	 */
    public function set($key, $value, $time = null ){

        $time = !$time ? $this->_expire : $time;

        $time = time() + $time;

		$value .= self::COOKIE_SALT;

        return setCookie( $key, $this->encode($value), $time, $this->_path, $this->_domain, self::SECURE, self::HTTPONLY );
    }

	/**
	 * @desc 通过key来获取cookie值
	 * @param string $key 要获取值的cookie键名
	 * @return string $value
	 */
    public function get( $key ){
        if( isset($_COOKIE[$key]) || !empty($_COOKIE[$key]) ){
			$value = $this->decode( $_COOKIE[$key] );
			if( $this->verify( $value ) ){
				return substr( $value, 0, strlen( $value ) - strlen(self::COOKIE_SALT) );
			}
		}
		return false;
    }

	/**
	 * @desc 通过key来删除一个cookie值
	 * @param string $key 要删除的cookie键名
	 * @return boolean
	 */
    public function del( $key ){
        return setCookie( $key, '', -1, $this->_path, $this->_domain,self::SECURE,self::HTTPONLY );
    }

	/**
	 * @desc 获取当前cookie相关参数
	 * @return array $cookie_params cookie相关信息
	 */
	public function getParams(){

		return array(
			'path' => $this->_path,
			'domain' => $this->_domain,
			'expire' => $this->_expire
		);
	}

	/**
	 * @desc 验证cookie是否被伪造
	 * @param string $value cookie解密后的值
	 * @return boolean
	 */
	protected function verify( $value ){
		$salt = substr( $value, strlen($value)-(strlen( self::COOKIE_SALT )));
		if( $salt === self::COOKIE_SALT ){
			return true;
		}
		return false;
	}
}
