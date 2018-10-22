<?php
namespace App\Models;

use HTR\Database\EntityAbstract as db;
use HTR\Database\AbstractModel;
use HTR\Common\Json;
use Slim\Http\Response;
use Slim\Http\Request;
use Doctrine\ORM\ORMException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use App\Helpers\PaginatorHelper as paginator;
use App\Exceptions\PaginatorException;
use App\Entities\StockControls;
use App\Exceptions\DoubleRegistrationException;
use App\Entities\MilitaryOrganizations;
use App\Entities\Users;
use App\Entities\StockControlsItems;

class StockControlsModel extends AbstractModel
{

    /**
     * Returns all register
     * @param Request $request The Resquest Object
     * @param Response $response The Response Object
     * @return Response
     */
    public static function findAll(Request $request, Response $response): Response
    {
        try {

            $paginator = paginator::buildAttributes($request, 'stock_controls');
            $limit = $paginator->limit;
            $offset = $paginator->offset;
            $repository = db::em()->getRepository(StockControls::class);
            $entity = $repository->findBy([], null, $limit, $offset);

            return $response->withJson([
                    "message" => "",
                    "status" => "success",
                    "allResults" => $paginator->allResults,
                    "limit" => $limit,
                    "offset" => $offset,
                    "data" => self::outputValidate($entity)
                        ->withAttribute(self::buildCallbacks(), null, true)
                        ->run()
                    ], 200);
        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
        } catch (PaginatorException $ex) {
            return self::commonError($response, $ex);
        }
    }

    /**
     * Return one register by ID
     * @param int $id The identify value
     * @param Response $response The Response Object
     * @return Response
     */
    public static function find(int $id, Response $response): Response
    {
        try {
            $repository = db::em()->getRepository(StockControls::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Stock not found",
                            "status" => "error"
                            ], 404);
            }

