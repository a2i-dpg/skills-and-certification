<?php


namespace App\Http\Controllers\UtilityAPI;


use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TypeaheadAPI
{
    public function index(Request $request): JsonResponse
    {
        $inputs = $request->input();

        if (empty($inputs['model'])) {
            return response()->json(['error' => 'Not Found'], 404);
        }
        /** @var User $authUser */
        $authUser = Auth::user();
        try {
            $model = app($inputs['model']);

            if (!empty($inputs['connection']) && $inputs['connection'] === 'district') {
                $model->setConnection($authUser->getDbConnection());
            }

            /** @var $model Model */
            $search = $request->input('search', false);
            $keyField = !empty($inputs['key']) ? $inputs['key'] : 'id';
            $labelField = !empty($inputs['label']) ? $inputs['label'] : 'title';

            if (!empty($inputs['additional_fields']) && is_array($inputs['additional_fields'])) {
                $selectedColumns = [$keyField];
                $selectedColumns = array_merge($selectedColumns, $inputs['additional_fields']);
            } else {
                $selectedColumns = [$keyField, $labelField];
            }

            /** @var Builder $query */

            if (isset($inputs['scope']) && $inputs['scope'] && method_exists($model, 'scope' . ucfirst($inputs['scope']))) {
                $query = $model->select($selectedColumns)->{$inputs['scope']}();
            } else {
                $query = $model->select($selectedColumns);
            }

            if (!empty($inputs['filters']) && is_array($inputs['filters'])) {
                collect($inputs['filters'])->each(static function ($value, $key) use ($query) {
                    if (!empty($value) && is_scalar($value)) {
                        $query->where($key, $value);
                    } else if (!empty($value) && is_array($value)) {
                        $query->whereIn($key, $value);
                    }
                });
            }

            if (!empty($inputs['relation']) && !empty($inputs['relation_filters']) && is_array($inputs['relation_filters'])) {
                $relation = $inputs['relation'];
                $condition = [];
                foreach ($inputs['relation_filters'] as $key => $val) {
                    if (!empty($key) && !empty($val)) {
                        $condition[] = [$relation . '.' . $key, '=', $val];
                    }
                }
                if (count($condition)) {
                    $query->whereHas($relation, static function (Builder $q) use ($condition) {
                        $q->where($condition);
                    });
                }
            }
            if ($search) {
                $searchFields = [];
                if (!empty($inputs['search_fields']) && is_array($inputs['search_fields'])) {
                    $searchFields = $inputs['search_fields'];
                }

                if (count($searchFields) > 0) {
                    $query->where(static function (Builder $thisQuery) use ($search, $searchFields) {
                        foreach ($searchFields as $searchField) {
                            $thisQuery->orWhere($searchField, 'LIKE', '%' . $search . '%');
                        }
                        return $thisQuery;
                    });
                } else {
                    $query->where($labelField, 'LIKE', '%' . $search . '%');
                }
                $result = $query->limit(10)
                    ->get();
            } else {
                $result = $query->limit(10)->get();
            }


            $data = [];
            foreach ($result as $row) {
                $data[] = ['value' => $row->{$labelField}, 'id' => $row->{$keyField}];
            }

            return response()->json($data);
        } catch (\Exception $ex) {
            Log::debug($ex->getMessage());
            return response()->json(["error" => $ex->getMessage()], 500);
        }
    }
}
