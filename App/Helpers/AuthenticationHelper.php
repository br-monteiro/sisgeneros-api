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

    /**
     * Returns the profile default from user token
     * @param Request $request
     * @return \stdClass
     */
    public static function getUserProfile(Request $request): \stdClass
    {
        $data = ValidateAuthentication::token($request);
        $profiles = $data->data->profiles ?? [];

        foreach ($profiles as $value) {
            if (isset($data->data->id, $value->default) && $value->default == 'yes') {
                $temp = $value;
                // add the user ID on return
                $temp->id = $data->data->id;
                // remove unnecessary fileds
                unset($temp->default, $temp->militaryOrganizationNavalIndicative);
                return $temp;
            }
        }

        return new \stdClass();
    }
}
