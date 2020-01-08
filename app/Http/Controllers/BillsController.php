<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Bill;
use App\Http\Resources\BillResource;

class BillsController extends Controller
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
        $this->authorize('index', Bill::class);

        $optionsArr = $this->extractOptions($request);

        $bills = Bill::where('user_id', $request->user()->id)->with($optionsArr['with'])->get();

        if(!empty($optionsArr['filter_date'])) {
            $filter_dateArr = $optionsArr['filter_date'];
            $bills = $bills->filter(function($model, $key) use ($filter_dateArr) {
                foreach($filter_dateArr as $filter) {
                    $comparisionFunction = $filter['comparison'];
                    $valueArr = explode("-", $filter['comparison_value']);
                    $carbonValue = Carbon::create($valueArr[0], $valueArr[1], $valueArr[2]);
                    $carbonModel = $model->{$filter['model_attribute']};
                    if(!($carbonValue->$comparisionFunction($carbonModel))) {
                        return false;
                    }
                }
                return true;
            });
        }

        return BillResource::collection($bills);
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
            'name' => 'required|string',
            'amount' => 'digits_between:1,7',
            'day_due_on' => 'nullable|integer',
            'start_at' => 'bail|required|date',
            'end_at' => 'required|date|after:'.$request->input('start_at')
        ]);
        /* authorization */
        $this->authorize('create', Bill::class);
        /* create new Bill */
        $bill = new Bill;
        $bill->user_id = $request->user()->id;
        $bill->name = $request->input('name');
        $bill->amount = $request->input('amount');
        $bill->day_due_on = $request->input('day_due_on');
        $bill->start_at = $request->input('start_at');
        $bill->end_at = $request->input('end_at');
        /* save new Bill */
        if($bill->save()) {
            return new BillResource($bill);
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
            'user_id' => 'nullable|integer',
            'name' => 'nullable|string',
            'amount' => 'nullable|digits_between:1,7',
            'day_due_on' => 'nullable|integer',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after:'.$request->input('start_at')
        ]);
        /* find resource */
        $bill = Bill::findOrFail($request->input('id'));
        /* authorization */
        $this->authorize('update', $bill);
        /* update Bill */
        $bill->name = $request->input('name');
        $bill->amount = $request->input('amount');
        $bill->day_due_on = $request->input('day_due_on');
        $bill->start_at = $request->input('start_at');
        $bill->end_at = $request->input('end_at');

        if($bill->save()) {
            return new BillResource($bill);
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
        $bill = Bill::findOrFail($id);

        $this->authorize('view', $bill);

        return new BillResource($bill);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bill = Bill::findOrFail($id);

        $this->authorize('delete', $bill);

        if($bill->delete()) {
            return new BillResource($bill);
        }
    }
}
