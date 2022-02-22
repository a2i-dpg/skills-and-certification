<?php

namespace App\Policies;

use App\Models\GalleryCategory;
use App\Models\User;

class GalleryCategoryPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_any_gallery_category');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param GalleryCategory $galleryCategory
     * @return mixed
     */
    public function view(User $user, GalleryCategory $galleryCategory)
    {
        return $user->hasPermission('view_single_gallery_category');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_gallery_category');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param GalleryCategory $galleryCategory
     * @return mixed
     */
    public function update(User $user, GalleryCategory $galleryCategory)
    {
        return $user->hasPermission('update_gallery_category');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param GalleryCategory $galleryCategory
     * @return mixed
     */
    public function delete(User $user, GalleryCategory $galleryCategory)
    {
        return $user->hasPermission('delete_gallery_category');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param GalleryCategory $galleryCategory
     * @return mixed
     */
    public function restore(User $user, GalleryCategory $galleryCategory)
    {
        return $user->hasPermission('restore_gallery_category');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param GalleryCategory $galleryCategory
     * @return mixed
     */
    public function forceDelete(User $user, GalleryCategory $galleryCategory)
    {
        return $user->hasPermission('force_delete_gallery_category');
    }


    public function viewFeaturedGalleries(User $user): bool
    {
        return $user->isUserBelongsToInstitute();
    }
}
