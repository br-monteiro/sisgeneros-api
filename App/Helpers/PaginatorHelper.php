<?php
namespace App\Helpers;

use HTR\Database\EntityAbstract as db;
use Slim\Http\Request;
use App\System\Configuration as cfg;
use Doctrine\DBAL\DBALException;

class PaginatorHelper
{

    /**
     * Build the attributes necessary to paginate the query
     * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
     * @param Request $request
     * @param string $entityName
     * @param string $field
     * @return \stdClass
     */
    public static function buildAttributes(Request $request, string $entityName, string $field = 'id'): \stdClass
    {
        $stdResult = new \stdClass();
        $limit = $request->getParam('limit');
        $offset = $request->getParam('offset');
        $stdResult->limit = $limit ? intval($limit) : cfg::MAX_RESULTS;
        $stdResult->offset = $offset ? intval($offset) : null;

        try {
            $allResults = db::em()->getConnection()->query("SELECT {$field} FROM {$entityName}")->rowCount();
        } catch (DBALException $ex) {
            $error = $ex->getMessage();
            $allResults = 0;
        }

        $stdResult->hasError = isset($error);
        $stdResult->error = $error ?? '';
        $stdResult->allResults = $allResults;

        return $stdResult;
    }
}
