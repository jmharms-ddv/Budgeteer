<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Paycheck;
use App\Bill;
use App\Income;
use App\Http\Resources\PaycheckResource;

class PaychecksController extends Controller
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
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', Paycheck::class);

        $optionsArr = $this->extractOptions($request);

        $paychecks = Paycheck::whereHas('income', function(Builder $query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->with($optionsArr['with'])->get();

        return PaycheckResource::collection($paychecks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'income_id' => 'required|integer',
            'amount' => 'nullable|digits_between:2,8',
            'amount_project' => 'nullable|digits_between:2,8',
            'paid_on' => 'required|date'
        ]);

        $income = Income::findOrFail($request->input('income_id'));

        $paycheck = new Paycheck;

        $paycheck->income_id = $request->input('income_id');

        $this->authorize('create', $paycheck);

        $paycheck->amount = $request->input('amount');
        $paycheck->amount_project = $request->input('amount_project');
        $paycheck->paid_on = $request->input('paid_on');

        if($paycheck->save()) {
            return new PaycheckResource($paycheck);
        }
    }

    /**
     * Pair a bill with a paycheck association in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pair(Request $request)
    {
        $request->validate([
            'bill_id' => 'required|integer',
            'paycheck_id' => 'required|integer',
            'amount' => 'nullable|digits_between:2,8',
            'amount_project' => 'nullable|digits_between:2,8',
            'due_on' => 'required|date',
            'paid_on' => 'nullable|date'
        ]);

        $bill = Bill::findOrFail($request->input('bill_id'));
        $paycheck = Paycheck::with('income')->findOrFail($request->input('paycheck_id'));

        $this->authorize('pair', [$paycheck, $bill]);

        $paycheck->bills()->attach($request->input('bill_id'), [
          'amount' => $request->input('amount'),
          'amount_project' => $request->input('amount_project'),
          'due_on' => $request->input('due_on'),
          'paid_on' => $request->input('paid_on')
        ]);

        return new PaycheckResource($paycheck);
    }

    /**
     * Update a resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'income_id' => 'nullable|integer',
            'amount' => 'nullable|digits_between:2,8',
            'amount_project' => 'nullable|digits_between:2,8',
            'paid_on' => 'nullable|date'
        ]);

        $paycheck = Paycheck::findOrFail($request->input('id'));

        $this->authorize('update', $paycheck);

        $paycheck->amount = $request->input('amount');
        $paycheck->amount_project = $request->input('amount_project');
        $paycheck->paid_on = $request->input('paid_on');

        if($paycheck->save()) {
            return new PaycheckResource($paycheck);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $paycheck = Paycheck::findOrFail($id);

        $this->authorize('view', $paycheck);

        return new PaycheckResource($paycheck);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $paycheck = Paycheck::findOrFail($id);

        $this->authorize('delete', $paycheck);

        if($paycheck->delete()) {
            return new PaycheckResource($paycheck);
        }
    }
}
