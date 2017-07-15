<?php namespace Brackets\Admin;

use Dimsav\Translatable\Translatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AdminListing {

    /**
     * @var Model|Translatable
     */
    protected $model;

    /**
     * @var Model
     */
    protected $translationModel;

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
     * @throws NotAModelClass
     */
    public function setModel($model) {

        if (is_string($model)) {
            $model = app($model);
        }

        if (!is_a($model, Model::class)) {
            throw new NotAModelClass("AdminListing works only with Eloquent Models");
        }

        $this->model = $model;
        $this->query = $model->newQuery();

        if (in_array(Translatable::class, class_uses($this->model))) {
            $this->modelHasTranslations = true;
            $this->translationModel = app($this->model->getTranslationModelName());
        }
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

        // execute query and get the results
        return $this->get($columns, $locale);
    }

    /**
     * Attach the ordering functionality
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

                    if ($this->model->getKeyName() == $column['column'] && $this->model->getTable() == $column['table']) {
                        if (is_numeric($token) && $token === strval(intval($token))) {
                            $query->orWhere($column['table'].'.'.$column['column'], intval($token));
                        }
                    } else {
                        $query->orWhere($column['table'].".".$column['column'], 'ilike', '%'.$token.'%');
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
     * @param Collection $columns
     * @param string $locale
     * @return LengthAwarePaginator|Collection The result is either LengthAwarePaginator (when pagination was attached) or simple Collection otherwise
     */
    public function get(Collection $columns = null, $locale = null) {
        $columns = collect($columns)->map(function($column) {
            return $this->parseFullColumnName($column);
        });

        $this->attachTranslations($columns, $locale);

        if ($this->hasPagination) {
            return $this->query->paginate($this->perPage, $this->filterModelColumns($columns), $this->pageColumnName, $this->currentPage);
        } else {
            return $this->query->get($this->filterModelColumns($columns));
        }
    }

    protected function attachTranslations(Collection $columns, $locale = null) {
        if ($this->modelHasTranslations()) {

            if (is_null($locale)) {
                $locale = app()->getLocale();
            }

            $translationColumns = $this->filterTranslationModelColumns($columns);

            // we set eager loading, but only if there is anything to select
            if (count($translationColumns) > 0) {

                array_push($translationColumns, $this->translationModel->getTable().'.'.$this->model->getRelationKey());
                array_push($translationColumns, $this->translationModel->getTable().'.'.$this->model->getLocaleKey());

                $this->query->with([
                    'translations' => function (Relation $query) use ($translationColumns, $locale) {
                        $query->addSelect($translationColumns)
                            ->where($this->translationModel->getTable() . '.' . $this->model->getLocaleKey(), $locale);
                    },
                ]);
            }

            // but in order to get searching, filtering and ordering working, we have to also join the translation using locale we want to search/filter/order in
            $this->query->join($this->translationModel->getTable(), function ($join) use ($locale) {
                $join->on($this->model->getTable().'.'.$this->model->getKeyName(), '=', $this->translationModel->getTable().'.'.$this->model->getRelationKey())
                    ->where($this->translationModel->getTable().'.'.$this->model->getLocaleKey(), $locale);
            });
        }
    }

    protected function parseFullColumnName($column) {
        if (str_contains($column, '.')) {
            list($table, $column) = explode('.', $column, 2);
        } else {
            if ($this->modelHasTranslations() && $this->model->isTranslationAttribute($column)) {
                $table = $this->translationModel->getTable();
            } else {
                $table = $this->model->getTable();
            }
        }

        return compact('table', 'column');
    }

    protected function modelHasTranslations() {
        return $this->modelHasTranslations;
    }

    private function filterModelColumns(Collection $columns) {
        return $this->filterColumns($this->model, $columns);
    }

    private function filterTranslationModelColumns(Collection $columns) {
        return $this->filterColumns($this->translationModel, $columns);
    }

    private function filterColumns(Model $object, Collection $columns) {
        return $columns->filter(function($column) use ($object) {
            return $column['table'] == $object->getTable();
        })->map(function($column) {
            return $column['table'].'.'.$column['column'];
        })->toArray();
    }

}