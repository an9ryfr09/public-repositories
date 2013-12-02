<?php
/**
 * @desc 数据校检模块
 * @author an9ryfr09 <an9ryfr09@gmail.com>
 * @package LibValidate.php
 * @version v1.0
 */
class Validate{

	/**
	 * const string MSG_IS_EMPTY
	 * const string MSG_BOTH_DIFFERENCE
	 * const string MSG_LENGTH_FAIL
	 * const string MSG_IS_NOT_EMAIL
	 * const string MSG_IS_NOT_NUMERIC
	 * const string MSG_VALUE_EXISTS
	 * const integer MSG_FORMAT_ARRAY
	 * const integer MSG_FORMAT_JSON
	 * const integer MSG_FORMAT_STRING
	 * protected string $_value
	 * protected string $_callback
	 * protected string $_need_callback
	 */
	const MSG_NOT_CALLABLE = '不是合法的回调函数';
	const MSG_IS_EMPTY = '不能为空';
	const MSG_BOTH_DIFFERENCE = '两个字符串不相等';
	const MSG_LENGTH_ERR = '长度不正确';
	const MSG_IS_NOT_EMAIL = 'email格式不正确';
	const MSG_IS_NOT_NUMERIC = '不是数字';
	const MSG_VALUE_EXISTS = '值已存在';

	const MSG_FORMAT_ARRAY = 0;
	const MSG_FORMAT_JSON = 1;
	const MSG_FORMAT_STRING = 2;

	protected $_value, $_callback, $_need_callback, $_msg;


	public function __construct( $value = null, $need_callback = false, $func = '' ){
		return $this->_init( $value, $need_callback, $func );
	}

	/**
	 * @desc 初始化各项参数
	 * @param string $value
	 * @param string $func
	 * @return $this
	 */
	protected function _init( $value = null, $need_callback = false, $func = null ){

		$this->_setValue( $value );
		$this->_setNeedCallBack( $need_callback );

		if( $this->_needCallback() ){
			$this->setCallback( $func );
		}

		return $this;
	}

	protected function _setValue( $value = null ){
		if( isset( $value ) ){
			$this->_value = $value;
		}
	}

	/**
	 * @desc 获取或设置当前检查的值
	 * @param string | integer | array | obj $value
	 * @return string | integer | array | obj
	 */
	public function getValue( $value = null ){
		if( isset( $value ) ){
			$this->_setValue( $value );
		}
		return $this->_value;
	}

	protected function _setNeedCallBack( $need_callback = false ){
		if( isset( $need_callback ) ){
			$this->_need_callback = $need_callback;
		}
	}

	protected function _needCallback(){
		return $this->_need_callback;
	}

	protected function _functionExists( $func = null ){
		if( isset( $func ) && is_array( $func )){
			return method_exists( $func[0], $func[1]);
		}
		else{
			return function_exists( $func );
		}
	}

	protected function _isCallback( $func = null ){
		if( $this->_functionExists( $func ) || is_callable( $func ) ){
			return true;
		}
		return false;
	}

	protected function _defaultCallBack( $msg ){
		$this->_msg['msg'][] = $msg;
	}

	protected function _canCallback(){
		if( $this->_isCallback( $this->_callback ) && $this->_needCallback() ){
			return true;
		}
		return false;
	}

	public function setCallback( $func = null ){
		if( $this->_isCallback( $func ) ){
			$this->_callback = $func;
		}
		else{
			$this->_callback = array( $this, '_defaultCallBack' );
		}
	}

	protected function _callYourSelFunc( $result = false, $args = null ){
		if( $this->_canCallback() ){
			if( !$result ){
				call_user_func( $this->_callback, $args );
			}
			return $this;
		}
		else{
			if( $result ){
				return $this;
			}
			die('false');
		}
	}

	protected function _replaceMsg( $msg = '', $replace_msg = '' ){
		if( $this->_isEmpty( $msg ) ){
			return $replace_msg;
		}
		return $msg;
	}

	protected function _isEmpty( $value = '' ){
		if(!isset( $value ) || empty( $value ) || $value === '' || $value === null){
			return true;
		}
		return false;
	}

	protected function _toString(){
		return implode( ',', $this->_msg['msg'] );
	}

	protected function _toJson(){
		return json_encode( $this->_msg, JSON_UNESCAPED_UNICODE );
	}

	/**
	 * @desc 获取消息列表
	 * @param enum $type
	 * @return string | array | json
	 */
	public function getResult( $type = self::MSG_FORMAT_ARRAY ){

		if( !isset( $this->_msg ) ){
			$this->_msg['msg'] = array('succ');
		}

		switch( $type ){
			case self::MSG_FORMAT_ARRAY:
				return $this->_msg;
				break;
			case self::MSG_FORMAT_JSON:
				return $this->_toJson();
				break;
			case self::MSG_FORMAT_STRING:
				return $this->_toString();
			default:
				return $this->_msg;
				break;
		}
	}

	/**
	 * @desc 是否为空
	 * @param string $value
	 * @param string $msg
	 * @return $this | boolean
	 */
	public function checkEmpty( $value = null, $msg = '' ){
		return $this->_callYourSelFunc( $this->_isEmpty( $this->getValue( $value ) ) ? false : true,
										$this->_replaceMsg( self::MSG_IS_EMPTY, $msg )
				);
	}

	/**
	 * @desc 是否是数字
	 * @param integer $value
	 * @param string $msg
	 * @return $this | boolean
	 */
	public function checkNumeric( $value = null, $msg = '' ){
		return $this->_callYourSelFunc( is_numeric( $this->getValue( $value ) ) ? true : false,
										$this->_replaceMsg( self::MSG_IS_NOT_NUMERIC, $msg )
				);
	}

	/**
	 * @desc 是否是email格式
	 * @param string $value
	 * @param string $msg
	 * @return $this | boolean
	 */
	public function checkEmail( $value = null, $msg = '' ){
		return $this->_callYourSelFunc( preg_match('/.+?@.+?\..+/im', $this->getValue( $value )) ? true : false,
										$this->_replaceMsg( self::MSG_IS_NOT_EMAIL, $msg )
				);
	}

	protected function _isSame( $value ){
		$this->getValue( $value );
		return strcmp( $this->_value[0], $this->_value[1] ) === 0 ? true : false;
	}

	/**
	 * @desc 两个字符串是否相等
	 * @param array $value
	 * @param string $msg
	 * @return $this | boolean
	 */
	public function checkBothDifference( $value = array(), $msg = ''){
		return $this->_callYourSelFunc( $this->_isSame( $value ),
										$this->_replaceMsg( self::MSG_BOTH_DIFFERENCE, $msg )
				);
	}

	/**
	 * @desc 检查长度
	 * @param string $value
	 * @param integer $min
	 * @param integer $max
	 * @param string $msg
	 * @return $this | boolean
	 */
	public function checkLength( $value = null, $min = 0, $max = 0, $msg = '' ){

		if( $this->checkEmpty( $this->getValue( $value ) ) && $this->checkNumeric( $min ) && $this->checkNumeric( $max )){
			if( $min > 0 && $max > 0 ){
				$this->_callYourSelFunc( (strlen( $this->getValue( $value ) ) < $min || strlen( $this->getValue( $value ) ) > $max) ? false : true,
										$this->_replaceMsg( self::MSG_LENGTH_ERR, $msg )
				);
			}
		}
		return $this;
	}


}