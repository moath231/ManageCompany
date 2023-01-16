<?php

namespace App\Http\Controllers;

use App\Models\company;
use Illuminate\Http\Request;

class adminCompanyController extends Controller
{
    public function index()
    {
        return view('admin.company.index', [
            'company' => company::latest()->paginate(5),
        ]);
    }

    public function create()
    {
        return view('admin.company.createCompany');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate(company::rules());
        if ($request->logo) {
            $path = $request->logo->store('app/logos', 'public');
            $validatedData['logo'] = $path;
        }

        company::create($validatedData);

        return redirect('/adminCompany')->with('success', 'Company store successfully!');
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        $comapny = company::findOrFail($id);
        return view('admin.company.edit', compact('comapny'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'logo' => 'sometimes|mimes:jpeg,jpg,png|dimensions:min_width=100,min_height=100|max:3000',
            'website' => 'required',
        ]);
        if ($validatedData['logo'] ?? false) {
            $path = $request->logo->store('app/logos', 'public');
            $validatedData['logo'] = $path;
        }

        $company = company::findOrFail($id);
        $company->update($validatedData);

        return redirect('/adminCompany')->with('success', 'Company updated successfully!');
    }

    public function destroy($id)
    {
        $company = company::find($id);

        if (!$company) {
            return redirect('/adminCompany')->with('error', 'Company not found.');
        }

        $company->delete();

        return redirect('/adminCompany')->with('error', 'delete is done.');;
    }
}
