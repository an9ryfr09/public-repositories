<?php
/**
 * @desc 数据过滤模块
 * @author an9ryfr09 <an9ryfr09@gmail.com>
 * @package LibFilter.php
 * @version v1.0
 */
class Filter{

	/**
	 * @const int FILTER_BOTH_SPACE
	 * @const int FILTER_LFET_SPACE
	 * @const int FILTER_RIGHT_SPACE
	 */
	const FILTER_BOTH_SPACE = 0;
	const FILTER_LFET_SPACE = 1;
	const FILTER_RIGHT_SPACE = 2;

	/**
	 * $_contents string 要过滤的字符串
	 */
	protected $_contents;

	public function __construct( $contents = null ){
		$this->_init( $contents );
	}

	protected function _init( $contents ){
		$this->_setContents( $contents );
	}

	/**
	 * @desc 设置要过滤的字符串
	 * @param string $contents
	 * @return void
	 */
	protected function _setContents( $contents ){
			$this->_contents = $contents;
	}

	/**
	 * @desc 获取当前字符串
	 * @return string | null
	 */
	public function getContents(){
		if( isset( $this->_contents ) && !empty( $this->_contents )){
			return $this->_contents;
		}
		else{
			return null;
		}
	}

	/**
	 * @过滤前后空格
	 * @param enum(0, 1, 2) $type
	 * @return $this
	 */
	public function filterSpace( $type = self::FILTER_BOTH_SPACE ){

		switch( $type ){
			case self::FILTER_BOTH_SPACE:
				$this->_setContents( trim( $this->getContents() ) );
				break;
			case self::FILTER_LFET_SPACE:
				$this->_setContents( ltrim( $this->getContents() ) );
				break;
			case self::FILTER_RIGHT_SPACE:
				$this->_setContents( rtrim( $this->getContents() ) );
				break;
		}

		return $this;
	}

	/**
	 * @desc 过滤字符
	 * @param string | array $ch
	 * @return $this
	 */
	public function filterChar( $ch = ' ' ){

		if( is_array($ch) ){
			foreach( $ch as $c ){
				$this->_setContents( str_replace( $c, '', $this->getContents() ) );
			}
		}
		else{
			$this->_setContents( str_replace( $ch, '', $this->getContents() ) );
		}
		return $this;
	}

	/**
	 * @desc 过滤html标签
	 * @return $this
	 */
	public function filterHtmlTags(){
		$this->_setContents( strip_tags( $this->getContents() ) );
		return $this;
	}

	/**
	 * @desc 将html转换为特殊字符
	 * @return $this
	 */
	private function transforHtmlSpecialChars(){
		$this->_setContents( htmlspecialchars( $this->getContents() ) );
		return $this;
	}

	/**
	 * desc 将包含特殊字符的字符串转换为正常字符串
	 * @return $this
	 */
	public function transSpecialCharsHtml(){
		$this->_setContents( html_entity_decode( $this->getContents() ) );
		return $this;
	}

	/**
	 * @desc 为字符串/添加转义
	 * return $this
	 */
	private function addSlashesF(){
		$this->_setContents( addslashes( $this->getContents() ) );
		return $this;
	}

	/**
	 * @desc 过滤客户端数据
	 * @return $this
	 */
	public function filterClientData(){

		return $this->addSlashesF()
				->transforHtmlSpecialChars();
	}

	/**
	 * @desc 取得结果
	 * @return $_contents
	 */
	public function getResults(){
		return $this->getContents();
	}
}
