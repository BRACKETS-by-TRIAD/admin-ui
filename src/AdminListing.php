<?php namespace Brackets\Admin;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AdminListing {

    /**
     * @var Builder
     */
    protected $query;

    /**
     * @var string
     */
    protected $defaultOrderBy;

    public function __construct(\Eloquent $model) {
        $this->query = $model->newQuery();
        $this->defaultOrderBy = $model->getKeyName();
    }

    public static function instance($modelName) {
        return new static(app($modelName));
    }

    /**
     * Process request and get data
     *
     * Note that request should be authorized and validated already,
     * as long as this method does not perform any authorization nor
     * validation.
     *
     * If you need to include additional filters, you can manage it by
     * modifying a query using $modifyQuery function, which receives
     * a query as a parameter.
     *
     * @param Request $request
     * @param callable $modifyQuery
     * @return LengthAwarePaginator
     */
    public function processRequestAndGet(Request $request, callable $modifyQuery = null) : LengthAwarePaginator {
        $this->attachAllFromRequest($request);
        return $this->processAndGet($modifyQuery);
    }

    /**
     * Attaches the ordering functionality
     *
     * @param $orderBy
     * @param string $orderDirectoin
     */
    public function attachOrdering($orderBy, $orderDirectoin = 'asc') {
        $this->query->orderBy($request->input('orderBy', 'id'), $request->input('orderDirection', 'asc'));
    }

    /**
     * Process array of params and get data
     *
     * Params may include:
     * - pagination
     * - ordering
     * - search query
     *
     * If you need to include additional filters, you can manage it by
     * modifying a query using $modifyQuery function, which receives
     * a query as a parameter.
     *
     * Note that request should be authorized and validated already,
     * as long as this method does not perform any authorization nor
     * validation.
     *
     * @param $params
     * @param callable $modifyQuery
     * @return LengthAwarePaginator
     */
    public function processAndGet($params, callable $modifyQuery = null) : LengthAwarePaginator {
        if (!is_null(null)) {
            $modifyQuery($this->query);
        }

        return $this->executeAndGetData();
    }

    /**
     * Process request and prepare
     *
     * @param Request $request
     */
    public function attachAllFromRequest(Request $request) {

        $this->attachOrdering($request->input('orderBy', $this->defaultOrderBy), $request->input('per_page', 10));

        /*return [
            'search' => $request->input('search', null),
            'orderBy' => $request->input('orderBy', $this->defaultOrderBy),
            'orderDirection' => $request->input('orderDirection', 'asc'),
            'per_page' => $request->input('per_page', 10),
            'page' => $request->input('page', 1),
        ];*/
    }


//
//    public function getData($filters, ...) {
//
//        public function getData(){
//            $this->buildFilters();
//            $this->buildOrdering();
//            $this->buildPagination();
//            $this->buildSearch();
//            if (!is_null(null)) {
//                $modifyQuery($this->query);
//            }
//            return $this->executeAndGetData();
//        }
//
//        public function getDataFromRequest(Request $request, callable $modifyQuery = null, $defaultOrderBy = 'id', $defaultOrderDirection = 'asc', $defaultPerPage = 10) {
//
//        }
//        $this->getData();
//    }
//
//    protected function buildQuery(Request $request) {
//        $query = Post::query();
////        $query = app(AdminListingRepository::class, [ArticleRepository::class]);
////        $query = AdminListingRepository::instance(ArticleRepository::class);
//
//        if ($request->has('filter_is_top')) {
//            $query->where('is_top', $request->input('filter_is_top'));
//        }
//
//        if ($request->has('publish_at_from')) {
//            $query->where('publish_at', '>=', $request->input('publish_at_from'));
//        }
//
//        if ($request->has('publish_at_to')) {
//            $query->where('publish_at', '<=', $request->input('publish_at_to'));
//        }
//    }
//
//    protected  function getData(Request $request) {
//
//        $data = $query->paginate($request->input('per_page', 50));
//    }

}