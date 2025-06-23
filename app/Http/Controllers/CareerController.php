<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Career;
use App\Models\Hiring;
use App\Models\SavedJob;
use App\Models\GoogleUser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Mail\InterviewScheduled;

class CareerController extends Controller
{
    private function getGoogleUserId()
    {
        $user = Auth::user();
        if (!$user || !$user->google_user_id) {
            return null;
        }
        return GoogleUser::where('google_id', $user->google_user_id)->first()->id ?? null;
    }

    public function index()
    {
        $hirings = Hiring::all();
        $savedJobs = [];
        $googleUserId = null;

        if (Auth::check()) {
            try {
                $googleUserId = $this->getGoogleUserId();
                if ($googleUserId) {
                    $savedJobs = SavedJob::where('google_user_id', $googleUserId)->pluck('hiring_id')->toArray();
                }
            } catch (\Exception $e) {
                Log::error('Error fetching saved hirings: ' . $e->getMessage());
            }
        }

        return view('careers', compact('hirings', 'savedJobs', 'googleUserId'));
    }

    public function show($slug)
    {
        $hiring = Hiring::where('slug', $slug)->firstOrFail();
        $googleUserId = $this->getGoogleUserId();
        $savedJobs = [];

        if ($googleUserId) {
            $savedJobs = SavedJob::where('google_user_id', $googleUserId)->pluck('hiring_id')->toArray();
        }

        $relatedJobs = Hiring::where('id', '!=', $hiring->id)->take(5)->get();

        return view('career_details', compact('hiring', 'savedJobs', 'relatedJobs', 'googleUserId'));
    }

    /**
     * Save a job for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveJob(Request $request)
    {
        $googleUserId = $this->getGoogleUserId();
        if (!$googleUserId) {
            Log::warning('Attempt to save job without authentication');
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $validator = Validator::make($request->all(), [
            'hiring_id' => 'required|exists:hirings,id',
        ]);

        if ($validator->fails()) {
            Log::warning('Invalid save job request', ['errors' => $validator->errors()]);
            return response()->json(['error' => $validator->errors()], 422);
        }

        $hiringId = $request->input('hiring_id');

        try {
            DB::beginTransaction();

            $savedJob = SavedJob::updateOrCreate(
                ['google_user_id' => $googleUserId, 'hiring_id' => $hiringId],
                ['saved' => true]
            );

            DB::commit();

            Log::info('Job saved successfully', ['google_user_id' => $googleUserId, 'hiring_id' => $hiringId]);
            return response()->json([
                'message' => 'Job saved successfully',
                'saved' => true,
                'job_id' => $hiringId
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving job', [
                'google_user_id' => $googleUserId,
                'hiring_id' => $hiringId,
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Unable to save job. Please try again later.'], 500);
        }
    }

    /**
     * Unsave a job for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unsaveJob(Request $request)
    {
        $googleUserId = $this->getGoogleUserId();
        if (!$googleUserId) {
            Log::warning('Attempt to unsave job without authentication');
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $validator = Validator::make($request->all(), [
            'hiring_id' => 'required|exists:hirings,id',
        ]);

        if ($validator->fails()) {
            Log::warning('Invalid unsave job request', ['errors' => $validator->errors()]);
            return response()->json(['error' => $validator->errors()], 422);
        }

        $hiringId = $request->input('hiring_id');

        try {
            DB::beginTransaction();

            $deleted = SavedJob::where('google_user_id', $googleUserId)
                ->where('hiring_id', $hiringId)
                ->delete();

            DB::commit();

            if ($deleted) {
                Log::info('Job unsaved successfully', ['google_user_id' => $googleUserId, 'hiring_id' => $hiringId]);
                return response()->json([
                    'message' => 'Job unsaved successfully',
                    'saved' => false,
                    'job_id' => $hiringId
                ]);
            } else {
                Log::warning('Attempt to unsave a job that was not saved', ['google_user_id' => $googleUserId, 'hiring_id' => $hiringId]);
                return response()->json(['error' => 'Job was not saved'], 404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error unsaving job', [
                'google_user_id' => $googleUserId,
                'hiring_id' => $hiringId,
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Unable to unsave job. Please try again later.'], 500);
        }
    }

    public function apply(Request $request)
    {
        $validatedData = $request->validate([
            'hiring_id' => 'required|exists:hirings,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'linkedin' => 'nullable|url|max:255',
            'experience' => 'required|string|in:0-1,1-3,3-5,5+',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'cover_letter' => 'nullable|string|max:5000',
            'agree_terms' => 'required|accepted',
        ]);

        // Check if the email has already applied for this hiring_id
        $existingApplication = Career::where('email', $validatedData['email'])
            ->where('hiring_id', $validatedData['hiring_id'])
            ->first();

        if ($existingApplication) {
            return redirect()->route('careers')->with('error', 'You have already applied for this position.');
        }

        // Store the resume file
        $resumePath = $request->file('resume')->store('resumes', 'public');

        // Create and save the Career model
        $application = new Career();
        $application->hiring_id = $validatedData['hiring_id'];
        $application->first_name = $validatedData['first_name'];
        $application->last_name = $validatedData['last_name'];
        $application->email = $validatedData['email'];
        $application->phone = $validatedData['phone'];
        $application->linkedin = $validatedData['linkedin'];
        $application->experience = $validatedData['experience'];
        $application->resume_path = $resumePath;
        $application->cover_letter = $validatedData['cover_letter'];
        $application->agree_terms = true;
        $application->save();

        // Send detailed email to the applicant
        $this->sendApplicationConfirmationEmail($application);

        // After successful processing
        return redirect()->route('careers')->with('success', 'Your application has been submitted successfully!');
    }

    private function sendApplicationConfirmationEmail(Career $application)
    {
        $hiringDetails = $application->hiring;
        // Create a Mailable class instead of sending raw view
        Mail::to($application->email)->send(new \App\Mail\ApplicationConfirmation($application, $hiringDetails));
    }

    public function getAllCareers()
    {
        $careers = Career::all();
        return view('all-careers', compact('careers'));
    }

    public function showApplicant($id)
    {
        // Find the leave record
        $career = Career::findOrFail($id);
        $this->markAsRead($career);

        return view('showApplicant', compact('career'));
    }

    private function markAsRead(Career $career)
    {
        if (!$career->is_read) {
            $career->is_read = true;
            $career->read_at = now();
            $career->save();
        }
    }

    public function scheduleInterview(Request $request, $id)
    {
        $validatedData = $request->validate([
            'interview_date' => 'required|date_format:Y-m-d\TH:i',
            'interview_location' => 'required|string|max:255',
        ]);

        $career = Career::findOrFail($id);
        $career->interview_date = $validatedData['interview_date'];
        $career->interview_location = $validatedData['interview_location'];
        $career->save();

        // Send email notification to the applicant
        $this->sendInterviewScheduleEmail($career);

        return response()->json(['message' => 'Interview scheduled successfully']);
    }

    private function sendInterviewScheduleEmail(Career $career)
    {
        Mail::to($career->email)->send(new InterviewScheduled($career));
    }

    /**
     * Get the count of unread career applications.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnreadCount()
    {
        $count = Career::where('is_read', false)->count();
        return response()->json(['count' => $count]);
    }
}
