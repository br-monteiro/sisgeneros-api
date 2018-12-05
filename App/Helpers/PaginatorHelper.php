<?php
namespace App\Helpers;

use HTR\Database\EntityAbstract as db;
use Slim\Http\Request;
use App\System\Configuration as cfg;
use App\Exceptions\PaginatorException;

class PaginatorHelper
{

    /**
     * Build the attributes necessary to paginate the query
     * @author Edson B S Monteiro <bruno.monteirodg@gmail.com>
     * @param Request $request
     * @param string $entityName
     * @param string $field
     * @param string $options
     * @return \stdClass
     */
    public static function buildAttributes(Request $request, string $entityName, string $field = 'id', string $options = ''): \stdClass
    {
        try {
            $allResults = db::em()->getConnection()->query("SELECT {$field} FROM {$entityName} {$options}")->rowCount();

            $stdResult = new \stdClass();
            $limit = $request->getParam('limit');
            $offset = $request->getParam('offset');
            $stdResult->limit = $limit ? intval($limit) : cfg::MAX_RESULTS;
            $stdResult->offset = $offset ? intval($offset) : null;
            $stdResult->allResults = $allResults;

            return $stdResult;
        } catch (\Exception $ex) {
            throw new PaginatorException($ex->getMessage());
        }
    }
}
