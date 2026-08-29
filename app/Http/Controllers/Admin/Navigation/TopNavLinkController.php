<?php

namespace App\Http\Controllers\Admin\Navigation;

use App\Http\Controllers\Controller;
use App\Models\TopNavLink;
use Illuminate\Http\Request;
use Validator;

class TopNavLinkController extends Controller
{
    public function index(Request $request)
    {
        $topNavLinks = TopNavLink::whereNull('parent_id')->with(['children' => function ($query) {
            $query->byOrder();
        }])->byOrder()->get();
        return view('admin.navigation.top-nav-links.index', ['topNavLinks' => $topNavLinks]);

    }

    public function nestable(Request $request)
    {
        if (!$request->has('ids') || is_null($request->ids)) {
            return response()->json(['error' => translate('Failed to sort the list')]);
        }

        $data = json_decode($request->ids, true);
        $i = 1;
        foreach ($data as $arr) {
            $menu = TopNavLink::find($arr['id']);
            $menu->update([
                'order' => $i,
                'parent_id' => null,
            ]);
            if (isset($arr['children'])) {
                $sub_i = 1;
                foreach ($arr['children'] as $children) {
                    $menu = TopNavLink::find($children['id']);
                    $menu->update([
                        'order' => $sub_i,
                        'parent_id' => $arr['id'],
                    ]);
                    $sub_i++;
                }
            }
            $i++;
        }

        return response()->json(['success' => true]);
    }

    public function create()
    {
        return view('admin.navigation.top-nav-links.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'link' => ['required', 'string', 'max:255'],
            'link_type' => ['required', 'integer', 'min:1', 'max:2'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        $countLinks = TopNavLink::all()->count();
        $topNavLink = TopNavLink::create([
            'name' => $request->name,
            'link' => $request->link,
            'link_type' => $request->link_type,
            'order' => ($countLinks + 1),
        ]);

        if ($topNavLink) {
            toastr()->success(translate('Created Successfully'));
            return redirect()->route('admin.navigation.top-nav-links.index');
        }
    }

    public function edit(TopNavLink $topNavLink)
    {
        return view('admin.navigation.top-nav-links.edit', ['topNavLink' => $topNavLink]);
    }

    public function update(Request $request, TopNavLink $topNavLink)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'link' => ['required', 'string', 'max:255'],
            'link_type' => ['required', 'integer', 'min:1', 'max:2'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $updateMenu = $topNavLink->update([
            'name' => $request->name,
            'link' => $request->link,
            'link_type' => $request->link_type,
        ]);

        if ($updateMenu) {
            toastr()->success(translate('Updated Successfully'));
            return back();
        }
    }

    public function destroy(TopNavLink $topNavLink)
    {
        $topNavLink->delete();
        toastr()->success(translate('Deleted Successfully'));
        return back();
    }
}
