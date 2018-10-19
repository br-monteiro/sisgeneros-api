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
     * @throws HeaderWithoutAuthorizationException
     */
    public static function isValidLogin(Request $request)
    {
        $token = ValidateAuthentication::token($request);
        $currentHost = $request->getServerParam('HTTP_HOST');

        if (isset($token->iss) && $token->iss !== $currentHost) {
            throw new InvalidUserException("Host with access denied");
        }

        if (isset($token->exp) && $token->exp < time()) {
            throw new ExpiredUserException("User with expired authentication");
        }

        return true;
    }
}
