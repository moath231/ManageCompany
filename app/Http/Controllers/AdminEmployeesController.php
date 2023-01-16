<?php

namespace App\Http\Controllers;

use App\Models\employee;
use Illuminate\Http\Request;

class AdminEmployeesController extends Controller
{
    public function index()
    {
        return view('admin.employee.index', [
            'emp' => employee::latest()->paginate(5),
        ]);
    }

    public function create()
    {
        return view('admin.employee.create');
    }

    public function store(Request $request)
    {
        $valiDateEmployeeData = $request->validate(employee::rules()) ;

        employee::create($valiDateEmployeeData);

        return redirect('/adminEmployee')->with('success', 'employee store successfully!');
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        $e = employee::find($id);
        return view('admin.employee.edit',compact('e'));
    }

    public function update(Request $request, $id)
    {
        $phone_regex = '/^07[0-9]{8}$/';
        $attributes = $request->validate([
            'Fname' => 'required',
            'Lname' => 'required',
            'company_id' => 'sometimes',
            'email' => 'required|email',
            'phone' => ['required',"regex:$phone_regex"],
        ]);
        $employee = employee::find($id);

        $employee->update($attributes);

        return redirect('/adminEmployee')->with('success', 'employee updated successfully!');
    }

    public function destroy($id)
    {
        $e = employee::find($id);

        if (!$e) {
            return redirect('/adminEmployee')->with('error', 'employee not found.');
        }

        $e->delete();

        return redirect('/adminEmployee')->with('error', 'delete is done.');
    }
}
