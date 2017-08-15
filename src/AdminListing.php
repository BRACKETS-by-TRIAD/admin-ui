<?php namespace Brackets\Admin;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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

    /**
     * @var bool
     */
    protected $hasPagination = false;

    /**
     * @var bool
     */
    protected $modelHasTranslations = false;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @param $modelName
     * @return static
     */
    public static function create($modelName) {
        return (new static)->setModel($modelName);
    }

    /**
     * Set model admin listing works with
     *
     * Setting the model is required
     *
     * @param Model|string $model
     * @return $this
     * @throws NotAModelClassException
     */
    public function setModel($model) {

        if (is_string($model)) {
            $model = app($model);
        }

        if (!is_a($model, Model::class)) {
            throw new NotAModelClassException("AdminListing works only with Eloquent Models");
        }

        $this->model = $model;

        if (in_array(HasTranslations::class, class_uses($this->model))) {
            $this->modelHasTranslations = true;
            $this->locale = $this->model->locale ?: app()->getLocale();
        }

        $this->query = $model->newQuery();

        return $this;
    }

    /**
     * Process request and get data
     *
     * You should always specify an array of columns that are about to be queried
     *
     * You can specify columns which should be searched
     *
     *
     * If you need to include additional filters, you can manage it by
     * modifying a query using $modifyQuery function, which receives
     * a query as a parameter.
     *
     * If your model is Dimsav\Translatable\Translatable, translation will be automatically loaded. You can specify
     * locale which should be loaded. When filtering, searching and ordering you can use columns from Translatable
     * model as well.
     *
     * This method does not perform any authorization nor validation.
     *
     * @param Request $request
     * @param array $columns
     * @param array $searchIn array of columns which should be searched in (only text, character varying or primary key are allowed)
     * @param callable $modifyQuery
     * @param string $locale
     * @return LengthAwarePaginator|Collection The result is either LengthAwarePaginator (when pagination was attached) or simple Collection otherwise
     */
    public function processRequestAndGet(Request $request, array $columns = ['*'], $searchIn = null, callable $modifyQuery = null, $locale = null) {
        // process all the basic stuff
        $this->attachOrdering($request->input('orderBy', $this->model->getKeyName()), $request->input('orderDirection', 'asc'))
            ->attachSearch($request->input('search', null), $searchIn)
            ->attachPagination($request->input('page', 1), $request->input('per_page', 10));

        // add custom modifications
        if (!is_null($modifyQuery)) {
            $this->modifyQuery($modifyQuery);
        }

        if (!is_null($locale)) {
            $this->setLocale($locale);
        }

        // execute query and get the results
        return $this->get($columns);
    }

    /**
     * Set the locale you want to query
     *
     * This method is only valid for Translatable models
     *
     * @param $locale
     * @return $this
     */
    public function setLocale($locale) {
        $this->locale = $locale;
        return $this;
    }

    /**
     * Attach the ordering functionality
     *
     * Any repeated call to this method is going to have no effect and original ordering is going to be used.
     * This is due to the limitation of the Illuminate\Database\Eloquent\Builder.
     *
     * @param $orderBy
     * @param string $orderDirection
     * @return $this
     */
    public function attachOrdering($orderBy, $orderDirection = 'asc') {
        $orderBy = $this->parseFullColumnName($orderBy);
        $this->query->orderBy($orderBy['table'].'.'.$orderBy['column'], $orderDirection);

        return $this;
    }


    /**
     * Attach the searching functionality
     *
     * @param string $search searched string
     * @param array $searchIn array of columns which should be searched in (only text, character varying or primary key are allowed)
     * @return $this
     */
    public function attachSearch($search, array $searchIn) {

        // when passed null, search is disabled
        if (is_null($searchIn)) {
            return $this;
        }

        // if empty string, then we don't search at all
        $search = trim((string) $search);
        if ($search == '') {
            return $this;
        }

        $tokens = collect(explode(' ', $search));

        $searchIn = collect($searchIn)->map(function($column){
            return $this->parseFullColumnName($column);
        });

        // FIXME there is an issue, if you pass primary key as the only column to search in, it may not work properly

        $tokens->each(function($token) use ($searchIn) {
            $this->query->where(function(Builder $query) use ($token, $searchIn) {
                $searchIn->each(function($column) use ($token, $query) {
                    // FIXME try to find out how to customize this default behaviour
                    if ($this->model->getKeyName() == $column['column'] && $this->model->getTable() == $column['table']) {
                        if (is_numeric($token) && $token === strval(intval($token))) {
                            $query->orWhere($column['table'].'.'.$column['column'], intval($token));
                        }
                    } else {
                        // FIXME how to make this case insensitive when using different databases? in SQLite "like" is case-insensitive but in PostgreSQL we use there is a "ilike" operator.. so maybe we need to extract this operator and initialize it depending on a database driver
                        $query->orWhere($column['table'].".".$column['column'], 'like', '%'.$token.'%');
                    }
                });
            });
        });

        return $this;
    }

    /**
     * Attach the pagination functionality
     *
     * @param $currentPage
     * @param int $perPage
     * @return $this
     */
    public function attachPagination($currentPage, $perPage = 10) {
        $this->hasPagination = true;
        $this->currentPage = $currentPage;
        $this->perPage = $perPage;

        return $this;
    }


    /**
     * Modify built query in any way
     *
     * @param callable $modifyQuery
     * @return $this
     */
    public function modifyQuery(callable $modifyQuery) {
        $modifyQuery($this->query);

        return $this;
    }

    /**
     * Execute query and get data
     *
     * @param array $columns
     * @return LengthAwarePaginator|Collection The result is either LengthAwarePaginator (when pagination was attached) or simple Collection otherwise
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

    protected function materializeColumn($column) {
        return $column['table'].'.'.$column['column'];
    }

    protected function modelHasTranslations() {
        return $this->modelHasTranslations;
    }

    protected function materializeColumns(Collection $columns) {
        return $columns->map(function($column) {
            return $this->materializeColumn($column);
        })->toArray();
    }

}