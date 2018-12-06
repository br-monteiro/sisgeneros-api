<?php
namespace App\Helpers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Helpers\AuthenticationHelper;
use App\Exceptions\AccessDeniedException;

class AccessHelper
{

    /**
     * Verify if the user profile is allowed to access the resource
     * @param Request $request The request
     * @param array $allowedLevels Array with the access levels
     * @param bool $throwException Check if the Exception is to be thrown. By default throw an AccessDeniedException.
     * @return bool
     * @throws AccessDeniedException
     */
    public static function check(Request $request, array $allowedLevels, bool $throwException = true): bool
    {
        $userProfile = AuthenticationHelper::getUserProfile($request);
        $isAllowed = in_array($userProfile->profile, $allowedLevels);

        if (!$isAllowed && $throwException) {
            throw new AccessDeniedException('Access Denied for this user profile');
        }

        return $isAllowed;
    }

    /**
     * Config the message returned when AccessDeniedException is thrown.
     * @param Response $response
     * @param AccessDeniedException $ex
     * @return Response
     */
    public static function message(Response $response, AccessDeniedException $ex): Response
    {
        return $response->withJson([
                "message" => $ex->getMessage(),
                "status" => "error"
                ], 400);
    }
}
