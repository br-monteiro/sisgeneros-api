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
            $processedValues = self::buildOffsetByPage($request, $allResults);
            $stdResult->limit = $processedValues['limit'];
            $stdResult->offset = $processedValues['offset'];
            $stdResult->page = $processedValues['page'];
            $stdResult->allResults = $allResults;

            return $stdResult;
        } catch (\Exception $ex) {
            throw new PaginatorException($ex->getMessage());
        }
    }

    /**
     * Build the 'offset' value based on 'page' value.
     * This method returns the values of 'limit', 'page' and 'offset'
     * @param Request $request
     * @param int $allResults
     * @return array
     */
    private static function buildOffsetByPage(Request $request, int $allResults = 0): array
    {
        $page = $request->getParam('page');
        $offset = $request->getParam('offset');
        $limit = $request->getParam('limit');

        $page = $page ? intval($page) : null;
        $offset = $offset ? intval($offset) : null;
        $limit = $limit ? intval($limit) : cfg::MAX_RESULTS;

        if ($page) {
            $lastPage = ceil($allResults / $limit);

            if ($page > $lastPage) {
                $page = $lastPage;
            }
            if ($page > 1) {
                $offset = ($page - 1) * $limit;
            }
            if ($page <= 1) {
                $offset = 0;
            }
        }

        return [
            'offset' => $offset,
            'limit' => $limit,
            'page' => $page
        ];
    }
}
