<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\City;
use App\Models\State;
use App\Models\Course;
use App\Models\College;
use App\Models\LeadSource;
use App\Models\User;
use App\Models\Qualification;
use App\Models\Intake;
use App\Models\Priority;
use App\Models\LeadStage;
use App\Models\LeadDocument;
use App\Models\LeadCommunication;
use App\Models\AdmissionRequest;
use App\Models\Consultant;
use App\Models\ContactStage;
use App\Models\DocumentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Spatie\Permission\Traits\HasRoles;

class LeadController extends Controller
{
    use HasRoles;
    public function index()
    {
        $user = Auth::user()->load('roles');

        // 🔍 DEBUGGING - Add this temporarily
        Log::info('=== LEAD ACCESS DEBUG ===');
        Log::info('User ID: ' . $user->id);
        Log::info('User Name: ' . $user->name);
        Log::info('User Email: ' . $user->email);
        Log::info('User Roles: ' . json_encode($user->getRoleNames()));
        Log::info('Is Consultant: ' . ($user->hasRole('consultant') ? 'YES' : 'NO'));

        // Find consultant record by email
        $consultant = \App\Models\Consultant::where('email', $user->email)->first();
        Log::info('Consultant Record Found: ' . ($consultant ? 'YES (ID: ' . $consultant->id . ')' : 'NO'));

        $leadsQuery = Lead::with([
            'city',
            'state',
            'leadSource',
            'consultant',
            'qualification',
            'intake',
            'priority',
            'counsellor'
        ])->latest();

        // Check total leads before filter
        $totalLeads = $leadsQuery->count();
        Log::info('Total Leads in DB: ' . $totalLeads);

        // 🔐 ROLE-BASED FILTERING
        if ($user->hasRole('consultant')) {
            if ($consultant) {
                // Show leads assigned to this consultant
                $leadsQuery->where('consultant_id', $consultant->id);
                Log::info('Filtering by consultant_id: ' . $consultant->id);
            } else {
                // No consultant record - show no leads
                $leadsQuery->whereRaw('1 = 0');
                Log::info('No consultant record found - showing no leads');
            }
        }

        $leads = $leadsQuery->paginate(15);

        // Check leads after filter
        Log::info('Leads After Filter: ' . $leads->count());
        Log::info('=== END DEBUG ===');

        // Group leads by status
        $leadsByStatus = $leads->groupBy('status');

        // Status config for UI
        $statuses = [
            'new' => ['label' => 'New', 'color' => 'warning'],
            'contacted' => ['label' => 'Contacted', 'color' => 'info'],
            'qualified' => ['label' => 'Qualified', 'color' => 'primary'],
            'proposal' => ['label' => 'Proposal', 'color' => 'purple'],
            'negotiation' => ['label' => 'Negotiation', 'color' => 'orange'],
            'won' => ['label' => 'Won', 'color' => 'success'],
            'lost' => ['label' => 'Lost', 'color' => 'danger'],
        ];

        $states = State::orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        $courses = Course::where('status', true)->get();
        $leadsources = LeadSource::where('status', true)->get();
        $qualifications = Qualification::where('status', 1)->orderBy('name')->get();
        $intakes = Intake::where('status', 1)->orderBy('name', 'desc')->get();
        $priorities = Priority::where('status', 1)->get();



        return view('pages.leads.index', compact(
            'leads',
            'leadsByStatus',
            'statuses',
            'states',
            'cities',
            'courses',
            'leadsources',
            'qualifications',
            'intakes',
            'priorities'
        ));
    }
    public function create()
    {
        $states = State::all();
        $cities = City::all();
        $courses = Course::where('status', true)->get();
        $leadSources = LeadSource::where('status', true)->get();
        $consultants = Consultant::where('status', true)->orderBy('name')->get();
        $qualifications = Qualification::where('status', 1)->orderBy('name')->get();
        $intakes = Intake::where('status', 1)->orderBy('name', 'desc')->get();
        $priorities = Priority::where('status', 1)->get();

        return view('leads.create', compact(
            'states',
            'cities',
            'courses',
            'leadSources',
            'consultants',
            'qualifications',
            'intakes',
            'priorities'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'city_id' => 'nullable|exists:cities,id',
            'state_id' => 'nullable|exists:states,id',
            'qualification_id' => 'nullable|exists:qualifications,id',
            'interested_course_id' => 'nullable|exists:courses,id',
            'preferred_intake_id' => 'nullable|exists:intakes,id',
            'priority_id' => 'nullable|exists:priorities,id',
            'lead_source_id' => 'nullable|exists:lead_sources,id',
            'notes' => 'nullable|string',
            'consultant_id' => 'nullable|exists:users,id',
        ]);

        $lead = Lead::create($validated);

        return redirect()->route('leads.index')
            ->with('success', 'Lead created successfully!');
    }
    /**
     * Check if current user can access this lead
     */
    private function canAccessLead(Lead $lead)
    {
        $user = Auth::user();

        // ✅ Clear and explicit
        if ($user->hasAnyRole(['superadmin', 'admin'])) {
            return true;
        }

        if ($user->hasRole('consultant')) {
            return $lead->consultant && $lead->consultant->email === $user->email;
        }

        return false;
    }
    public function show(Lead $lead)
    {
        // 🔐 Authorization check
        if (!$this->canAccessLead($lead)) {
            abort(403, 'Unauthorized access to this lead.');
        }
        $lead->load([
            'city',
            'state',
            'course',
            'leadSource',
            'consultant',
            'qualification',
            'intake',
            'priority',
            'currentStage',
            'stages',
            'documents',
            'communications',
            'admissionRequests.college',
            'admissionRequests.course'
        ]);

        // ✅ Only consultant role - no counsellor
        $consultants = Consultant::where('status', true)->orderBy('name')->get();
        $colleges = College::where('status', true)->orderBy('name')->get();
        $courses = Course::where('status', true)->orderBy('name')->get();
        $priorities = Priority::where('status', 1)->orderBy('name')->get();
        $documentTypes = DocumentSetting::where('status', 1)->orderBy('name')->get();
        $stages = ContactStage::where('status', 1)->orderBy('name')->get();

        $communicationTypes = \App\Models\CommunicationLog::active()
            ->orderBy('name')
            ->pluck('name');

        $admissionStatuses = [
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'under_review' => 'Under Review',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected',
            'withdrawn' => 'Withdrawn'
        ];

        $statuses = [
            'new' => ['label' => 'New', 'color' => 'warning'],
            'contacted' => ['label' => 'Contacted', 'color' => 'info'],
            'qualified' => ['label' => 'Qualified', 'color' => 'primary'],
            'proposal' => ['label' => 'Proposal', 'color' => 'purple'],
            'negotiation' => ['label' => 'Negotiation', 'color' => 'orange'],
            'won' => ['label' => 'Won', 'color' => 'success'],
            'lost' => ['label' => 'Lost', 'color' => 'danger'],
        ];

        return view('pages.leads.lead-details', compact(
            'lead',
            'consultants',
            'colleges',
            'courses',
            'stages',
            'documentTypes',
            'communicationTypes',
            'admissionStatuses',
            'statuses',
            'priorities'
        ));
    }

