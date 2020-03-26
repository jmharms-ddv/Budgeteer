<?php

namespace App\Policies;

use App\User;
use App\Bill;
use Illuminate\Auth\Access\HandlesAuthorization;

class BillPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any bills.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the bill.
     *
     * @param  \App\User  $user
     * @param  \App\Bill  $bill
     * @return mixed
     */
    public function view(User $user, Bill $bill)
    {
        return $bill->user_id == $user->id;
    }

    /**
     * Determine whether the user can create bills.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, Bill $bill)
    {
        return $user->id == $bill->user_id;
    }

    /**
     * Determine whether the user can update the bill.
     *
     * @param  \App\User  $user
     * @param  \App\Bill  $bill
     * @return mixed
     */
    public function update(User $user, Bill $bill)
    {
        return $bill->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the bill.
     *
     * @param  \App\User  $user
     * @param  \App\Bill  $bill
     * @return mixed
     */
    public function delete(User $user, Bill $bill)
    {
        return $bill->user_id == $user->id;
    }

    /**
     * Determine whether the user can restore the bill.
     *
     * @param  \App\User  $user
     * @param  \App\Bill  $bill
     * @return mixed
     */
    public function restore(User $user, Bill $bill)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the bill.
     *
     * @param  \App\User  $user
     * @param  \App\Bill  $bill
     * @return mixed
     */
    public function forceDelete(User $user, Bill $bill)
    {
        //
    }

    /**
     * The following methods are for Bill-Paycheck association management
     */

    /**
     * Determine whether the user can create bill-paycheck association
     *
     * @param  \App\User  $user
     * @param  \App\Bill  $bill
     * @param  \App\Paycheck  $paycheck
     * @return mixed
     */
    public function attachPaycheck(User $user, Bill $bill, Paycheck $paycheck)
    {
        return $bill->user_id == $user->id && $paycheck->income->user_id == $user->id;
    }

    /**
     * Determine whether the user can update bill-paycheck association
     *
     * @param  \App\User  $user
     * @param  \App\Bill  $bill
     * @param  \App\Paycheck  $paycheck
     * @return mixed
     */

    public function updatePivotPaycheck(User $user, Bill $bill, Paycheck $paycheck)
    {
        return $bill->user_id == $user->id && $paycheck->income->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the bill-paycheck association
     *
     * @param  \App\User  $user
     * @param  \App\Bill  $bill
     * @param  \App\Paycheck  $paycheck
     * @return mixed
     */
    public function detachPaycheck(User $user, Bill $bill, Paycheck $paycheck)
    {
        return $bill->user_id == $user->id && $paycheck->income->user_id == $user->id;
    }
}
