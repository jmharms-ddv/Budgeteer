<?php

namespace App\Policies;

use App\User;
use App\Paycheck;
use App\Bill;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaycheckPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any paychecks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the paycheck.
     *
     * @param  \App\User  $user
     * @param  \App\Paycheck  $paycheck
     * @return mixed
     */
    public function view(User $user, Paycheck $paycheck)
    {
        $paycheck->load('income');
        return $paycheck->income->user_id == $user->id;
    }

    /**
     * Determine whether the user can create paychecks.
     *
     * @param  \App\User  $user
     * @param  \App\Paycheck  $paycheck
     * @return mixed
     */
    public function create(User $user, Paycheck $paycheck)
    {
        $paycheck->load('income');
        return $paycheck->income->user_id == $user->id;
    }

    /**
     * Determine whether the user can create bill-paychecks pairing
     *
     * @param  \App\User  $user
     * @param  \App\Paycheck  $paycheck
     * @param  \App\Bill  $bill
     * @return mixed
     */
    public function pair(User $user, Paycheck $paycheck, Bill $bill)
    {
        return $paycheck->income->user_id == $user->id && $bill->user_id == $user->id;
    }

    /**
     * Determine whether the user can update the paycheck.
     *
     * @param  \App\User  $user
     * @param  \App\Paycheck  $paycheck
     * @return mixed
     */
    public function update(User $user, Paycheck $paycheck)
    {
        $paycheck->load('income');
        return $paycheck->income->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the paycheck.
     *
     * @param  \App\User  $user
     * @param  \App\Paycheck  $paycheck
     * @return mixed
     */
    public function delete(User $user, Paycheck $paycheck)
    {
        $paycheck->load('income');
        return $paycheck->income->user_id == $user->id;
    }

    /**
     * Determine whether the user can restore the paycheck.
     *
     * @param  \App\User  $user
     * @param  \App\Paycheck  $paycheck
     * @return mixed
     */
    public function restore(User $user, Paycheck $paycheck)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the paycheck.
     *
     * @param  \App\User  $user
     * @param  \App\Paycheck  $paycheck
     * @return mixed
     */
    public function forceDelete(User $user, Paycheck $paycheck)
    {
        //
    }
}
