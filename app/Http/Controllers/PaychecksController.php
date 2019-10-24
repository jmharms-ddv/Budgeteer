<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Paycheck;
use App\Income;
use App\Http\Resources\PaycheckResource;

class PaychecksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', Paycheck::class);

        $paychecks = Paycheck::whereHas('income', function(Builder $query) {
            $query->where('user_id', $request->user()->id);
        })->get();

        return PaycheckResource::collection($paychecks);
    }

    /**
     * Store a newly created resource in storage or update a resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->method('post')) {
            $request->validate([
                'income_id' => 'required|integer',
                'amount' => 'required|digits_between:2,8'
            ]);
            $income = Income::findOrFail($request->input('income_id'));
            $paycheck = new Paycheck;
            $paycheck->income_id = $request->input('income_id');
            $this->authorize('create', $paycheck);
        } else {
            $request->validate([
                'id' => 'required|integer',
                'income_id' => 'required|integer',
                'amount' => 'required|digits_between:2,8'
            ]);
            $paycheck = Paycheck::findOrFail($request->input('id'));
            $this->authorize('update', $paycheck);
        }

        $paycheck->name = $request->input('name');

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
