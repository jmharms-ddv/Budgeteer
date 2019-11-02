<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        $bills = Bill::where('user_id', $request->user()->id)->get();

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
        if($request->method('post')) {
            $request->validate([
                'name' => 'required|string'
            ]);
            $this->authorize('create', Bill::class);
            $bill = new Bill;
            $bill->user_id = $request->user()->id;
        } else {
            $request->validate([
                'id' => 'required|integer',
                'user_id' => 'required|integer',
                'name' => 'required|string',
                'amount' => 'required|digits_between:1,7'
            ]);
            $bill = Bill::findOrFail($request->input('id'));
            $this->authorize('update', $bill);
        }

        $bill->name = $request->input('name');

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
