<?php

namespace App\Http\Controllers;

use App\Actions\CreateReviewAction;
use App\Http\Requests\ReviewRequest;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function __construct(private CreateReviewAction $createReview)
    {
        $this->middleware(['auth', 'verified']);
    }

    public function store(ReviewRequest $request): RedirectResponse
    {
        $this->createReview->execute($request->user(), $request->validated());

        return back()->with('status', 'Review submitted for moderation.');
    }
}
