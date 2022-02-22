<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy extends BasePolicy
{

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_any_course');
    }

    /**
     * determine where the user can view models.
     *
     * @param User $user
     * @param Course $course
     * @return bool
     */
    public function view(User $user, Course $course): bool
    {
        return $user->hasPermission('view_single_course');
    }


    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_course');
    }


    /**
     * @param User $user
     * @return mixed
     */
    public function update(User $user): bool
    {
        return $user->hasPermission('update_course');
    }

    /**
     * @param User $user
     * @param Course $course
     * @return mixed
     */
    public function delete(User $user, Course $course)
    {
        return $user->hasPermission('delete_course');

    }

    /**
     * @param User $user
     * @param Course $course
     * @return mixed
     *
     */
    public function restore(User $user, Course $course)
    {
        return $user->hasPermission('restore_courses');
    }

    /**
     * @param User $user
     * @param Course $course
     * @return mixed
     */
    public function forceDelete(User $user, Course $course)
    {
        return $user->hasPermission('force_delete_courses');
    }
}
