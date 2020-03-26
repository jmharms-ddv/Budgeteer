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
        /* authorization */
        $this->authorize('index', Paycheck::class);
        /* extract options */
        $optionsArr = $this->extractOptions($request);
        /* find models with options */
        $paychecks = Paycheck::whereHas('income', function(Builder $query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->with($optionsArr['with'])->get();
        /* return resource collection */
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
        /* validation */
        $request->validate([
            'income_id' => 'required|integer',
            'amount' => 'nullable|numeric|between:0.01,99999.99',
            'amount_project' => 'nullable|numeric|between:0.01,99999.99',
            'paid_on' => 'required|date'
        ]);
        /* find model */
        $income = Income::findOrFail($request->input('income_id'));
        /* authorization */
        $paycheck = new Paycheck;
        $paycheck->income_id = $request->input('income_id');
        $this->authorize('create', $paycheck);
        /* create new model from request */
        $paycheck->amount = $request->input('amount');
        $paycheck->amount_project = $request->input('amount_project');
        $paycheck->paid_on = $request->input('paid_on');
        /* save model */
        if($paycheck->save()) {
            /* return resource */
            return new PaycheckResource($paycheck);
        }
    }

    /**
     * Update a resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        /* validation */
        $request->validate([
            'id' => 'required|integer',
            'income_id' => 'nullable|integer',
            'amount' => 'nullable|numeric|between:0.01,99999.99',
            'amount_project' => 'nullable|numeric|between:0.01,99999.99',
            'paid_on' => 'nullable|date'
        ]);
        /* find model */
        $paycheck = Paycheck::findOrFail($request->input('id'));
        /* authorization */
        $this->authorize('update', $paycheck);
        /* update model from request */
        $paycheck->amount = $request->input('amount');
        $paycheck->amount_project = $request->input('amount_project');
        $paycheck->paid_on = $request->input('paid_on');
        /* save model */
        if($paycheck->save()) {
            /* return resource */
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
        /* find model */
        $paycheck = Paycheck::findOrFail($id);
        /* authorization */
        $this->authorize('view', $paycheck);
        /* return resource */
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
        /* find model */
        $paycheck = Paycheck::findOrFail($id);
        /* authorization */
        $this->authorize('delete', $paycheck);
        /* delete model */
        if($paycheck->delete()) {
            /* return resource */
            return new PaycheckResource($paycheck);
        }
    }
}
