<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class AuthorController extends Controller
{
    public function becomeAnAuthor()
    {
        return theme_view('workspace.author');
    }

    public function becomeAnAuthorAction(Request $request)
    {
        $rules = [];

        if (@settings('links')->author_terms_link) {
            $rules['author_terms'] = ['required'];
        }

        if (@settings('referral')->status && @settings('links')->referral_terms_link) {
            $rules['referral_terms'] = ['required'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $level = Level::default()->with('badge')->first();

        if ($level) {
            $author = authUser();

            $author->level_id = $level->id;
            $author->is_author = User::AUTHOR;
            $author->update();

            if ($level->badge) {
                $author->addBadge($level->badge);
            }

            toastr()->success(translate('Congratulations! You are now and author'));
            return redirect()->route('workspace.dashboard');
        }

        return back();
    }
}
