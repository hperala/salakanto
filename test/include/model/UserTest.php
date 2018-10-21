<?php
require_once 'include/model/User.php';

class UserTest extends PHPUnit_Framework_TestCase
{
    function testCorrectPassword() {
        $password = 'mypassword';
        $hash = User::getHash($password);

        $this->assertTrue(User::verifyPassword($password, $hash));
    }

    function testIncorrectPassword() {
        $password = 'mypassword';
        $hash = User::getHash($password);

        $this->assertFalse(User::verifyPassword('anotherpassword', $hash));
    }
}