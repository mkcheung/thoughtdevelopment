<?php
/**
 * Created by PhpStorm.
 * User: marscheung
 * Date: 8/14/18
 * Time: 10:00 PM
 */
namespace App\Constants;

class ErrorMessages
{
    const INVALID_EMAIL_ADDRESS                 = 'Email Address cannot be empty';
    const INVALID_PASSWORD                      = 'Password cannot be empty';
    const INVALID_USERNAME_PASSWORD_COMBINATION = 'Invalid Username or Password';
    const ACCOUNT_NOT_ENABLED                   = 'Account is disabled';
    const ACCOUNT_EXISTS                        = 'Account already exists for the email address %s';
    const EMAIL_ADDRESS_IN_USE                  = 'Email Address is already in use.';
}