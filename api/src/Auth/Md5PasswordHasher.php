<?php
namespace App\Auth;

use Cake\Auth\AbstractPasswordHasher;
use Cake\Utility\Security;

class Md5PasswordHasher extends AbstractPasswordHasher
{

    public function hash($password)
    {
        return Security::hash($password, 'md5', Security::getSalt());
    }

    public function check($password, $hashedPassword)
    {
        return Security::hash($password, 'md5', Security::getSalt()) === $hashedPassword;
    }
}