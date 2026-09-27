<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Referral;

class ReferralController extends Controller
{
    public function index()
    {
        $referrals = Referral::where('author_id', authUser()->id);

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $referrals->whereHas('user', function ($query) use ($searchTerm) {
                $query->where('username', 'like', $searchTerm);
            });
        }

        $referrals = $referrals->with('user')->orderbyDesc('id')->paginate(20);
        $referrals->appends(request()->only(['search']));

        return theme_view('workspace.referrals', ['referrals' => $referrals]);
    }
}