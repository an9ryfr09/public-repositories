<?php
abstract class Log{

	const FUNC_PREFIX = '_';

	protected static $_path;

	public static function __callStatic( $func, $args ){
		if( method_exists($this, $func) ){
			self::_init( $args );
			return call_user_func( array($this, self::FUNC_PREFIX . $func), $args );
		}
		else{
			return new ErrorException('not found method!');
		}
	}

	protected function _init( $root_path ){
		self::_setLogPath( $root_path );
	}

	protected static function _setLogPath( $root_path ){
		if( file_exists( $root_path ) && is_writable( $root_path ) ){
			self::$_path = $root_path;
		}
	}

	abstract protected static function _log1();

	abstract protected static function _log2();
}