            return $response->withJson([
                    "message" => "",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->withAttribute(self::buildCallbacks())
                        ->run()
                    ], 200);
        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
        }
    }

    /**
     * Register new value
     * @param Request $request The Resquest Object
     * @param Response $response The Response Object
     * @return Response
     */
    public static function create(Request $request, Response $response): Response
    {
        $data = json_decode($request->getBody()->getContents() ?? []);

        if (!self::inputValidate($data, 'stock_controls_schema.json')) {
            return $response->withJson([
                    "message" => "There are wrong fields in submission",
                    "status" => "error",
                    "error" => Json::getValidateErrors()
                    ], 400);
        }

        db::em()->getConnection()->beginTransaction();

        try {

            self::checkDoubleRegistration($data);

            $userRepository = db::em()->getRepository(Users::class);
            $requesterUser = $userRepository->find($data->requesterUser);
            $authorizerUser = $requesterUser;
            $receiverUser = $requesterUser;

            $militaryOrganizationRepository = db::em()->getRepository(MilitaryOrganizations::class);
            $militaryOrganizations = $militaryOrganizationRepository->find($data->militaryOrganizationsId);
            $militaryOrganizationsOrigin = $militaryOrganizationRepository->find($data->militaryOrganizationsOrigin);
            $militaryOrganizationsDestiny = $militaryOrganizationRepository->find($data->militaryOrganizationsDestiny);

            $date = new \DateTime('now');

            $number = self::buildNumberStockControl($data->militaryOrganizationsId);

            $entity = new StockControls();
            $entity->setMilitaryOrganizations($militaryOrganizations);
            $entity->setMilitaryOrganizationsOrigin($militaryOrganizationsOrigin);
            $entity->setMilitaryOrganizationsDestiny($militaryOrganizationsDestiny);
            $entity->setRequesterUser($requesterUser);
            $entity->setAuthorizerUser($authorizerUser);
            $entity->setReceiverUser($receiverUser);
            $entity->setNumber($number);
            $entity->setStatus('created');
            $entity->setStockType($data->stockType);
            $entity->setFiscalDocument('nodoc' . $number);
            $entity->setTransactionType($data->transactionType);
            $entity->setCreatedAt($date);
            $entity->setUpdatedAt($date);
            $entity->setObservations($data->observations ?? null);

            db::em()->persist($entity);
            // flush transaction
            db::em()->flush();

            foreach ($data->items as $item) {
                $stockControlsItems = new StockControlsItems();
                $stockControlsItems->setName($item->name);
                $stockControlsItems->setQuantity($item->quantity);
                $stockControlsItems->setSupplyUnit($item->supplyUnit);
                $stockControlsItems->setPiIdentifier($item->piIdentifier ?? null);
                $stockControlsItems->setStockControls($entity);
                ///
                db::em()->persist($stockControlsItems);
                // flush transaction
                db::em()->flush();
            }

            // commit transaction
            db::em()->getConnection()->commit();

            return $response->withJson([
                    "message" => "Registry created successfully",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->withAttribute(self::buildCallbacks())
                        ->run()
                    ], 201);
        } catch (ORMException $ex) {
            db::em()->getConnection()->rollBack();
            return self::commonError($response, $ex);
        } catch (UniqueConstraintViolationException $ex) {
            db::em()->getConnection()->rollBack();
            return $response->withJson([
                    "message" => $ex->getPrevious()->getMessage(),
                    "status" => "warning"
                    ], 400);
        } catch (DoubleRegistrationException $ex) {
            db::em()->getConnection()->rollBack();
            return $response->withJson([
                    "message" => $ex->getMessage(),
                    "status" => "error"
                    ], 400);
        }
    }

    /**
     * Update one register by ID
     * @param int $id
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public static function update(int $id, Request $request, Response $response): Response
    {
        $data = (object) $request->getParsedBody() ?? [];

        try {
            $repository = db::em()->getRepository(StockControls::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Stock not found",
                            "status" => "error"
                            ], 404);
            }

            if (!self::inputValidate($data, 'stock_controls_update_schema.json')) {
                return $response->withJson([
                        "message" => "There are wrong fields in submission",
                        "status" => "error",
                        "error" => Json::getValidateErrors()
                        ], 400);
            }

            $userRepository = db::em()->getRepository(Users::class);
            $authorizerUser = $userRepository->find($data->authorizerUser);
            $receiverUser = $userRepository->find($data->receiverUser);

            $militaryOrganizationRepository = db::em()->getRepository(MilitaryOrganizations::class);
            $militaryOrganizationsOrigin = $militaryOrganizationRepository->find($data->militaryOrganizationsOrigin);
            $militaryOrganizationsDestiny = $militaryOrganizationRepository->find($data->militaryOrganizationsDestiny);

            $entity->setMilitaryOrganizationsOrigin($militaryOrganizationsOrigin);
            $entity->setMilitaryOrganizationsDestiny($militaryOrganizationsDestiny);
            $entity->setAuthorizerUser($authorizerUser);
            $entity->setReceiverUser($receiverUser);
            $entity->setStatus($data->status);
            $entity->setFiscalDocument($data->fiscalDocument ?? $entity->getFiscalDocument());
            $entity->setUpdatedAt(new \DateTime('now'));
            $entity->setObservations($data->observations ?? $entity->getObservations());

            db::em()->flush();

            return $response->withJson([
                    "message" => "Registry updated successfully",
                    "status" => "success",
                    "data" => self::outputValidate($entity)
                        ->withAttribute(self::buildCallbacks())
                        ->run()
                    ], 200);
        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
        } catch (UniqueConstraintViolationException $ex) {
            return $response->withJson([
                    "message" => $ex->getPrevious()->getMessage(),
                    "status" => "warning"
                    ], 400);
        }
    }

    /**
     * Remove one register by ID
     * @param int $id
     * @param Response $response
     * @return Response
     */
    public static function remove(int $id, Response $response): Response
    {
        try {
            $repository = db::em()->getRepository(StockControls::class);
            $entity = $repository->find($id);

            if (!$entity) {
                // no have results
                return $response
                        ->withJson([
                            "message" => "Stock not found",
                            "status" => "error"
                            ], 404);
            }

            db::em()->remove($entity);
            db::em()->flush();

            return $response->withJson("", 204);
        } catch (ORMException $ex) {
            return self::commonError($response, $ex);
        }
    }

    /**
     * Build the callbacks of results
     * @return array
     */
    private static function buildCallbacks(): array
    {
        return [
            'createdAt' => function ($e) {
                return $e->getCreatedAt()->format('Y-m-d');
            },
            'updatedAt' => function ($e) {
                return $e->getUpdatedAt()->format('Y-m-d');
            },
            'militaryOrganizations' => function ($e) {
                return $e->getMilitaryOrganizations()->getName();
            },
            'militaryOrganizationsOrigin' => function ($e) {
                return $e->getMilitaryOrganizationsOrigin()->getName();
            },
            'militaryOrganizationsDestiny' => function ($e) {
                return $e->getMilitaryOrganizationsDestiny()->getName();
            },
            'receiverUser' => function ($e) {
                if ($e->getStatus() == 'delivered') {
                    return $e->getReceiverUser()->getFullName();
                }
                return '';
            },
            'authorizerUser' => function ($e) {
                if ($e->getStatus() == 'authorized') {
                    return $e->getAuthorizerUser()->getFullName();
                }
                return '';
            },
            'requesterUser' => function ($e) {
                return $e->getRequesterUser()->getFullName();
            },
            'fiscalDocument' => function ($e) {
                if (preg_match("/^nodoc\d+$/", $e->getFiscalDocument())) {
                    return '';
                }
                return $e->getFiscalDocument();
            }
        ];
    }

    private static function buildNumberStockControl($militaryOrganizationsId): int
    {
        $prefix = date('y');
        $militaryOrganizationsId = $militaryOrganizationsId ?? 0;
        $totalResuts = db::em()
            ->getConnection()
            ->query(""
                . "SELECT id "
                . "FROM stock_controls "
                . "WHERE "
                . "military_organizations_id = {$militaryOrganizationsId} "
                . "AND YEAR(created_at) = " . date('Y'))
            ->rowCount();
        return (int) $prefix . ($totalResuts + 1);
    }

    /**
     * Checks if there is a record with the same data
     * @param \stdClass $data
     * @throws DoubleRegistrationException
     */
    private static function checkDoubleRegistration(\stdClass $data, int $id = 0)
    {
        $queryPart = self::buildPartSqlQuery($data);
        $query = ""
            . "SELECT "
            . "    sc.id "
            . "FROM "
            . "    stock_controls AS sc "
            . "INNER JOIN stock_controls_items AS sci ON sci.stock_controls_id = sc.id "
            . "WHERE "
            . "    sc.requester_user = :ru "
            . "    AND sc.authorizer_user = :ru "
            . "    AND sc.receiver_user = :ru "
            . "    AND sc.status = 'created' "
            . "    AND sc.military_organizations_id = :moi "
            . "    AND sc.military_organizations_origin = :moo "
            . "    AND sc.military_organizations_destiny = :mod "
            . $queryPart['query'];
        $time = time();
        $param = [
            ":ru" => $data->requesterUser ?? $time,
            ":moi" => $data->militaryOrganizationsId ?? $time,
            ":moo" => $data->militaryOrganizationsOrigin ?? $time,
            ":mod" => $data->militaryOrganizationsDestiny ?? $time,
        ];

        $param = array_merge($param, $queryPart['bindValue']);

        if ($id) {
            $query .= " AND b.id != :id";
            $param[':id'] = $id;
        }

        $stmt = db::em()->getConnection()->prepare($query);
        $stmt->execute($param);

        if ($stmt->rowCount() > 0) {
            throw new DoubleRegistrationException("A record with this data already exists");
        }
    }

    private static function buildPartSqlQuery(\stdClass $data): array
    {
        $result = ['query', 'bindValue'];
        if (isset($data->items) && is_array($data->items)) {
            $queryItems = [];
            foreach ($data->items as $i => $value) {
                $result['bindValue'][':sci_name_' . $i] = $value->name;
                $result['bindValue'][':sci_quantity_' . $i] = $value->quantity;
                $result['bindValue'][':sci_supply_unit_' . $i] = $value->supplyUnit;
                $queryItems[] = ""
                    . " sci.name = :sci_name_" . $i . " "
                    . " AND sci.quantity = :sci_quantity_" . $i . " "
                    . " AND sci.supply_unit = :sci_supply_unit_" . $i . " ";
            }
            $result['query'] = " AND (" . implode(" OR ", $queryItems) . ") ";
        }
        return $result;
    }
}
