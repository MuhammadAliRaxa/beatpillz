<?php

namespace App\Http\Controllers\Admin\Financial;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalMethod;
use Illuminate\Http\Request;
use Validator;

class WithdrawalMethodController extends Controller
{
    public function index()
    {
        $withdrawalMethods = WithdrawalMethod::all();
        return view('admin.financial.withdrawal-methods.index', ['withdrawalMethods' => $withdrawalMethods]);
    }

    public function sortable(Request $request)
    {
        if (!$request->has('ids') || is_null($request->ids)) {
            return response()->json(['error' => translate('Failed to sort the table')]);
        }

        $ids = explode(',', $request->ids);
        foreach ($ids as $sortOrder => $id) {
            $withdrawalMethod = WithdrawalMethod::find($id);
            $withdrawalMethod->sort_id = ($sortOrder + 1);
            $withdrawalMethod->save();
        }

        return response()->json(['success' => true]);
    }

    public function create()
    {
        return view('admin.financial.withdrawal-methods.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:withdrawal_methods'],
            'minimum' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        $request->status = ($request->has('status')) ? true : false;
        $request->minimum = ($request->has('minimum') && !is_null($request->minimum)) ? $request->minimum : "0.00";

        $withdrawalMethod = new WithdrawalMethod();
        $withdrawalMethod->name = $request->name;
        $withdrawalMethod->minimum = $request->minimum;
        $withdrawalMethod->description = $request->description;
        $withdrawalMethod->status = $request->status;
        $withdrawalMethod->sort_id = WithdrawalMethod::all()->count() + 1;
        $withdrawalMethod->save();

        toastr()->success(translate('Created Successfully'));
        return redirect()->route('admin.financial.withdrawal-methods.index');
    }

    public function edit(WithdrawalMethod $withdrawalMethod)
    {
        return view('admin.financial.withdrawal-methods.edit', ['withdrawalMethod' => $withdrawalMethod]);
    }

    public function update(Request $request, WithdrawalMethod $withdrawalMethod)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:withdrawal_methods,id,' . $withdrawalMethod->id],
            'minimum' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        $request->status = ($request->has('status')) ? true : false;
        $request->minimum = ($request->has('minimum') && !is_null($request->minimum)) ? $request->minimum : "0.00";

        $withdrawalMethod->name = $request->name;
        $withdrawalMethod->minimum = $request->minimum;
        $withdrawalMethod->description = $request->description;
        $withdrawalMethod->status = $request->status;
        $withdrawalMethod->update();

        toastr()->success(translate('Updated Successfully'));
        return back();
    }

    public function destroy(WithdrawalMethod $withdrawalMethod)
    {
        $withdrawalMethod->delete();
        toastr()->success(translate('Deleted Successfully'));
        return back();
    }

}
