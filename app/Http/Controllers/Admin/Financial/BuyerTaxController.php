<?php

namespace App\Http\Controllers\Admin\Financial;

use App\Classes\Country;
use App\Http\Controllers\Controller;
use App\Models\BuyerTax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BuyerTaxController extends Controller
{
    public function index()
    {
        $buyerTaxes = BuyerTax::all();
        return view('admin.financial.buyer-taxes.index', [
            'buyerTaxes' => $buyerTaxes,
        ]);
    }

    public function create()
    {
        return view('admin.financial.buyer-taxes.create');
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

            $countryExists = BuyerTax::whereJsonContains('countries', $country)->first();
            if ($countryExists) {
                toastr()->error(translate(':country is already exists', ['country' => countries($country)]));
                return back()->withInput();
            }
        }

        $buyerTax = new BuyerTax();
        $buyerTax->name = $request->name;
        $buyerTax->rate = $request->rate;
        $buyerTax->countries = $request->countries;
        $buyerTax->save();

        toastr()->success(translate('Created Successfully'));
        return redirect()->route('admin.financial.buyer-taxes.index');
    }

    public function edit(BuyerTax $buyerTax)
    {
        return view('admin.financial.buyer-taxes.edit', [
            'buyerTax' => $buyerTax,
        ]);
    }

    public function update(Request $request, BuyerTax $buyerTax)
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

            $countryExists = BuyerTax::whereNot('id', $buyerTax->id)
                ->whereJsonContains('countries', $country)->first();
            if ($countryExists) {
                toastr()->error(translate(':country is already exists', ['country' => Country::get($country)]));
                return back()->withInput();
            }
        }

        $buyerTax->name = $request->name;
        $buyerTax->rate = $request->rate;
        $buyerTax->countries = $request->countries;
        $buyerTax->update();

        toastr()->success(translate('Updated Successfully'));
        return back();
    }

    public function destroy(BuyerTax $buyerTax)
    {
        $buyerTax->delete();
        toastr()->success(translate('Deleted Successfully'));
        return back();
    }
}
