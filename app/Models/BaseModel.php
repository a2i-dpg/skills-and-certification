<?php

namespace App\Models;

use App\Helpers\Classes\AuthHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

/**
 * Class BaseModel
 * @package App\Models
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property-read int id
 */
abstract class BaseModel extends Model
{
    public const ROW_STATUS_ACTIVE = '1';
    public const ROW_STATUS_INACTIVE = '0';
    public const ROW_STATUS_DELETED = '99';
    const MOBILE_REGEX =  "regex:/^01[0-9]{9}$/";


    /**
     * @param $decorated
     * @return array
     */
    public function getRowStatusOptions($decorated): array
    {
        return [
            self::ROW_STATUS_ACTIVE => sprintf($decorated ? '<span class="badge badge-success"> %s </span>' : '%s', __('Active')),
            self::ROW_STATUS_INACTIVE => sprintf($decorated ? '<span class="badge badge-warning"> %s </span>' : '%s', __('Inactive')),
            self::ROW_STATUS_DELETED => sprintf($decorated ? '<span class="badge badge-danger"> %s </span>' : '%s', __('Deleted')),
        ];
    }

    /**
     * get active status of a model
     *
     * @param false $decorated
     * @return string
     */
    public function getCurrentRowStatus(bool $decorated = false): string
    {
        $rowStatusArray = $this->getRowStatusOptions($decorated);
        if (!empty($rowStatusArray[$this->row_status])) {
            return $rowStatusArray[$this->row_status];
        }
        return '';
    }


    public function save(array $options = []): bool
    {
        if (AuthHelper::checkAuthUser()) {
            $authUser = AuthHelper::getAuthUser();
            if ($this->getAttribute('id')) {
                if (Schema::hasColumn($this->getTable(), 'updated_by')) {
                    $this->updated_by = $authUser->id;
                }
            } else {
                if (Schema::hasColumn($this->getTable(), 'created_by')) {
                    $this->created_by = $authUser->id;
                }
            }
        }

        return parent::save($options);
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        if (Auth::check()) {
            $authUser = AuthHelper::getAuthUser();
            $connectionName = $this->getConnectionName();
            if (Schema::connection($connectionName)->hasColumn($this->getTable(), 'updated_by')) {
                $this->updated_by = $authUser->id;
            }
        }

        return parent::update($attributes, $options);
    }
}
