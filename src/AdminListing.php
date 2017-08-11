<?php namespace Brackets\Admin;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\Translatable\HasTranslations;

class AdminListing {

    /**
     * @var Model|HasTranslations
     */
    protected $model;

    /**
     * @var Builder
     */
    protected $query;

    /**
     * @var int
     */
    protected $currentPage;

    /**
     * @var int
     */
    protected $perPage;

    /**
     * @var string
     */
    protected $pageColumnName = 'page';

    public function __construct(Model $model) {
        $this->model = $model;

        if (in_array(HasTranslations::class, class_uses($this->model))) {
            $this->modelHasTranslations = true;
            $this->locale = $this->model->locale ?: app()->getLocale();
        }

        $this->query = $model->newQuery();
    }

    public static function instance($modelName) {
        return new static(app($modelName));
    }

    /**
     * Process request and get data
     *
     * You should always specify an array of columns that are about to be queried
     *
     * You should specify columns which should be searched
     *
     * If you need to include additional filters, you can manage it by
     * modifying a query using $modifyQuery function, which receives
     * a query as a parameter.
     *
     * Note that request should be authorized and validated already,
     * as long as this method does not perform any authorization nor
     * validation.
     *
     * @param Request $request
     * @param array $columns
     * @param array $searchIn array of columns which should be searched in (only text, character varying or primary key are allowed)
     * @param callable $modifyQuery
     * @return LengthAwarePaginator
     */
    public function processRequestAndGet(Request $request, array $columns = ['*'], $searchIn = null, callable $modifyQuery = null) : LengthAwarePaginator {
        // process all the basic stuff
        $this->attachAllFromRequest($request, $searchIn);

        // add custom modifications
        if (!is_null($modifyQuery)) {
            $this->modifyQuery($modifyQuery);
        }

        // execute query and get the results
        return $this->execute($columns);
    }

    /**
     * Attach ordering, search and pagination
     *
     * After calling this method, everything is prepared for a typical scenario
     * and you are ready to attach custom filters or execute a query for your own.
     *
     * @param Request $request
     * @param array $searchIn array of columns which should be searched in (only text, character varying or primary key are allowed)
     */
    public function attachAllFromRequest(Request $request, $searchIn = ['id']) {

        $this->attachOrdering($request->input('orderBy', $this->model->getKeyName()), $request->input('orderDirection', 'asc'));
        $this->attachSearch($request->input('search', null), $searchIn);
        $this->attachPagination($request->input('page', 1), $request->input('per_page', 10));

    }

    /**
     * Attach the ordering functionality
     *
     * @param $orderBy
     * @param string $orderDirection
     */
    public function attachOrdering($orderBy, $orderDirection = 'asc') {
        $this->query->orderBy($orderBy, $orderDirection);
    }


    /**
     * Attach the searching functionality
     *
     * @param $search
     * @param array $searchIn array of columns which should be searched in (only text, character varying or primary key are allowed)
     */
    public function attachSearch($search, array $searchIn) {

        // when passed null, search is disabled
        if (is_null($searchIn)) {
            return ;
        }

        // if empty string, then we don't search at all
        $search = trim((string) $search);
        if ($search == '') {
            return ;
        }

        $tokens = collect(explode(' ', $search));

        $searchIn = collect($searchIn);

        // FIXME there is an issue, if you pass primary key as the only column to search in, it may not work properly

        $tokens->map(function($token) use ($searchIn) {
            $this->query->where(function(Builder $query) use ($token, $searchIn) {
                $searchIn->map(function($column) use ($token, $query) {
                    if ($this->model->getKeyName() == $column) {
                        if (is_numeric($token) && $token === strval(intval($token))) {
                            $query->orWhere($this->model->getKeyName(), intval($token));
                        }
                    } else {
                        $query->orWhere($column, 'like', '%'.$token.'%');
                    }
                });
            });
        });
    }

    /**
     * Attach the pagination functionality
     *
     * @param $currentPage
     * @param int $perPage
     */
    public function attachPagination($currentPage, $perPage = 10) {
        $this->currentPage = $currentPage;
        $this->perPage = $perPage;
    }

    /**
     * This is alias for modifyQuery()
     *
     * @param callable $modifyQuery
     */
    public function attachFilters(callable $modifyQuery) {
        $this->modifyQuery($modifyQuery);
    }


    /**
     * Modify built query in any way
     *
     * @param callable $modifyQuery
     */
    public function modifyQuery(callable $modifyQuery) {
        $modifyQuery($this->query);
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
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function get(array $columns = ['*']) {
        $columns = collect($columns)->map(function($column) {
            return $this->parseFullColumnName($column);
        });

        if ($this->hasPagination) {
            $result = $this->query->paginate($this->perPage, $this->materializeColumns($columns), $this->pageColumnName, $this->currentPage);
            $this->processResultCollection($result->getCollection());
        } else {
            $result = $this->query->get($this->materializeColumns($columns));
            $this->processResultCollection($result);
        }

        return $result;
    }

    protected function processResultCollection(Collection $collection) {
        // TODO what do we do with this? we need Spatie to update their package
//        if ($this->modelHasTranslations()) {
//            // we need to set this default locale ad hoc
//            $collection->each(function ($model) {
//                $model->locale = $this->locale;
//            });
//        }
    }

    protected function parseFullColumnName($column) {
        if (str_contains($column, '.')) {
            list($table, $column) = explode('.', $column, 2);
        } else {
            $table = $this->model->getTable();
        }

        return compact('table', 'column');
    }

    protected function modelHasTranslations() {
        return $this->modelHasTranslations;
    }


    private function materializeColumns(Collection $columns)
    {
        return $columns->map(function ($column) {
            return $column['table'] . '.' . $column['column'];
        })->toArray();
    }

    public function execute(array $columns = ['*']) : LengthAwarePaginator {
        return $this->query->paginate($this->perPage, $columns, $this->pageColumnName, $this->currentPage);
    }

}