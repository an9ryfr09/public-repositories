<?php

class Filter{

	const FILTER_BOTH_SPACE = 0;
	const FILTER_LFET_SPACE = 1;
	const FILTER_RIGHT_SPACE = 2;

	protected $_contents;

	public function __construct( $contents = null ){
		$this->_init( $contents );
	}

	protected function _init( $contents ){
		$this->_setContents( $contents );
	}

	protected function _setContents( $contents ){
		if( isset($contents) && !empty( $contents )){
			$this->_contents = $contents;
		}
	}

	public function __call( $func, $args ){
		$this->_op( $func, $args );
	}

	private function _recursive( $array ){
		return array_pop( $array );
	}

	protected function op( ){
		if( is_array( $this->_contents ) ){
			$element = $this->_recursive( $this->_contents );
			$this->__FUNCTION__( $element );
		}
		else{
			call_user_func( $func, $args );;
		}
	}

	public function getContents( $contents = null ){
		if( isset( $contents ) && !empty( $contents ))
			$this->_setContents( $contents );{
		}
		return $this->_contents;
	}

	public function filterChar( $ch = ' ', $type = self::FILTER_BOTH_SPACE, $contents = null ){
		switch( $type ){
			case self::FILTER_BOTH_SPACE:
				$this->_contents = trim( $this->getContents($contents), $ch );
				break;
			case self::FILTER_LFET_SPACE:
				$this->_contents = ltrim( $this->getContents($contents), $ch );
				break;
			case self::FILTER_RIGHT_SPACE:
				$this->_contents = rtrim( $this->getContents($contents), $ch );
				break;
		}
		return $this;
	}

	public function getResults(){
		return $this->_contents;
	}
}
