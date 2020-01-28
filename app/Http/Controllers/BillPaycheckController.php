<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Bill;
use App\Http\Resources\BillResource;
use App\Paycheck;
use App\Http\Resources\PaycheckResource;

class BillPaycheckController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Store a newly created bill-association in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* validation */
        $request->validate([
            'bill_id' => 'required|integer',
            'paycheck_id' => 'required|integer',
            'amount' => 'nullable|numeric|between:0.01,99999.99',
            'amount_project' => 'nullable|numeric|between:0.01,99999.99',
            'due_on' => 'required|date',
            'paid_on' => 'nullable|date'
        ]);
        /* find models */
        $bill = Bill::findOrFail($request->input('bill_id'));
        $paycheck = Paycheck::with('income', 'bills')->findOrFail($request->input('paycheck_id'));
        /* authorization */
        $this->authorize('attachBill', [$paycheck, $bill]);
        /* save new association from request */
        $paycheck->bills()->attach($request->input('bill_id'), [
          'amount' => $request->input('amount'),
          'amount_project' => $request->input('amount_project'),
          'due_on' => $request->input('due_on'),
          'paid_on' => $request->input('paid_on')
        ]);

        /**
         * A paycheck resource is returned only because (arbitrarily)
         * the paycheck resource was chosen to contain 'pivot'
         * (intermediate table) values.
         */

        /* return resource */
        return new PaycheckResource($paycheck);
    }

    /**
     * Update a bill-paycheck association in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        /* validation */
        $request->validate([
            'bill_id' => 'required|integer',
            'paycheck_id' => 'required|integer',
            'amount' => 'nullable|digits_between:2,8',
            'amount_project' => 'nullable|digits_between:2,8',
            'due_on' => 'required|date',
            'paid_on' => 'nullable|date'
        ]);
        /* find models */
        $bill = Bill::findOrFail($request->input('bill_id'));
        $paycheck = Paycheck::with('income', 'bills')->findOrFail($request->input('paycheck_id'));
        /* authorization */
        $this->authorize('updatePivotBill', [$paycheck, $bill]);
        /* save association from request */
        $paycheck->bills()->updateExistingPivot($request->input('bill_id'), [
          'amount' => $request->input('amount'),
          'amount_project' => $request->input('amount_project'),
          'due_on' => $request->input('due_on'),
          'paid_on' => $request->input('paid_on')
        ]);

        /**
         * A paycheck resource is returned only because (arbitrarily)
         * the paycheck resource was chosen to contain 'pivot'
         * (intermediate table) values.
         */

        /* return resource */
        return new PaycheckResource($paycheck);
    }

    /**
     * Remove the specified bill-paycheck association from storage.
     *
     * @param  int  $billId
     * @param  int  $paycheckId
     * @return \Illuminate\Http\Response
     */
    public function destroy($billId, $paycheckId)
    {
        /* find resources */
        $bill = Bill::findOrFail($billId);
        $paycheck = Paycheck::findOrFail($paycheckId);
        /* authorization */
        $this->authorize('detachBill', [$paycheck, $bill]);
        /* delete association */
        $paycheck->bills()->detach($billId);

        /**
         * A paycheck resource is returned only because (arbitrarily)
         * the paycheck resource was chosen to contain 'pivot'
         * (intermediate table) values.
         */

        /* return resource */
        return new PaycheckResource($paycheck);
    }
}
