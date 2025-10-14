<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubmissionRequest;
use App\Models\Submission;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

/**
 * SubmissionController (User)
 *
 * NOTE: docblock @method membantu static analyser (Intelephense)
 * agar tidak menandai $this->middleware sebagai undefined.
 *
 * @method void middleware($middleware, array $options = [])
 */

class SubmissionController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    // Show create form
    public function create() {
        return view('user.submissions.create');
    }

    // Store initial submission
    public function store(StoreSubmissionRequest $request) {
        $user = Auth::user();
        $file = $request->file('document');

        $path = $file->store('submissions', 'public');
        $submission = Submission::create([
            'user_id' => $user->id,
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

    public function resubmit(StoreSubmissionRequest $request, Submission $submission) {
        $this->authorizeOwnership($submission);

        if ($submission->status !== 'denied') {
            return back()->withErrors(['document' => 'Hanya submission yang berstatus denied yang boleh direvisi.']);
        }

        // Delete old file
        if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
            Storage::disk('public')->delete($submission->file_path);
        }

        $file = $request->file('document');
        $path = $file->store('submissions', 'public');

        $submission->update([
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
