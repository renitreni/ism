<?php

namespace App\Http\Controllers;

use App\Expenses;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ExpensesController extends Controller
{
    public function index()
    {
        return view('expenses');
    }

    
    public function table()
    {
        return DataTables::of(Expenses::all())->make(true);
    }

    public function create(Request $request)
    {
        return view('expenses_form');
    }

    public function store(Request $request)
    {
        Expenses::updateOrCreate(['id' => $request->has('id') ? $request->get('id') : null], $request->all());

        return 'success';
    }

    public function edit($id)
    {
        
        return view('expenses_form', ['overview' => Expenses::find($id)]);
    }

    public function destroy(Request $request)
    {
        Expenses::destroy($request->get('id'));

        return 'success';
    }
}
