<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Classes\Country;
use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\Level;
use Illuminate\Http\Request;
use Str;
use Validator;

class BadgeController extends Controller
{
    public function index()
    {
        $badges = Badge::query();

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $badges->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', $searchTerm)
                    ->OrWhere('alias', 'like', $searchTerm)
                    ->OrWhere('title', 'like', $searchTerm);
            });
        }

        if (request()->filled('type')) {
            if (request('type') == 'none') {
                $badges->whereNotIn('alias', [
                    Badge::COUNTRY_BADGE_ALIAS,
                    Badge::AUTHOR_LEVEL_BADGE_ALIAS,
                    Badge::MEMBERSHIP_YEARS_BADGE_ALIAS,
                ]);
            } elseif (request('type') == 'countries') {
                $badges->countryBadge();
            } elseif (request('type') == 'author_levels') {
                $badges->authorLevelBadge();
            } elseif (request('type') == 'membership_years') {
                $badges->membershipYearsBadge();
            }
        }

        if (!licenseType(2) || !@settings('premium')->status) {
            $badges->whereNotIn('alias', ['premiumer', 'premium_membership']);
        }

        $badges = $badges->paginate(50);
        $badges->appends(request()->only(['search']));

        return view('admin.settings.badges.index', ['badges' => $badges]);
    }

    public function create()
    {
        $levels = Level::all();
        return view('admin.settings.badges.create', ['levels' => $levels]);
    }

    public function store(Request $request)
    {
        $rules = [
            'badge_image' => ['required', 'mimes:png,svg', 'max:2048'],
            'name' => ['required', 'string', 'block_patterns', 'max:255', 'unique:badges'],
            'title' => ['nullable', 'string', 'block_patterns', 'max:255'],
            'type' => ['nullable', 'string', 'in:countries,author_levels,membership_years'],
        ];

        if ($request->has('type') && !is_null($request->type)) {
            if ($request->type == "countries") {
                $rules['country'] = ['required', 'string', 'in:' . implode(',', array_keys(Country::all())), 'unique:badges,country'];
                $alias = Badge::COUNTRY_BADGE_ALIAS;
            } elseif ($request->type == "author_levels") {
                $rules['author_level'] = ['required', 'integer', 'unique:badges,level_id', 'exists:levels,id'];
                $alias = Badge::AUTHOR_LEVEL_BADGE_ALIAS;
            } elseif ($request->type == "membership_years") {
                $rules['membership_years'] = ['required', 'integer', 'unique:badges,membership_years', 'min:1'];
                $alias = Badge::MEMBERSHIP_YEARS_BADGE_ALIAS;
            } else {
                return back()->withInput();
            }
        } else {
            $alias = Str::slug($request->name, '_');
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        try {
            $image = imageUpload($request->file('badge_image'), 'images/badges/');
            $badge = new Badge();
            $badge->name = $request->name;
            $badge->alias = $alias;
            $badge->title = $request->title;
            $badge->image = $image;
            $badge->country = $request->country ?? null;
            $badge->level_id = $request->author_level ?? null;
            $badge->membership_years = $request->membership_years ?? null;
            $badge->save();
            toastr()->success(translate('Created Successfully'));
            return redirect()->route('admin.settings.badges.index');
        } catch (\Exception $e) {
            toastr()->error($e->getMessage());
            return back()->withInput();
        }

    }

    public function edit(Badge $badge)
    {
        $levels = Level::all();
        return view('admin.settings.badges.edit', [
            'badge' => $badge,
            'levels' => $levels,
        ]);
    }

    public function update(Request $request, Badge $badge)
    {
        $validator = Validator::make($request->all(), [
            'badge_image' => ['nullable', 'mimes:png,svg', 'max:2048'],
            'name' => ['required', 'string', 'block_patterns', 'max:255', 'unique:badges,name,' . $badge->id],
            'title' => ['nullable', 'string', 'block_patterns', 'max:255'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        try {
            $image = ($request->has('badge_image')) ? imageUpload($request->file('badge_image'), 'images/badges/', null, null, $badge->image) : $badge->image;
            $badge->name = $request->name;
            $badge->title = $request->title;
            $badge->image = $image;
            $badge->update();
            toastr()->success(translate('Updated Successfully'));
            return back();
        } catch (\Exception $e) {
            toastr()->error($e->getMessage());
            return back()->withInput();
        }

    }

    public function destroy(Badge $badge)
    {
        abort_if($badge->is_permanent, 403);
        removeFile(public_path($badge->image));
        $badge->delete();
        toastr()->success(translate('Deleted Successfully'));
        return back();
    }
}