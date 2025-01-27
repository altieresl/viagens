<?php

namespace App\Policies;

use App\Models\TravelRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TravelRequestPolicy
{
    use HandlesAuthorization;

    public function updateStatus(User $user, TravelRequest $travelRequest)
    {
        return $user->id !== $travelRequest->user_id;
    }
}
