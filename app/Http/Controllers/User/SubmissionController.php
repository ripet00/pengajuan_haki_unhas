<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubmissionRequest;
use App\Models\Submission;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    // List user's submissions
    public function index() {
        $user = Auth::user();
        $submissions = Submission::where('user_id', $user->id)
                                ->with('reviewedByAdmin')
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);
        
        return view('user.submissions.index', compact('submissions'));
    }

    // Show create form
    public function create() {
        return view('user.submissions.create');
    }

    // Store initial submission
        /**
     * Store initial submission
     *
     * @param \App\Http\Requests\StoreSubmissionRequest|\Illuminate\Http\Request $request
     */

    public function store(StoreSubmissionRequest $request) {
        $user = Auth::user();
        /** @var \Illuminate\Http\UploadedFile $file */
        $file = $request->file('document');

        $path = $file->store('submissions', 'public');
        $submission = Submission::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'categories' => $request->categories,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'status' => 'pending',
            'revisi' => false,
        ]);
        return redirect()->route('user.submissions.show', $submission)->with('success', 'File berhasil diunggah. Menunggu review admin');
    }

    // Show a user's submission
    public function show(Submission $submission) {
        $this->authorizeOwnership($submission);
        return view('user.submissions.show', compact('submission'));
    }


    // Store initial submission
        /**
     * Store initial submission
     *
     * @param \App\Http\Requests\StoreSubmissionRequest|\Illuminate\Http\Request $request
     * @param \App\Models\Submission $submission
     */
    public function resubmit(StoreSubmissionRequest $request, Submission $submission) {
        $this->authorizeOwnership($submission);

        if ($submission->status !== 'rejected') {
            return back()->withErrors(['document' => 'Hanya submission yang berstatus rejected yang boleh direvisi.']);
        }

        // Delete old file
        if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
            Storage::disk('public')->delete($submission->file_path);
        }

        /** @var \Illuminate\Http\UploadedFile $file */
        $file = $request->file('document');
        $path = $file->store('submissions', 'public');

        $submission->update([
            'title' => $request->title,
            'categories' => $request->categories,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'status' => 'pending',
            'revisi' => true,
            'reviewed_at' => null,
            'rejection_reason' => null,
            'reviewed_by_admin_id' => null,
        ]);

        return redirect()->route('user.submissions.show', $submission)->with('success', 'File berhasil diunggah ulang. Menunggu review admin.');
    }

    protected function authorizeOwnership(Submission $submission) {
        if ($submission->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
