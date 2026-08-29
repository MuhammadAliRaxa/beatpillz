<?php

namespace App\Http\Controllers\Admin\Members;

use App\Classes\Country;
use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\Level;
use App\Models\Referral;
use App\Models\Statement;
use App\Models\User;
use App\Models\UserBadge;
use App\Models\UserLoginLog;
use App\Models\WithdrawalMethod;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Validator;

class UserController extends Controller
{
    public function index()
    {
        $counters['active'] = User::active()->count();
        $counters['banned'] = User::banned()->count();

        $counters['kyc_verified'] = User::kycVerified()->count();
        $counters['kyc_unverified'] = User::kycUnVerified()->count();

        $counters['email_verified'] = User::emailVerified()->count();
        $counters['email_unverified'] = User::emailUnVerified()->count();

        $users = User::query();

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $users->where(function ($query) use ($searchTerm) {
                $query->where('firstname', 'like', $searchTerm)
                    ->orWhere('lastname', 'like', $searchTerm)
                    ->orWhere('username', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm)
                    ->orWhere('address', 'like', $searchTerm)
                    ->orWhere('profile_heading', 'like', $searchTerm)
                    ->orWhere('profile_description', 'like', $searchTerm)
                    ->orWhere('profile_contact_email', 'like', $searchTerm)
                    ->orWhere('profile_social_links', 'like', $searchTerm)
                    ->orWhere('facebook_id', 'like', $searchTerm)
                    ->orWhere('google_id', 'like', $searchTerm);
            });
        }

        if (request()->filled('role')) {
            $users->where('is_author', request('role'));
        }

        if (request()->filled('account_status')) {
            $users->where('status', request('account_status'));
        }

        if (request()->filled('kyc_status')) {
            $users->where('kyc_status', request('kyc_status'));
        }

        if (request()->filled('email_status')) {
            if (request('email_status') == 1) {
                $users->whereNotNull('email_verified_at');
            } else {
                $users->whereNull('email_verified_at');
            }
        }

        $users = $users->orderbyDesc('id')->paginate(50);
        $users->appends(request()->only(['search', 'role', 'account_status', 'kyc_status', 'email_status']));

