<?php
namespace App\Helpers;

use Slim\Http\Request;
use HTR\Common\ValidateAuthentication;
use App\Exceptions\ExpiredUserException;
use App\Exceptions\InvalidUserException;

class AuthenticationHelper
{

    /**
     * Check the validate of authenticate User
     * @param Request $request
     * @return boolean
     * @throws InvalidUserException
     * @throws ExpiredUserException
     */
    public static function isValidLogin(Request $request)
    {
        $token = ValidateAuthentication::token($request);

        if (!$token) {
            throw new InvalidUserException("Invalid User");
        }
        
        if (isset($token->exp) && $token->exp < time()) {
            throw new ExpiredUserException("User with expired authentication");
        }

        return true;
    }
}
