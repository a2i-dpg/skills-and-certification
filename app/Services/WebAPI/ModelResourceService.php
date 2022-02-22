<?php

namespace App\Services\WebAPI;

use App\Exceptions\CustomException;
use App\Helpers\Classes\AuthHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ModelResourceService
{
    public ?array $filters = [];
    public ?array $scopes = null;
    public array $columns;
    public Model $model;
    public string $resourceType = 'full-response';
    public array $paginate = [];

    /**
     * @throws CustomException
     */
    public function bootstrap(Request $request)
    {
        try {
            $resourceModel = $request->input('resource.model');
            $parsedModelInstance = $this->parseModel($resourceModel);
            $this->setModel($parsedModelInstance);
        } catch (\Throwable $throwable) {
            throw new CustomException('Invalid model instance exception.');
        }

        /**
         * format: 'name|relation_name:relation_col1,col2'
         */
        $columnStrings = $request->input('resource.columns', '');

        /**
         * model => [], here model key contain base model column
         * relationship_name => [] here relationship_name key contain related column
         */
        $parsedColumns = $this->parseColumns($columnStrings);
        $this->setColumns($parsedColumns);

        $responseType = $request->input('resource.type');
        $this->setResourceType($responseType);

        $this->setPaginate($request);

        $resourceFilters = $request->input('resource.filters', []);
        $this->setFilters($resourceFilters);

        $resourceScopes = $request->input('resource.scopes');
        $this->parseScopes($resourceScopes);
    }

    public function getSelectableColumns(Builder $query): array
    {
        $baseModelTableName = $this->model->getTable();
        $selectableColumns = [];

        foreach ($this->columns as $key => $columns) {
            if ($key === 'model') {
                $primaryKey = $this->model->getKeyName();

                if (!in_array($primaryKey, $columns)) {
                    array_push($columns, $primaryKey);
                }

                $tableColumns = $this->loadModelTableColumns($this->model);
                $validColumns = array_intersect($tableColumns, $columns);

                foreach ($validColumns as $column) {
                    $selectableColumns[] = $baseModelTableName . '.' . $column;
                }

            } elseif (method_exists($this->model, $key)) {
                /** @var BelongsTo|HasMany $relatedRelation */
                $relatedRelation = $this->model->{$key}();

                $ownerKey = $relatedRelation->getOwnerKeyName();
                $relatedForeignKey = $relatedRelation->getForeignKeyName();

                $relatedModelInstance = $relatedRelation->getRelated();

                $primaryKey = $relatedModelInstance->getKeyName();
                if (!in_array($primaryKey, $columns)) {
                    array_push($columns, $primaryKey);
                }

                $relatedTableName = $relatedModelInstance->getTable();
                $tableColumns = $this->loadModelTableColumns($relatedModelInstance);
                $validColumns = array_intersect($tableColumns, $columns);

                foreach ($validColumns as $column) {
                    $selectableColumns[] = ($relatedTableName . '.' . $column) . ' as ' . ($key . '_' . $column);
                }

                if (!in_array($relatedForeignKey, $columns)) {
                    $selectableColumns[] = $baseModelTableName . '.' . $relatedForeignKey;
                }

                $baseTablePrimaryKey = $baseModelTableName .'.id';
                if (!in_array($selectableColumns, [$baseTablePrimaryKey])) {
                    array_push($selectableColumns, $baseTablePrimaryKey);
                }
                $query->leftJoin(
                    $relatedTableName,
                    $baseModelTableName . '.' . $relatedForeignKey,
                    '=',
                    $relatedTableName . '.' . $ownerKey
                );
            }
        }

        return $selectableColumns;
    }

    public function applyFilters(Builder $query): Builder
    {
        foreach ($this->filters as $key => $filter) {
            $parseKey = explode('.', $key);
            if (count($parseKey) == 2) {
                $relation = $parseKey[0];
                $relatedModel = $this->getRelatedModel($relation);
                $tableName = $relatedModel->getTable();
                $column = $parseKey[1];
            } elseif (count($parseKey) == 1) {
                $tableName = $this->model->getTable();
                $column = $parseKey[0];
            }
            if (empty($tableName) || empty($column)) {
                continue;
            }

            $whereColumn = $tableName . '.' . $column;

            $type = !empty($filter['type']) ? $filter['type'] : null;
            $value = !empty($filter['value']) ? $filter['value'] : (!empty($filter) ? $filter : '');

            if (empty($value)) {
                continue;
            }

            if ($type === 'equal') {
                $query->where($whereColumn, '=', $value);
            } elseif ($type === 'not-equal') {
                $query->where($whereColumn, '!=', $value);
            } elseif ($type === 'contain') {
                $query->where($whereColumn, 'LIKE', "%$value%");
            } else {
                $query->where($whereColumn, '=', $value);
            }
        }

        return $query;
    }

    public function applyScopes($query)
    {
        if (!AuthHelper::checkAuthUser('web')) {
            return $query;
        }

        foreach (($this->scopes ?? []) as $scope) {
            if (method_exists($this->model, 'scope' . ucfirst($scope))) {
                $query->{$scope}();
            }
        }

        return $query;
    }

    /**
     * @param string|null $columns
     * @return array
     */
    private function convertStringColumnsToArray(?string $columns): array
    {
        if (empty($columns)) {
            return [];
        }

        $columnsArray = explode('|', $columns);
        $finalColumns = [];

        foreach ($columnsArray as $column) {
            $column = trim($column);
            $columnArray = explode('.', $column);
            if (count($columnArray) == 2) {
                $finalColumns[$columnArray[0]][] = $columnArray[1];
            } elseif (count($columnArray) == 1) {
                $finalColumns['model'][] = $column;
            }
        }

        return $finalColumns;
    }

    /**
     * @param Model $model
     * @return array
     */
    public function loadModelTableColumns(Model $model): array
    {
        return Schema::getColumnListing($model->getTable());
    }

    public function getResults(Builder $query, Request $request): \Illuminate\Http\JsonResponse
    {
        if ($this->resourceType === 'select2') {
            $totalCount = $query->count();
            if (count($this->paginate)) {
                $result = $query->take($this->paginate['perPage'] ?? 20)->skip($this->paginate['skip'])->get();
            } else {
                $result = $query->get();
            }

            $responseFormat = $request->input('resource.select2.text_field_format');
            $keyField = $request->input('resource.select2.key_field');


            $data = [];
            foreach ($result as $row) {
                $keyFieldValue = $this->getSelect2KeyFieldValue($row, $keyField);
                $select2TextFieldValue = $this->getSelect2TitleTextWithResponseFormat($row, $responseFormat);

                $data[] = ['text' => $select2TextFieldValue, 'id' => $keyFieldValue];
            }

            return response()->json([
                'results' => $data,
                'pagination' => [
//                    'more' => ($totalCount > ($this->paginate['skip'] + $this->paginate['perPage'])),
                    'more' => false,
                ],
            ]);
        }

        if (count($this->paginate)) {
            $paginationResult = $query->paginate($this->paginate['perPage'])->links()->render();
            return response()->json([
                'data' => $query->paginate($this->paginate['perPage']),
                'pagination' => $paginationResult,
            ]);
        }

        return response()->json([
            'data' => $query->get()
        ]);
    }

    private function getSelect2TitleTextWithResponseFormat(Model $row, ?string $responseFormat)
    {
        preg_match_all('~\{([^}]*)\}~', $responseFormat, $matches);
        $parsedColumns = $matches[1];

        $result = $responseFormat;
        foreach ($parsedColumns as $column) {
            $columnArray = explode('.', $column);
            $selector = '';
            if (count($columnArray) == 2) {
                $selector = $columnArray[0] . '_' . $columnArray[1];
            } elseif (count($columnArray) == 1) {
                $selector = $column;
            }
            $value = $row->getAttribute($selector);
            $result = str_replace('{' . $column . '}', $value, $result);
        }

        return $result;
    }


    private function getSelect2KeyFieldValue($row, ?string $keyField)
    {
        if (empty($keyField)) {
            $keyField = $this->model->getKeyName();
        }

        return $row->getAttribute($keyField) ?? '0';
    }

    public function applySearch(Builder $query, Request $request)
    {
        $search = $request->input('search');
        $responseFormat = $request->input('resource.select2.text_field_format');

        if ($this->resourceType === 'select2' && !empty($search)) {
            preg_match_all('~\{([^}]*)\}~', $responseFormat, $matches);
            $parsedColumns = $matches[1];

            $selectors = [];
            foreach ($parsedColumns as $column) {
                $columnArray = explode('.', $column);
                if (count($columnArray) == 2) {
                    $relation = $columnArray[0];
                    $relatedModel = $this->getRelatedModel($relation);
                    $tableName = $relatedModel->getTable();
                    $selectors[] = $tableName . '.' . $columnArray[1];
                } elseif (count($columnArray) == 1) {
                    $selectors[] = $this->model->getTable() . '.' . $column;
                }
            }

            $query->where(function ($q) use ($selectors, $search) {
                foreach ($selectors as $selector) {
                    $q->orWhere($selector, 'LIKE', "%$search%");
                }
            });
        }
    }

    private function parseScopes(?string $scopes): void
    {
        $parsedScopes = [];
        if (!empty($scopes)) {
            $parsedScopes = explode("|", $scopes);
        }
        $this->setScopes($parsedScopes);
    }

    /**
     * @param string $columns
     * @return array
     * @throws CustomException
     */
    public function parseColumns(string $columns): array
    {
        $parsedColumns = $this->convertStringColumnsToArray($columns);
        if (!count($parsedColumns)) {
            throw new CustomException('please select at least a column');
        }

        return $parsedColumns;
    }

    public function parseModel(?string $encodedModel): Model
    {
        return app(base64_decode($encodedModel));
    }

    /**
     * @param array|null $filters
     */
    public function setFilters(?array $filters): void
    {
        $this->filters = $filters;
    }

    /**
     * @param array|null $scopes
     */
    public function setScopes(?array $scopes): void
    {
        $this->scopes = $scopes;
    }

    public function setColumns(array $columns): void
    {
        $this->columns = $columns;
    }

    /**
     * @param Model $model
     */
    public function setModel(Model $model): void
    {
        $this->model = $model;
    }

    /**
     * @param string|null $resourceType
     */
    public function setResourceType(?string $resourceType): void
    {
        if (!empty($resourceType)) {
            $this->resourceType = $resourceType;
        }
    }

    /**
     * @param Request $request
     */
    public function setPaginate(Request $request): void
    {
        $paginate = $request->input('resource.paginate') == 'true';
        $page = $request->input('resource.page');

        if (!$paginate || $page < 1) {
            return;
        }

        $perPage = $request->input('resource.per_page', 50);
        $skip = $perPage * ($page - 1);

        $this->paginate = compact('page', 'perPage', 'skip');
    }

    /**
     * @param string $key
     * @return Model
     */
    private function getRelatedModel(string $key): Model
    {
        /** @var BelongsTo $relatedRelation */
        $relatedRelation = $this->model->{$key}();
        return $relatedRelation->getRelated();
    }
}