    public function edit(Lead $lead)
    {
        if (!$this->canAccessLead($lead)) {
            abort(403, 'Unauthorized access.');
        }
        $states = State::all();
        $cities = City::all();
        $courses = Course::where('status', true)->get();
        $leadSources = LeadSource::where('status', true)->get();
        $consultants = Consultant::where('status', true)->orderBy('name')->get();
        $qualifications = Qualification::where('status', 1)->orderBy('name')->get();
        $intakes = Intake::where('status', 1)->orderBy('name', 'desc')->get();
        $priorities = Priority::where('status', 1)->get();
        return view('leads.edit', compact(
            'lead',
            'states',
            'cities',
            'courses',
            'leadSources',
            'consultants',
            'qualifications',
            'intakes',
            'priorities'
        ));
    }

    public function update(Request $request, Lead $lead)
    {
        if (!$this->canAccessLead($lead)) {
            abort(403, 'Unauthorized access.');
        }
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'city_id' => 'nullable|exists:cities,id',
            'state_id' => 'nullable|exists:states,id',
            'qualification_id' => 'nullable|exists:qualifications,id',
            'interested_course_id' => 'nullable|exists:courses,id',
            'preferred_intake_id' => 'nullable|exists:intakes,id',
            'priority_id' => 'nullable|exists:priorities,id',
            'lead_source_id' => 'nullable|exists:lead_sources,id',
            'notes' => 'nullable|string',
            'consultant_id' => 'nullable|exists:users,id',
            'counsellor_id' => 'nullable|exists:users,id',
        ]);

        $lead->update($validated);

        return redirect()->route('leads.index')
            ->with('success', 'Lead updated successfully!');
    }

    public function destroy(Lead $lead)
    {
        if (!$this->canAccessLead($lead)) {
            abort(403, 'Unauthorized access.');
        }
        $lead->delete();
        return redirect()->route('leads.index')
            ->with('success', 'Lead deleted successfully!');
    }

    // ============================================
    // NEW FEATURE METHODS
    // ============================================

    /**
     * Update lead stage/pipeline status
     */
    public function updateStage(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'stage' => 'required|in:new,contacted,qualified,proposal,negotiation,won,lost',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Create stage history record
        $lead->stages()->create([
            'stage' => $validated['stage'],
            'notes' => $validated['notes'] ?? null,
            'updated_by' => Auth::id(),
            'completed_at' => in_array($validated['stage'], ['won', 'lost']) ? now() : null
        ]);

        // Update main lead status for quick filtering
        $lead->update(['status' => $validated['stage']]);

        return back()->with('success', 'Lead stage updated to "' . ucfirst($validated['stage']) . '" successfully!');
    }

    /**
     * Assign consultant to lead
     */
    public function assignCounsellor(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'consultant_id' => 'required|exists:consultants,id',
        ]);

        $consultant = Consultant::find($validated['consultant_id']);

        // Verify consultant has a user account
        $user = User::where('email', $consultant->email)->first();

        if (!$user) {
            return back()->with('error', 'Consultant does not have a login account. Please create user account first.');
        }

        $lead->update([
            'consultant_id' => $consultant->id,
            'assigned_at' => now()
        ]);

        $lead->communications()->create([
            'type' => 'note',
            'content' => 'Consultant assigned: ' . $consultant->name,
            'created_by' => Auth::id(),
            'status' => 'completed'
        ]);

        return back()->with('success', 'Consultant assigned successfully!');
    }
    /**
     * Upload document for lead
     */
    public function uploadDocument(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'document_type' => [
                'required',
                'string',
                Rule::unique('documents', 'document_type')
                    ->where('lead_id', $lead->id)
                    // Optional: Only block if previous doc is still pending/verified
                    // Remove the where() if you want to block regardless of status
                    ->where(function ($query) {
                        $query->whereNull('is_verified') // pending
                            ->orWhere('is_verified', true); // already verified
                    })
            ],
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp|max:10240',
            'remarks' => 'nullable|string|max:500'
        ], [
            'document_type.unique' => 'A document of this type has already been uploaded for this lead.'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('leads/' . $lead->id . '/documents', 'public');

            $lead->documents()->create([
                'document_type' => $validated['document_type'],
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_size' => $this->formatFileSize($file->getSize()),
                'uploaded_by' => Auth::id(),
                'remarks' => $validated['remarks'] ?? null,
                'is_verified' => null
            ]);
        }

        return back()->with('success', 'Document uploaded successfully!');
    }


    /**
     * Delete a document
     */
    public function deleteDocument(LeadDocument $document)
    {
        // Delete physical file
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return back()->with('success', 'Document deleted successfully!');
    }

    /**
     * Add communication log entry
     */
    public function addCommunication(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'type' => 'required|in:call,email,meeting,note,whatsapp',
            'direction' => 'nullable|in:inbound,outbound',
            'call_status' => 'nullable|string|max:100',
            'subject' => 'nullable|string|max:255',
            'content' => 'required|string',
            'assigned_to' => 'nullable|exists:users,id'
        ]);

        $lead->communications()->create([
            'type' => $validated['type'],
            'direction' => $validated['direction'] ?? null,
            'call_status' => $validated['call_status'] ?? null,
            'subject' => $validated['subject'] ?? null,
            'content' => $validated['content'],
            'created_by' => Auth::id(),
            'assigned_to' => $validated['assigned_to'] ?? null
        ]);

        return back()->with('success', 'Communication logged successfully!');
    }

    /**
     * Update communication status (e.g., mark as completed)
     */
    public function updateCommunication(Request $request, LeadCommunication $communication)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,cancelled'
        ]);

        $communication->update([
            'status' => $validated['status'],
            'completed_at' => $validated['status'] === 'completed' ? now() : null
        ]);

        return back()->with('success', 'Communication status updated!');
    }

    /**
     * Delete communication entry
     */
    public function deleteCommunication(LeadCommunication $communication)
    {
        $communication->delete();
        return back()->with('success', 'Communication entry deleted!');
    }

    /**
     * Create new admission request for lead
     */


    /**
     * Update admission request status and details
     */
    public function updateAdmissionStatus(Request $request, AdmissionRequest $admission)
    {
        try {
            Log::info("=== Admission Status Update Attempt ===");
            Log::info("Admission ID: {$admission->id}");
            Log::info("Request status: " . $request->input('status'));
            Log::info("Current status: {$admission->status}");

            $admission->load('lead');

            if (!$admission->lead) {
                Log::error("Lead not found for admission #{$admission->id}");
                return back()->with('error', 'Associated lead not found!');
            }

            $validated = $request->validate([
                'status' => 'required|in:draft,submitted,under_review,accepted,rejected,withdrawn',
            ]);

            $oldStatus = $admission->status;
            $newStatus = $validated['status'];

            $updated = $admission->update([
                'status' => $newStatus,
                'submitted_date' => $newStatus === 'submitted' && !$admission->submitted_date
                    ? now()
                    : $admission->submitted_date
            ]);

            Log::info("Update result: " . ($updated ? 'SUCCESS' : 'FAILED'));

            if ($admission->lead) {
                $admission->lead->communications()->create([
                    'type' => 'note',
                    'content' => "Admission request #{$admission->id} status: '{$oldStatus}' → '{$newStatus}'",
                    'created_by' => Auth::id(),
                    'status' => 'completed'
                ]);
            }

            return back()->with('success', 'Admission status updated to "' . ucfirst(str_replace('_', ' ', $newStatus)) . '"!');
        } catch (\Exception $e) {
            Log::error('Admission status update ERROR: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return back()->with('error', 'Failed: ' . $e->getMessage());
        }
    }



    // ============================================
    // EXISTING API METHOD (unchanged)
    // ============================================

    public function getCitiesByState($stateId)
    {
        $cities = City::where('state_id', $stateId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($cities);
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    /**
     * Format file size in human readable format
     */
    private function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes / (1 << (10 * $pow)), 2) . ' ' . $units[$pow];
    }
    public function createAdmissionRequest(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'college_id' => 'required|exists:colleges,id',
            'course_id' => 'required|exists:courses,id',
            'consultant_id' => 'nullable|exists:users,id'  // Optional counselor assignment
        ]);

        $admission = $lead->admissionRequests()->create([
            'college_id' => $validated['college_id'],
            'course_id' => $validated['course_id'],
            'submitted_by' => Auth::id(),  // ✅ Fixed
            'status' => 'submitted'
        ]);

        // ✅ Optionally assign counselor if selected
        if (!empty($validated['consultant_id'])) {
            $lead->update(['consultant_id' => $validated['consultant_id']]);
        }

        return back()->with('success', 'Admission request created! Reference: ADM-' . $admission->id);
    }
    /**
     * Verify/Unverify a document
     */
    /**
     * Verify a document
     */
    public function verifyDocument(Request $request, Lead $lead, LeadDocument $document)
    {
        if ($document->lead_id !== $lead->id) {
            return back()->with('error', 'Document does not belong to this lead!');
        }

        $document->update([
            'is_verified' => 1,  // ✅ Use integer 1
            'verified_at' => now(),
            'verified_by' => Auth::id()
        ]);

        return back()->with('success', 'Document verified successfully!');
    }

    /**
     * Reject a document
     */
    public function rejectDocument(Request $request, Lead $lead, LeadDocument $document)
    {
        if ($document->lead_id !== $lead->id) {
            return back()->with('error', 'Document does not belong to this lead!');
        }

        $document->update([
            'is_verified' => 0,  // ✅ Use integer 0
            'verified_at' => now(),
            'verified_by' => Auth::id()
        ]);

        return back()->with('success', 'Document rejected successfully!');
    }
    public function sendMessage(Request $request, Lead $lead)
    {
        try {
            $validated = $request->validate([
                'message_type' => 'required|in:email,sms',
                'to' => 'required',
                'subject' => 'nullable|string|max:255',
                'content' => 'required|string',
            ]);

            if ($validated['message_type'] === 'email') {
                // Additional email-specific validation
                $request->validate([
                    'to' => 'required|email',
                    'subject' => 'required|string|max:255',
                ]);

                // Send email using Laravel Mail facade
                Mail::raw($validated['content'], function ($message) use ($validated) {
                    $message->to($validated['to'])
                        ->subject($validated['subject'])
                        ->from(config('mail.from.address'), config('mail.from.name'));
                });

                // Log to database
                $lead->communications()->create([
                    'type' => 'email',
                    'subject' => $validated['subject'],
                    'content' => $validated['content'],
                    'created_by' => Auth::id(),
                    'status' => 'completed'
                ]);

                return back()->with('success', 'Email sent successfully to ' . $validated['to']);
            } else {
                // SMS validation
                $request->validate([
                    'to' => 'required|string|max:20',
                    'content' => 'required|string|max:160',
                ]);

                // TODO: Integrate SMS provider (Twilio, MSG91, etc.)
                // For now, just log it
                $lead->communications()->create([
                    'type' => 'sms',
                    'content' => $validated['content'],
                    'created_by' => Auth::id(),
                    'status' => 'completed'
                ]);

                return back()->with('success', 'SMS logged successfully! (Provider not configured)');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('SendMessage Error: ' . $e->getMessage());
            return back()->with('error', 'Failed: ' . $e->getMessage());
        }
    }
    /**
     * Display all uploaded documents
     */
    public function documents(Request $request)
    {
        $query = LeadDocument::with(['lead', 'documentSetting']);

        // 🔍 Search filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('file_name', 'like', "%{$request->search}%")
                    ->orWhere('document_type', 'like', "%{$request->search}%");
            });
        }

        // 📅 Date range filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date,
                $request->end_date . ' 23:59:59'
            ]);
        }

        // 🏷️ Status filter
        if ($request->filled('status')) {
            if ($request->status === 'verified') {
                $query->where('is_verified', true);
            } elseif ($request->status === 'rejected') {
                $query->where('is_verified', false);
            } elseif ($request->status === 'pending') {
                $query->whereNull('is_verified');
            }
        }

        // 🔄 Sort - ✅ Sanitize input
        $allowedSorts = ['created_at', 'file_name', 'file_size', 'updated_at'];
        $sort = $request->get('sort', 'created_at');

        // Only allow whitelisted columns
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        // Only allow 'asc' or 'desc' (lowercase)
        $order = strtolower($request->get('order', 'desc'));
        $order = in_array($order, ['asc', 'desc']) ? $order : 'desc';

        $query->orderBy($sort, $order);

        // ✅ Paginate and pass to view
        $documents = $query->paginate(15)->withQueryString();

        return view('pages.documents.index', compact('documents'));
    }
    /**
     * Display all admission requests
     */
    public function admissionRequests(Request $request)
    {
        $query = AdmissionRequest::with(['lead', 'college', 'course', 'submittedBy']);

        // 🔍 Search filter
        if ($request->filled('search')) {
            $query->whereHas('lead', function ($q) use ($request) {
                $q->where('full_name', 'like', "%{$request->search}%");
            })->orWhereHas('college', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            });
        }

        // 📅 Date range filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date,
                $request->end_date . ' 23:59:59'
            ]);
        }

        // 🏷️ Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🔄 Sort
        $allowedSorts = ['created_at', 'updated_at', 'status'];
        $sort = $request->get('sort', 'created_at');

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        $order = strtolower($request->get('order', 'desc'));
        $order = in_array($order, ['asc', 'desc']) ? $order : 'desc';

        $query->orderBy($sort, $order);

        $admissions = $query->paginate(15)->withQueryString();

        return view('pages.admissions.index', compact('admissions'));
    }
}