        return view('admin.members.users.index', [
            'counters' => $counters,
            'users' => $users,
        ]);
    }

    public function create()
    {
        return view('admin.members.users.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'block_patterns', 'max:50'],
            'lastname' => ['required', 'string', 'block_patterns', 'max:50'],
            'username' => ['required', 'string', 'min:6', 'alpha_dash', 'username', 'block_patterns', 'max:50', 'unique:users'],
            'email' => ['required', 'string', 'email', 'indisposable', 'block_patterns', 'max:100', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        $author = false;
        $level = null;
        if ($request->has('author')) {
            $level = Level::default()->with('badge')->first();
            if ($level) {
                $author = true;
                $level = $level->id;
            }
        }

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_author' => $author,
            'level_id' => $level,
        ]);

        if ($user) {
            if (@settings('actions')->email_verification) {
                $user->forceFill(['email_verified_at' => Carbon::now()])->save();
            }
            toastr()->success(translate('Created Successfully'));
            return redirect()->route('admin.members.users.edit', $user->id);
        }
    }

    public function edit(User $user)
    {
        return view('admin.members.users.edit', $this->sharedData($user));
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'block_patterns', 'max:50'],
            'lastname' => ['required', 'string', 'block_patterns', 'max:50'],
            'username' => ['required', 'string', 'min:6', 'max:50', 'username', 'block_patterns', 'unique:users,username,' . $user->id],
            'email' => ['required', 'string', 'email', 'indisposable', 'block_patterns', 'max:100', 'unique:users,email,' . $user->id],
            'address_line_1' => ['nullable', 'max:255'],
            'address_line_2' => ['nullable', 'max:255'],
            'city' => ['nullable', 'max:150'],
            'state' => ['nullable', 'max:150'],
            'zip' => ['nullable', 'max:100'],
            'country' => ['nullable', 'string', 'in:' . implode(',', array_keys(Country::all()))],
            'exclusivity' => ['nullable', 'string', 'in:exclusive,non_exclusive'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $country = $request->country ?? null;

        $address = [
            'line_1' => $request->address_line_1,
            'line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => $country,
        ];

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->address = $address;
        $user->exclusivity = $request->exclusivity;
        $user->update();

        $user->addCountryBadge($country);
        $user->addExclusiveAuthorBadge();

        toastr()->success(translate('Updated Successfully'));
        return back();
    }

    public function login(User $user)
    {
        Auth::login($user);
        return redirect()->route('workspace.index');
    }

    public function destroy(User $user)
    {
        $user->deleteResources();
        $user->delete();
        toastr()->success(translate('Deleted Successfully'));
        return back();
    }

    public function sendMail(Request $request, User $user)
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
            $email = $user->email;
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
        } catch (Exception $e) {
            toastr()->error(translate('Sent error'));
            return back();
        }
    }

    public function showWithdrawalForm(User $user)
    {
        $withdrawalMethods = WithdrawalMethod::active()->get();
        return view('admin.members.users.withdrawal', $this->sharedData($user) + [
            'withdrawalMethods' => $withdrawalMethods,
        ]);
    }

    public function updateWithdrawal(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'withdrawal_method' => ['nullable', 'integer', 'exists:withdrawal_methods,id'],
            'withdrawal_account' => ['nullable', 'string', 'max:500', 'block_patterns'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        if (!is_null($request->withdrawal_method) && is_null($request->withdrawal_account)) {
            toastr()->error(translate('Withdrawal account cannot be empty'));
            return back();
        }

        if (is_null($request->withdrawal_method)) {
            $request->withdrawal_account = null;
        }

        $user->withdrawal_method_id = $request->withdrawal_method;
        $user->withdrawal_account = $request->withdrawal_account;
        $user->update();

        toastr()->success(translate('Updated Successfully'));
        return back();
    }

    public function showActionsForm(User $user)
    {
        return view('admin.members.users.actions', $this->sharedData($user));
    }

    public function updateActions(Request $request, User $user)
    {
        $request->status = ($request->has('status')) ? 1 : 0;
        $request->kyc_status = ($request->has('kyc_status')) ? 1 : 0;

        $google2faStatus = 0;
        if ($request->has('google2fa_status')) {
            if (!$user->google2fa_status) {
                toastr()->error(translate('Two-Factor authentication cannot activated from admin side'));
                return back();
            } else {
                $google2faStatus = 1;
            }
        }

        $user->kyc_status = $request->kyc_status;
        $user->google2fa_status = $google2faStatus;
        $user->status = $request->status;
        $user->update();

        $emailVerifiedAt = ($request->has('email_status')) ? Carbon::now() : null;
        $user->forceFill(['email_verified_at' => $emailVerifiedAt])->save();

        toastr()->success(translate('Updated Successfully'));
        return back();
    }

    public function balance(User $user)
    {
        return view('admin.members.users.balance', $this->sharedData($user));
    }

    public function balanceUpdate(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['required', 'string', 'in:credit,debit'],
            'amount' => ['required', 'regex:/^\d*(\.\d{2})?$/'],
            'title' => ['required', 'string', 'max:255'],

        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        $type = null;
        if ($request->type == "credit") {
            $user->increment('balance', $request->amount);
            $type = Statement::TYPE_CREDIT;
            toastr()->success(translate('Credited Successfully'));
        } elseif ($request->type == "debit") {
            $user->decrement('balance', $request->amount);
            $type = Statement::TYPE_DEBIT;
            toastr()->success(translate('Debited Successfully'));
        }

        if ($type) {
            $saleStatement = new Statement();
            $saleStatement->user_id = $user->id;
            $saleStatement->title = $request->title;
            $saleStatement->amount = $request->amount;
            $saleStatement->total = $request->amount;
            $saleStatement->type = $type;
            $saleStatement->save();
        }

        return back();
    }

    public function profile(User $user)
    {
        return view('admin.members.users.profile', $this->sharedData($user));
    }

    public function updateProfile(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'profile_cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'profile_heading' => ['nullable', 'string', 'block_patterns', 'max:255'],
            'profile_description' => ['nullable', 'string'],
            'profile_contact_email' => ['nullable', 'email', 'block_patterns', 'max:255'],
            'profile_social_links.*' => ['nullable', 'string', 'block_patterns', 'max:50'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        try {

            $profilesPath = 'images/profiles/' . strtolower(hash_encode($user->id)) . '/';

            if ($request->has('avatar')) {
                $avatar = $request->file('avatar');
                if (!checkImageSize($avatar, '120x120')) {
                    toastr()->error(translate('Avatar image must be 120x120px'));
                    return back();
                }
                $avatar = imageUpload($avatar, $profilesPath, '120x120', null, $user->profile_cover);
            } else {
                $avatar = $user->avatar;
            }

            if ($request->has('profile_cover')) {
                $profileCover = $request->file('profile_cover');
                if (!checkImageSize($profileCover, '1200x500')) {
                    toastr()->error(translate('Profile cover image must be 1200x500px'));
                    return back();
                }
                $profileCover = imageUpload($profileCover, $profilesPath, '1200x500', null, $user->profile_cover);
            } else {
                $profileCover = $user->profile_cover;
            }

            $socialLinks = [];
            foreach ($request->profile_social_links as $key => $socialLink) {
                if (!is_null($socialLink)) {
                    $socialLinks[$key] = $socialLink;
                }
            }

            $profileDescription = purifierClean($request->profile_description);

            $user->avatar = $avatar;
            $user->profile_cover = $profileCover;
            $user->profile_heading = $request->profile_heading;
            $user->profile_description = $profileDescription;
            $user->profile_contact_email = $request->profile_contact_email;
            $user->profile_social_links = count($socialLinks) > 0 ? $request->profile_social_links : null;
            $user->update();

            toastr()->success(translate('Updated Successfully'));
            return back();

        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back()->withInput();
        }

    }

    public function showPasswordForm(User $user)
    {
        return view('admin.members.users.password', $this->sharedData($user));
    }

    public function updatePassword(Request $request, User $user)
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

        $update = $user->update([
            'password' => bcrypt($request->get('new-password')),
        ]);

        if ($update) {
            toastr()->success(translate('Updated Successfully'));
            return back();
        }
    }

    public function referrals(User $user)
    {
        $referrals = Referral::where('author_id', $user->id)
            ->with('user');

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $referrals->whereHas('user', function ($query) use ($searchTerm) {
                $query->where('id', 'like', $searchTerm)
                    ->orWhere('firstname', 'like', $searchTerm)
                    ->orWhere('lastname', 'like', $searchTerm)
                    ->orWhere('username', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm)
                    ->orWhere('address', 'like', $searchTerm);
            });
        }

        $referrals = $referrals->orderbyDesc('id')->paginate(30);
        $referrals->appends(request()->only(['search']));

        return view('admin.members.users.referrals', $this->sharedData($user) + [
            'referrals' => $referrals,
        ]);
    }

    public function deleteReferral(User $user, $id)
    {
        $referral = Referral::where('id', $id)->where('author_id', $user->id)->firstOrFail();
        $referral->delete();
        toastr()->success(translate('Deleted Successfully'));
        return back();
    }

    public function makeAuthorFeatured(Request $request, User $user)
    {
        User::featuredAuthor()->update(['is_featured_author' => User::NOT_FEATURED_AUTHOR]);

        $user->is_featured_author = User::FEATURED_AUTHOR;
        $user->update();

        $badge = Badge::where('alias', Badge::FEATURED_AUTHOR_BADGE_ALIAS)->first();
        if ($badge) {
            $user->addBadge($badge);
        }

        toastr()->success(translate('The author is now featured Successfully'));
        return back();
    }

    public function removeAuthorFeatured(Request $request, User $user)
    {
        $user->is_featured_author = User::NOT_FEATURED_AUTHOR;
        $user->update();

        toastr()->success(translate('Featured author removed Successfully'));
        return back();
    }

    public function badges(User $user)
    {
        $badges = Badge::all();
        $userBadges = UserBadge::where('user_id', $user->id)->with('badge')->get();
        return view('admin.members.users.badges', $this->sharedData($user) + [
            'badges' => $badges,
            'userBadges' => $userBadges,
        ]);
    }

    public function addBadge(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'badge' => ['required', 'integer', 'exists:badges,id'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $badge = Badge::where('id', $request->badge)->firstOrFail();
        $userBadge = $user->badges->where('badge_id', $badge->id)
            ->where('badge_alias', $badge->alias)->first();
        if ($userBadge) {
            toastr()->error(translate('The user already has the selected badge'));
            return back();
        }

        $user->addBadge($badge);
        toastr()->success(translate('Badge has been added Successfully'));
        return back();
    }

    public function badgesSortable(Request $request, User $user)
    {
        if (!$request->has('ids') || is_null($request->ids)) {
            return response()->json(['error' => translate('Failed to sort the data')]);
        }
        $ids = explode(',', $request->ids);
        foreach ($ids as $sortOrder => $id) {
            $userBadge = $user->badges->where('id', $id)->first();
            $userBadge->sort_id = ($sortOrder + 1);
            $userBadge->update();
        }
        return response()->json(['success' => true]);
    }

    public function deleteBadge(Request $request, User $user, $id)
    {
        $userBadge = $user->badges->where('id', $id)->firstOrFail();
        $userBadge->delete();
        toastr()->success(translate('Deleted Successfully'));
        return back();
    }

    public function loginLogs(User $user)
    {
        $loginLogs = UserLoginLog::where('user_id', $user->id)->orderbyDesc('id')->get();
        return view('admin.members.users.login-logs', $this->sharedData($user) + [
            'loginLogs' => $loginLogs,
        ]);
    }

    public function apiKey(User $user)
    {
        return view('admin.members.users.api-key', $this->sharedData($user));
    }

    public function apiKeyGenerate(Request $request, User $user)
    {
        $apiKey = hash('sha256', hash_encode($user->id) . Str::random(16) . microtime());
        $user->api_key = $apiKey;
        $user->update();
        toastr()->success(translate('API key has been generated successfully'));
        return back();
    }

    private function sharedData($user)
    {
        $user = User::where('id', $user->id)
            ->withCount(['purchases' => function ($query) {
                $query->active();
            }, 'items'])->firstOrFail();

        $counters['total_withdrawal_amount'] = $user->withdrawals()->sum('amount');
        $counters['total_transactions_amount'] = $user->transactions()->paid()->sum('total');

        return [
            'user' => $user,
            'counters' => $counters,
        ];
    }
}