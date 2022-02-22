<?php

namespace App\Traits;

use App\Helpers\Classes\AuthHelper;
use Doctrine\DBAL\Schema\Schema;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait ScopeInstituteAclTrait
 * @package App\Traits\ModelTraits
 * @method static Builder|$this acl()
 */
trait ScopeAclTrait
{
    /**
     * @param Builder $query
     * @param string|null $alias
     * @return Builder
     */
    public function scopeAcl(Builder $query, string $alias = null): Builder
    {
        $table = $this->getTable();

        if (empty($alias)) {
            $alias = $this->getTable() . '.';
        }

        if (AuthHelper::checkAuthUser()) {
            $authUser = AuthHelper::getAuthUser();

            if ($authUser->isUserBelongsToInstitute()) {

                if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'institute_id')) {
                    $query->where($alias . 'institute_id', $authUser->institute_id);
                } elseif (\Illuminate\Support\Facades\Schema::hasColumn($table, 'batch_id')) {
                    $query->join('batches', $table . '.batch_id', '=', 'batches.id');
                    $query->where('batches.institute_id', $authUser->institute_id);
                } elseif (\Illuminate\Support\Facades\Schema::hasColumn($table, 'trainee_batch_id')) {
                    $query->join('batches', $table . '.trainee_batch_id', '=', 'batches.id');
                    $query->where('batches.institute_id', $authUser->institute_id);
                }

            }
        }

        return $query;
    }
}
