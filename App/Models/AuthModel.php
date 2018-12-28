<?php
namespace App\Models;

use Slim\Http\Response;
use Slim\Http\Request;
use HTR\Common\Json;
use HTR\Database\AbstractModel;
use HTR\Database\EntityAbstract as db;
use HTR\Common\Authenticator;
use App\System\Configuration as cfg;
use App\Entities\Auth;
use Doctrine\ORM\ORMException;

class AuthModel extends AbstractModel
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function login(Request $request, Response $response): Response
    {
        $data = (object) $request->getParsedBody();

        if (!self::inputValidate($data, 'auth_schema.json')) {
            return $response->withJson([
                    "message" => "There are wrong fields in submission",
                    "status" => "error",
                    "error" => Json::getValidateErrors()
                    ], 400);
        }

        try {

            $repository = db::em()->getRepository(Auth::class);
            $entity = $repository->findOneBy(["username" => md5($data->username)]);

            if (
                !$entity ||
                !password_verify($data->password . cfg::SALT_KEY, $entity->getPassword()) ||
                $entity->getUsers()->getActive() == "no"
            ) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Invalid User",
                            "status" => "error",
                            "t" => $entity->getUsers()->getActive()
                            ], 401);
            }

            $user = $entity->getUsers();

            $profile = self::getAllProfiles($user->getId());

            $userData = [
                'expiration_sec' => cfg::EXPIRATE_TOKEN,
                'host' => cfg::htrFileConfigs()->devmode ? cfg::HOST_DEV : cfg::HOST_PRD,
                'userdata' => [
                    'id' => $user->getId(),
                    'profiles' => $profile
                ]
            ];

            return $response->withJson([
                    "message" => "User Authorized",
                    "status" => "success",
                    "data" => [
                        "userId" => $user->getId(),
                        "userName" => $user->getName(),
                        "userMilitaryPost" => $user->getMilitaryPost(),
                        "userProfile" => $profile,
                        "token" => Authenticator::generateToken($userData)
                    ]
                    ], 200);
        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
        }
    }

    /**
     * Return all profiles of User
     * @param int $userId
     * @return array
     */
    private static function getAllProfiles(int $userId): array
    {
        try {
            $query = ""
                . "SELECT "
                . "mo.id AS militaryOrganizationId, mo.naval_indicative AS militaryOrganizationNavalIndicative, "
                . "uhmo.profile, uhmo.default "
                . "FROM users_has_military_organizations AS uhmo "
                . "INNER JOIN military_organizations AS mo ON mo.id = uhmo.military_organizations_id "
                . "WHERE uhmo.users_id = ?";
            $stmt = db::em()->getConnection()->prepare($query);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $ex) {
            return [];
        }
    }
}
