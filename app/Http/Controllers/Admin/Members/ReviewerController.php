<?php

namespace App\Http\Controllers\Admin\Members;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Reviewer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class ReviewerController extends Controller
{
    public function index()
    {
        $reviewers = Reviewer::query();

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $reviewers->where('firstname', 'like', $searchTerm)
                ->OrWhere('lastname', 'like', $searchTerm)
                ->OrWhere('username', 'like', $searchTerm)
                ->OrWhere('email', 'like', $searchTerm);
        }

        $reviewers = $reviewers->with('categories')->orderbyDesc('id')->paginate(50);
        $reviewers->appends(request()->only(['search']));

        return view('admin.members.reviewers.index', [
            'reviewers' => $reviewers,
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.members.reviewers.create', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'block_patterns', 'max:50'],
            'lastname' => ['required', 'string', 'block_patterns', 'max:50'],
            'username' => ['required', 'string', 'min:5', 'alpha_dash', 'block_patterns', 'max:50', 'unique:reviewers'],
            'email' => ['required', 'string', 'email', 'indisposable', 'block_patterns', 'max:100', 'unique:reviewers'],
            'categories' => ['required', 'array'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        if ($request->has('categories')) {
            foreach ($request->categories as $category) {
                $category = Category::where('id', $category)->first();
                if (!$category) {
                    toastr()->error(translate('Invalid Category'));
                    return back();
                }
            }
        }

        $reviewer = Reviewer::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($reviewer) {
            $reviewer->categories()->sync($request->categories);
            toastr()->success(translate('Created Successfully'));
            return redirect()->route('admin.members.reviewers.edit', $reviewer->id);
        }
    }

    public function edit(Reviewer $reviewer)
    {
        $categories = Category::all();
        $reviewerCategoryIds = $reviewer->categories->pluck('id')->toArray();
        return view('admin.members.reviewers.edit', [
            'reviewer' => $reviewer,
            'categories' => $categories,
            'reviewerCategoryIds' => $reviewerCategoryIds,
        ]);
    }

    public function update(Request $request, Reviewer $reviewer)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'block_patterns', 'max:50'],
            'lastname' => ['required', 'string', 'block_patterns', 'max:50'],
            'username' => ['required', 'string', 'min:5', 'alpha_dash', 'block_patterns', 'max:50', 'unique:reviewers,username,' . $reviewer->id],
            'email' => ['required', 'email', 'indisposable', 'string', 'block_patterns', 'max:100', 'unique:reviewers,email,' . $reviewer->id],
            'avatar' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'categories' => ['required', 'array'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        if ($request->has('categories')) {
            foreach ($request->categories as $category) {
                $category = Category::where('id', $category)->first();
                if (!$category) {
                    toastr()->error(translate('Invalid Category'));
                    return back();
                }
            }
        }

        if ($request->has('avatar')) {
            $avatar = imageUpload($request->file('avatar'), 'images/avatars/reviewers/', '120x120', null, $reviewer->avatar);
        } else {
            $avatar = $reviewer->avatar;
        }

        $update = $reviewer->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'avatar' => $avatar,
        ]);

        if ($update) {
            $reviewer->categories()->sync($request->categories);
            toastr()->success(translate('Updated Successfully'));
            return back();
        }
    }

    public function login(Reviewer $reviewer)
    {
        Auth::guard('reviewer')->login($reviewer);
        return redirect()->route('reviewer.index');
    }

    public function destroy(Reviewer $reviewer)
    {
        removeFile($reviewer->avatar);
        $reviewer->delete();
        toastr()->success(translate('Deleted Successfully'));
        return back();
    }

    public function sendMail(Request $request, Reviewer $reviewer)
    {
        $validator = Validator::make($request->all(), [
            'subject' => ['required', 'string', 'block_patterns'],
            'reply_to' => ['required', 'email', 'block_patterns'],
            'message' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        if (!@settings('smtp')->status) {
            toastr()->error(translate('SMTP is not enabled'));
            return back()->withInput();
        }

        try {
            $email = $reviewer->email;
            $subject = $request->subject;
            $replyTo = $request->reply_to;
            $msg = $request->message;
            \Mail::send([], [], function ($message) use ($msg, $email, $subject, $replyTo) {
                $message->to($email)
                    ->replyTo($replyTo)
                    ->subject($subject)
                    ->html($msg);
            });
            toastr()->success(translate('Sent successfully'));
            return back();
        } catch (\Exception $e) {
            toastr()->error(translate('Sent error'));
            return back();
        }
    }

    public function showActionsForm(Reviewer $reviewer)
    {
        return view('admin.members.reviewers.actions', ['reviewer' => $reviewer]);
    }

    public function updateActions(Request $request, Reviewer $reviewer)
    {
        $google2faStatus = 0;
        if ($request->has('google2fa_status')) {
            if (!$reviewer->google2fa_status) {
                toastr()->error(translate('Two-Factor authentication cannot activated from admin side'));
                return back();
            } else {
                $google2faStatus = 1;
            }
        }

        $reviewer->google2fa_status = $google2faStatus;
        $reviewer->update();

        toastr()->success(translate('Updated Successfully'));
        return back();
    }

    public function showPasswordForm(Reviewer $reviewer)
    {
        return view('admin.members.reviewers.password', ['reviewer' => $reviewer]);
    }

    public function updatePassword(Request $request, Reviewer $reviewer)
    {
        $validator = Validator::make($request->all(), [
            'new-password' => ['required', 'string', 'min:6', 'confirmed'],
            'new-password_confirmation' => ['required'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $update = $reviewer->update([
            'password' => bcrypt($request->get('new-password')),
        ]);

        if ($update) {
            toastr()->success(translate('Updated Successfully'));
            return back();
        }
    }
}
