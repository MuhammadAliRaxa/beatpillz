<?php

namespace App\Http\Controllers\Admin\Financial;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::all();
        return view('admin.financial.currencies.index', [
            'currencies' => $currencies,
        ]);
    }

    public function sortable(Request $request)
    {
        if (!$request->has('ids') || is_null($request->ids)) {
            return response()->json(['error' => translate('Failed to sort the table')]);
        }

        $ids = explode(',', $request->ids);
        foreach ($ids as $sortOrder => $id) {
            $currency = Currency::find($id);
            $currency->sort_id = ($sortOrder + 1);
            $currency->save();
        }

        return response()->json(['success' => true]);
    }

    public function makeDefault(Currency $currency)
    {
        abort_if($currency->isDefault(), 401);

        setEnv('DEFAULT_CURRENCY', $currency->code, true);

        toastr()->success(translate('The default currency has been updated'));
        return back();
    }

    public function create()
    {
        return view('admin.financial.currencies.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'icon' => ['required', 'mimes:png,jpg,jpeg,webp'],
            'code' => ['required', 'string', 'block_patterns', 'unique:currencies', 'max:10'],
            'symbol' => ['required', 'string', 'block_patterns', 'max:10'],
            'position' => ['required', 'integer', 'in:' . implode(',', array_keys(Currency::getCurrencyPositionOptions()))],
            'rate' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        try {
            $icon = imageUpload($request->file('icon'), 'images/currencies/', null, Str::slug($request->code));

            $currency = new Currency();
            $currency->code = $request->code;
            $currency->symbol = $request->symbol;
            $currency->position = $request->position;
            $currency->rate = $request->rate;
            $currency->icon = $icon;
            $currency->sort_id = (Currency::count() + 1);
            $currency->save();

            toastr()->success(translate('Created Successfully'));
            return redirect()->route('admin.financial.currencies.index');
        } catch (\Exception $e) {
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function edit(Currency $currency)
    {
        return view('admin.financial.currencies.edit', [
            'currency' => $currency,
        ]);
    }

    public function update(Request $request, Currency $currency)
    {
        $validator = Validator::make($request->all(), [
            'icon' => ['nullable', 'mimes:png,jpg,jpeg,webp'],
            'symbol' => ['required', 'string', 'block_patterns', 'max:10'],
            'position' => ['required', 'integer', 'in:' . implode(',', array_keys(Currency::getCurrencyPositionOptions()))],
            'rate' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        try {
            if ($request->hasFile('icon')) {
                $icon = imageUpload($request->file('icon'), 'images/currencies/', null, Str::slug($request->code), $currency->icon);
            } else {
                $icon = $currency->icon;
            }

            $currency->symbol = $request->symbol;
            $currency->position = $request->position;
            $currency->rate = $request->rate;
            $currency->icon = $icon;
            $currency->update();

            toastr()->success(translate('Updated Successfully'));
            return back();
        } catch (\Exception $e) {
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy(Currency $currency)
    {
        abort_if($currency->isDefault(), 401);

        removeFile(public_path($currency->icon));

        $currency->delete();
        toastr()->success(translate('Deleted Successfully'));
        return back();
    }
}