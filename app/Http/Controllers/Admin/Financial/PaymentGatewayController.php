<?php

namespace App\Http\Controllers\Admin\Financial;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use Validator;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $paymentGateways = PaymentGateway::query();

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $paymentGateways->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', $searchTerm)
                    ->orWhere('alias', 'like', $searchTerm)
                    ->orWhere('credentials', 'like', $searchTerm);
            });
        }

        if (request()->filled('status')) {
            $paymentGateways->where('status', request('status'));
        }

        $paymentGateways = $paymentGateways->get();

        return view('admin.financial.payment-gateways.index', ['paymentGateways' => $paymentGateways]);
    }

    public function sortable(Request $request)
    {
        if (!$request->has('ids') || is_null($request->ids)) {
            return response()->json(['error' => translate('Failed to sort the table')]);
        }

        $ids = explode(',', $request->ids);
        foreach ($ids as $sortOrder => $id) {
            $paymentGateway = PaymentGateway::find($id);
            $paymentGateway->sort_id = ($sortOrder + 1);
            $paymentGateway->save();
        }

        return response()->json(['success' => true]);
    }

    public function edit(PaymentGateway $paymentGateway)
    {
        return view('admin.financial.payment-gateways.edit', ['paymentGateway' => $paymentGateway]);
    }

    public function update(Request $request, PaymentGateway $paymentGateway)
    {
        $rules = [
            'logo' => ['nullable', 'mimes:png,jpg,jpeg,webp'],
            'name' => ['required', 'string', 'block_patterns', 'max:255'],
        ];

        if (!$paymentGateway->isAccountBalance()) {
            $rules['fees'] = ['required', 'integer', 'min:0', 'max:100'];
        } else {
            $request->fees = 0;
        }

        if (!$paymentGateway->isAccountBalance() && !$paymentGateway->isManual()) {
            $rules['charge_currency'] = ['nullable', 'string', 'max:10', 'required_with:charge_rate'];
            $rules['charge_rate'] = ['nullable', 'numeric', 'required_with:charge_currency'];
        } else {
            $request->charge_currency = null;
            $request->charge_rate = null;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        if ($paymentGateway->mode) {
            if (!in_array($request->mode, PaymentGateway::getModes())) {
                toastr()->error(translate('Invalid mode'));
                return back();
            }
        } else {
            $request->mode = null;
        }

        if ($request->charge_currency == defaultCurrency()->code) {
            toastr()->error(translate('Charge currency should be different from website currency'));
            return back();
        }

        if (!$paymentGateway->isManual()) {
            $request->instructions = null;
            foreach ($request->credentials as $key => $value) {
                if (!array_key_exists($key, (array) $paymentGateway->credentials)) {
                    toastr()->error(translate('Credentials error'));
                    return back();
                }
                if ($request->has('status')) {
                    if (empty($value)) {
                        toastr()->error(translate(':key cannot be empty', ['key' => str_replace('_', ' ', ucfirst($key))]));
                        return back();
                    }
                }
            }
        } else {
            if ($request->has('status') && !$paymentGateway->isAccountBalance()) {
                if (empty($request->instructions)) {
                    toastr()->error(translate('Instructions cannot be empty'));
                    return back();
                }
            }
            $request->credentials = null;
        }

        if ($request->has('logo')) {
            $logo = imageUpload($request->file('logo'), 'images/payment-gateways/', null, $paymentGateway->alias, $paymentGateway->logo);
        } else {
            $logo = $paymentGateway->logo;
        }

        $request->status = $request->has('status') ? PaymentGateway::STATUS_ACTIVE : PaymentGateway::STATUS_DISABLED;

        $paymentGateway->update([
            'name' => $request->name,
            'logo' => $logo,
            'fees' => $request->fees,
            'charge_currency' => $request->charge_currency,
            'charge_rate' => $request->charge_rate,
            'credentials' => $request->credentials,
            'instructions' => $request->instructions,
            'mode' => $request->mode,
            'status' => $request->status,
        ]);

        toastr()->success(translate('Updated Successfully'));
        return back();
    }

}