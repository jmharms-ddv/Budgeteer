<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Income;
use App\Http\Resources\IncomeResource;

class IncomesController extends Controller
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
        $this->authorize('index', Income::class);

        $optionsArr = $this->extractOptions($request);

        $incomes = Income::where('user_id', $request->user()->id)->with($optionsArr['with'])->get();

        return IncomeResource::collection($incomes);
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
            'name' => 'required|string'
        ]);

        $this->authorize('create', Income::class);

        $income = new Income;

        $income->user_id = $request->user()->id;

        $income->name = $request->input('name');

        if($income->save()) {
            return new IncomeResource($income);
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
        $request->validate([
            'id' => 'required|integer',
            'user_id' => 'required|integer',
            'name' => 'required|string'
        ]);

        $income = Income::findOrFail($request->input('id'));

        $this->authorize('update', $income);

        $income->name = $request->input('name');

        if($income->save()) {
            return new IncomeResource($income);
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
        $income = Income::findOrFail($id);

        $this->authorize('view', $income);

        return new IncomeResource($income);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $income = Income::findOrFail($id);

        $this->authorize('delete', $income);

        if($income->delete()) {
            return new IncomeResource($income);
        }
    }
}
