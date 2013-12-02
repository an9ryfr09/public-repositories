<?php
/**
 * @desc 加密处理模块
 * @author an9ryfr09 <an9ryfr09@gmail.com>
 * @package LibMcrypt.php
 * @version v1.0
 */
class Mcrypt{

	/**
	 * @const string PRIVATE_KEY 私钥
	 * @const integer 加密算法
	 * @const integer 加密模式
	 */
	const PRIVATE_KEY = 'ylmf-pri';
	const CIPHER = MCRYPT_DES;
	const MODE = MCRYPT_MODE_ECB;

	/**
	 * @param string $_vactor 密钥向量
	 */
	private static $_vactor;


	/**
	 * @desc 默认命中方法
	 * @param string $name 方法名称
	 * @param array $arguments 参数列表
	 * @return callback
	 */
	public static function __callStatic($name, $arguments) {
		self::init();
		return call_user_func( array('self', '_' . $name), $arguments );
	}

	/**
	 * @desc 初始化
	 */
	public static function init(){
		self::$_vactor = mcrypt_create_iv( mcrypt_get_iv_size( self::CIPHER, self::MODE ), MCRYPT_DEV_URANDOM) ;
	}

	/**
	 * @desc 加密
	 * @param string $arg 加密的字符串
	 * @return string $string 加密后的字符串
	 */
	private static function _encrypt( $arg ){
		return mcrypt_encrypt( self::CIPHER, self::PRIVATE_KEY, $arg[0], self::MODE, self::$_vactor );
	}

	/**
	 * @desc string 解密
	 * @param string $arg 要解密的字符串参数
	 * @return string 解密后的字符串
	 */
	private static function _decrypt( $arg ){
		return rtrim(mcrypt_decrypt( self::CIPHER, self::PRIVATE_KEY, $arg[0], self::MODE, self::$_vactor), "\x00..\x1F");
	}
}
