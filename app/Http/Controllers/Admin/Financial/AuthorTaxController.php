<?php

namespace App\Http\Controllers\Admin\Financial;

use App\Classes\Country;
use App\Http\Controllers\Controller;
use App\Models\AuthorTax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorTaxController extends Controller
{
    public function index()
    {
        $authorTaxes = AuthorTax::all();
        return view('admin.financial.author-taxes.index', [
            'authorTaxes' => $authorTaxes,
        ]);
    }

    public function create()
    {
        return view('admin.financial.author-taxes.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'block_patterns', 'max:255'],
            'rate' => ['required', 'integer', 'min:1', 'max:100'],
            'countries' => ['required', 'array'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        foreach ($request->countries as $country) {
            if (!array_key_exists($country, countries())) {
                toastr()->error(translate('Invalid Country'));
                return back()->withInput();
            }

            $countryExists = AuthorTax::whereJsonContains('countries', $country)->first();
            if ($countryExists) {
                toastr()->error(translate(':country is already exists', ['country' => countries($country)]));
                return back()->withInput();
            }
        }

        $authorTax = new AuthorTax();
        $authorTax->name = $request->name;
        $authorTax->rate = $request->rate;
        $authorTax->countries = $request->countries;
        $authorTax->save();

        toastr()->success(translate('Created Successfully'));
        return redirect()->route('admin.financial.author-taxes.index');
    }

    public function edit(AuthorTax $authorTax)
    {
        return view('admin.financial.author-taxes.edit', [
            'authorTax' => $authorTax,
        ]);
    }

    public function update(Request $request, AuthorTax $authorTax)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'block_patterns', 'max:255'],
            'rate' => ['required', 'integer', 'min:1', 'max:100'],
            'countries' => ['required', 'array'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        foreach ($request->countries as $country) {
            if (!array_key_exists($country, Country::all())) {
                toastr()->error(translate('Invalid Country'));
                return back()->withInput();
            }

            $countryExists = AuthorTax::whereNot('id', $authorTax->id)
                ->whereJsonContains('countries', $country)->first();
            if ($countryExists) {
                toastr()->error(translate(':country is already exists', ['country' => Country::get($country)]));
                return back()->withInput();
            }
        }

        $authorTax->name = $request->name;
        $authorTax->rate = $request->rate;
        $authorTax->countries = $request->countries;
        $authorTax->update();

        toastr()->success(translate('Updated Successfully'));
        return back();
    }

    public function destroy(AuthorTax $authorTax)
    {
        $authorTax->delete();
        toastr()->success(translate('Deleted Successfully'));
        return back();
    }
}