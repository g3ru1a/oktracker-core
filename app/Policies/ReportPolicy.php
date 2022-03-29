<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function view_assignee(User $user){
        return $user->role_id == Role::ADMIN;
    }

    public function remove_assignee(User $user)
    {
        return $user->role_id == Role::ADMIN;
    }

    public function view_list(User $user)
    {
        return in_array($user->role_id, [Role::ADMIN, Role::DATA_ANALYST]);
    }

    public function complete(User $user, Report $report)
    {
        return $user->role_id == Role::ADMIN || ($user->role_id == Role::DATA_ANALYST && $user->id == $report->assignee_id);
    }

    public function drop(User $user, Report $report)
    {
        return $user->role_id == Role::ADMIN || ($user->role_id == Role::DATA_ANALYST && $user->id == $report->assignee_id);
    }

    public function take_batch(User $user)
    {
        return $user->role_id == Role::DATA_ANALYST && count($user->unfinishedReports) == 0;
    }

    public function destroy(User $user)
    {
        return $user->role_id == Role::ADMIN;
    }
}
