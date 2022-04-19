<?php

namespace App\Policies;

use App\Models\CertificateTemplate;
use App\Models\User;

class CertificateTemplatePolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_any_certificate_template');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param CertificateTemplate $certificateTemplate
     * @return mixed
     */
    public function view(User $user, CertificateTemplate $certificateTemplate)
    {
        return $user->hasPermission('view_single_certificate_template');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_certificate_template');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param CertificateTemplate $certificateTemplate
     * @return mixed
     */
    public function update(User $user, CertificateTemplate $certificateTemplate)
    {
        return $user->hasPermission('update_certificate_template');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param CertificateTemplate $certificateTemplate
     * @return mixed
     */
    public function delete(User $user, CertificateTemplate $certificateTemplate)
    {
        return $user->hasPermission('delete_certificate_template');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param CertificateTemplate $certificateTemplate
     * @return mixed
     */
    public function restore(User $user, CertificateTemplate $certificateTemplate)
    {
        return $user->hasPermission('restore_certificate_template');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param CertificateTemplate $certificateTemplate
     * @return mixed
     */
    public function forceDelete(User $user, CertificateTemplate $certificateTemplate)
    {
        return $user->hasPermission('force_delete_certificate_template');
    }
}
