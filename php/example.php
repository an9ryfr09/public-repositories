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
$validate = new Validate( $s, true );

echo $validate->checkEmpty()
			->checkEmail()
			->checkBothDifference( array($s2, $s3) )
			->checkLength( $s2, 1, 4)
			->getResult( Validate::MSG_FORMAT_JSON );
