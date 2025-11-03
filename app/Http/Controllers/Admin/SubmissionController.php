<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewSubmissionRequest;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    protected function getCurrentAdmin()
    {
        return \App\Models\Admin::find(session('admin_id'));
    }

    // list submissions (filter optional)
    public function index(Request $request)
    {
        $q = Submission::with('user', 'reviewedByAdmin')->latest();

        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        $submissions = $q->paginate(20);
        return view('admin.submissions.index', compact('submissions'));
    }

    // view one submission (and download/open file)
    public function show(Submission $submission)
    {
        // Load the admin relationship manually if needed
        if ($submission->reviewed_by_admin_id) {
            $submission->load('reviewedByAdmin');
        }
        
        // Get submissions with similar titles (case-insensitive)
        $similarTitles = $submission->getSimilarTitles();
        
        return view('admin.submissions.show', compact('submission', 'similarTitles'));
    }

    // review action: set approved or denied with a comment
    public function review(ReviewSubmissionRequest $request, Submission $submission)
    {
        // only allow review if current status is pending
        if ($submission->status !== 'pending') {
            return back()->withErrors(['status' => 'Submission sudah direview sebelumnya.']);
        }

        $validatedData = $request->validated();
        $submission->status = $validatedData['status'];
        $submission->reviewed_at = now();
        $submission->reviewed_by_admin_id = session('admin_id'); // Use session admin_id
        $submission->rejection_reason = $validatedData['rejection_reason'] ?? null;
        $submission->revisi = false; // after admin review, reset revisi flag
        $submission->save();

        return redirect()->route('admin.submissions.show', $submission)->with('success', 'Review tersimpan.');
    }

    // download file - force download instead of opening in browser
    public function download(Submission $submission)
    {
        $filePath = storage_path('app/public/' . $submission->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download($filePath, $submission->file_name);
    }
}