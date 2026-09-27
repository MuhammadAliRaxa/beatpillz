<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemHistory extends Model
{
    use HasFactory;

    const TITLE_SUBMISSION = "Submission";
    const TITLE_TRUST_SUBMISSION = "Trust Submission";
    const TITLE_SUBMISSION_APPROVED = "Submission Approved";
    const TITLE_RESUBMISSION = "Resubmission";
    const TITLE_RESUBMISSION_APPROVED = "Resubmission Approved";
    const TITLE_SOFT_REJECTION = "Soft Rejection";
    const TITLE_HARD_REJECTION = "Hard Rejection";

    const TITLE_UPDATE_SUBMISSION = "Update Submission";
    const TITLE_TRUST_UPDATE = "Trust Update";
    const TITLE_UPDATE_APPROVED = "Update Approved";
    const TITLE_UPDATE_REJECTED = "Update Rejected";

    protected $fillable = [
        'author_id',
        'reviewer_id',
        'admin_id',
        'item_id',
        'title',
        'body',
    ];

    protected $with = [
        'author',
        'admin',
        'reviewer',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
