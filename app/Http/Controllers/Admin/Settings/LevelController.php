<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;
use Validator;

class LevelController extends Controller
{
    public function index()
    {
        $levels = Level::orderbyDesc('id')->get();
        return view('admin.settings.levels.index', ['levels' => $levels]);
    }

    public function create()
    {
        return view('admin.settings.levels.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'block_patterns', 'max:255', 'unique:levels'],
            'min_earnings' => ['required', 'integer', 'min:1', 'unique:levels'],
            'fees' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        $level = new Level();
        $level->name = $request->name;
        $level->min_earnings = $request->min_earnings;
        $level->fees = $request->fees;
        $level->save();

        toastr()->success(translate('Created Successfully'));
        return redirect()->route('admin.settings.levels.index');
    }

    public function edit(Level $level)
    {
        return view('admin.settings.levels.edit', ['level' => $level]);
    }

    public function update(Request $request, Level $level)
    {
        $rules = [
            'name' => ['required', 'string', 'block_patterns', 'max:255', 'unique:levels,id,' . $level->id],
            'fees' => ['required', 'integer', 'min:0', 'max:100'],
        ];

        if (!$level->isDefault()) {
            $rules['min_earnings'] = ['required', 'integer', 'min:1', 'unique:levels,min_earnings,' . $level->id];
        } else {
            $request->min_earnings = 0;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $level->name = $request->name;
        $level->min_earnings = $request->min_earnings;
        $level->fees = $request->fees;
        $level->update();

        toastr()->success(translate('Updated Successfully'));
        return back();
    }

    public function destroy(Level $level)
    {
        if (!$level->isDefault()) {
            $level->delete();
            toastr()->success(translate('Deleted Successfully'));
        }
        return back();
    }
}