<?php
/*
 * cookie example
 * ----------------------------------------
 */
include './LibCookie.php';
$cookie = new Cookie( '/', '.angryfrog.com', 3600 );
$cookie->set('example', 'test_cookie');
echo $cookie->get('example');


/*
 * validate example
 * ----------------------------------------
 */
include './LibValidate.php';
$validate = new Validate( $s, true );
echo $validate->checkEmpty()
			->checkEmail()
			->checkBothDifference( array($s2, $s3) )
			->checkLength( $s2, 1, 4)
			->getResult( YlmfValidate::MSG_FORMAT_JSON );


/*
 * Filter example
 * ----------------------------------------
 */
include './LibFilter.php';
$str = '</br>1，23232,3\'<a href="111">ssdsd</a>';
$filter = new Filter( $str );
echo $filter->filterClientData()
		->getResults();
