<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Models\DoctorMrAssignement;
use App\Models\EventUser;
use App\Models\Events;
use App\Models\User;
use App\Notifications\EventAssignedNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Services\FirebaseService;

class EventController extends Controller
{

    Protected $fcmService;

    public function __construct(FirebaseService $fcmService)
    {
        $this->fcmService = $fcmService;
    }
    /**
     * Ensure the user is authenticated.
     */
    private function ensureAuthenticated(): ?JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'status'  => 401,
                'message' => 'Unauthorized access. Please login first.',
                'data'    => null,
            ], 401);
        }

        return null;
    }

    /**
     * Resolve per page value with sensible defaults.
     */
    private function resolvePerPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', 10);
        if ($perPage <= 0) {
            $perPage = 10;
        }

        return min($perPage, 100);
    }

    /**
     * Base query for manager events.
     */
    private function managerEventQuery(): Builder
    {
        return Events::where('manager_id', Auth::id())
            ->with(['mr', 'doctor_detail'])
            ->orderByDesc('id');
    }

    /**
     * Append QR code URL for a single event.
     */
    private function appendQrCode($event)
    {
        if ($event && $event->qr_code_path) {
            $event->qr_code_path = asset('public/qr_codes/' . ltrim($event->qr_code_path, '/'));
        }

        return $event;
    }

    /**
     * Append QR code URL on paginated events.
     */
    private function appendQrCodeOnPaginator($paginator)
    {
        if ($paginator && method_exists($paginator, 'getCollection')) {
            $paginator->getCollection()->transform(function ($event) {
                return $this->appendQrCode($event);
            });
        }

        return $paginator;
    }

    /**
     * Validate created_by filter.
     */
    private function applyCreatedByFilter(Request $request, Builder $query): ?JsonResponse
    {
        if (!$request->filled('created_by')) {
            return null;
        }

        $createdBy = strtolower($request->query('created_by'));

        if (!in_array($createdBy, ['mr', 'manager'], true)) {
            return response()->json([
                'status'  => 422,
                'message' => 'Invalid created_by filter. Allowed values: mr, manager.',
                'data'    => null,
            ], 422);
        }

        $query->where('created_by', $createdBy);

        return null;
    }

    /**
     * Generate and store QR code for the event.
     */
    private function generateAndStoreQrCode(Events $event): void
    {
        $joinUrl     = url('/join-event/' . $event->id);
        $qrCodeImage = QrCode::format('png')->size(300)->generate($joinUrl);

        $filename = 'event_' . $event->id . '.png';
        $folder   = public_path('qr_codes');

        if (!file_exists($folder)) {
            mkdir($folder, 0775, true);
        }

        file_put_contents($folder . '/' . $filename, $qrCodeImage);

        $event->qr_code_path = $filename;
        $event->save();
    }

    /**
     * Retrieve a single event for the authenticated manager.
     */
    private function findEventForManager(int $id): ?Events
    {
        return Events::where('id', $id)
            ->where('manager_id', Auth::id())
            ->with(['mr', 'doctor_detail'])
            ->first();
    }

    /**
     * List active events.
     */
    public function index(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $query = $this->managerEventQuery()->where('is_active', 1);

        if ($response = $this->applyCreatedByFilter($request, $query)) {
            return $response;
        }

        $events = $this->appendQrCodeOnPaginator(
            $query->simplePaginate($this->resolvePerPage($request))
        );

        $message = $events->count() ? 'Events fetched successfully.' : 'No events found.';

        return response()->json([
            'status'  => 200,
            'message' => $message,
            'data'    => $events,
            'count'   => count($events),
        ], 200);
    }

    /**
     * List events waiting for approval.
     */
    public function pendingForApproval(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $query = $this->managerEventQuery()->where('is_active', 0);

        if ($response = $this->applyCreatedByFilter($request, $query)) {
            return $response;
        }

        $events = $this->appendQrCodeOnPaginator(
            $query->simplePaginate($this->resolvePerPage($request))
        );

        $message = $events->count() ? 'Pending approval events fetched successfully.' : 'No pending approval events found.';

        return response()->json([
            'status'  => 200,
            'message' => $message,
            'data'    => $events,
            'count'   => count($events),
        ], 200);
    }

    /**
     * Store a new event.
     */
    public function store(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'mr_id'          => 'required|exists:users,id',
            'doctor_id'      => 'nullable|exists:doctors,id',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'location'       => 'nullable|string|max:255',
            'pin_code'       => 'nullable|string|max:20',
            'start_datetime' => 'required|date',
            'end_datetime'   => 'required|date|after_or_equal:start_datetime',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 400,
                'message' => $validator->errors()->first(),
                'data'    => null,
            ], 400);
        }

        $event = Events::create([
            'mr_id'          => $request->mr_id,
            'manager_id'     => Auth::id(),
            'doctor_id'      => $request->doctor_id,
            'title'          => $request->title,
            'description'    => $request->description,
            'location'       => $request->location,
            'pin_code'       => $request->pin_code,
            'start_datetime' => $request->start_datetime,
            'end_datetime'   => $request->end_datetime,
            'status'         => 'pending',
            'created_by'     => 'manager',
            'is_active'      => 1,
        ]);

        if ($request->filled('doctor_id')) {
            DoctorMrAssignement::firstOrCreate([
                'doctor_id' => $request->doctor_id,
                'mr_id'     => $request->mr_id,
            ]);
        }

        $this->generateAndStoreQrCode($event);

        $user = User::find($request->mr_id);
        $fcmResponses = [];
        if ($user) {
            $user->notify(new EventAssignedNotification($event));
            //mobile notification
            $fcmResponses = $this->fcmService->sendToUser($user, [
                'id'         => $event->id,
                'title'      => $event->title, 
                'message'    => 'You have been assigned a new event: ' . $event->title,
                'type'       => 'event',
                'is_read'    => 'false',
                'created_at'=> now()->toDateTimeString(),
            ]);
        }

        $event->load(['mr', 'doctor_detail']);
        $this->appendQrCode($event);

        return response()->json([
            'status'  => 200,
            'message' => 'Event created successfully.',
            'data'    => $event,
            'fcm_response' => $fcmResponses,
        ], 200);
    }

    /**
     * Update event meta status (pending, completed, etc.).
     */
    public function updateStatus(Request $request, $id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 400,
                'message' => $validator->errors()->first(),
                'data'    => null,
            ], 400);
        }

        $event = $this->findEventForManager((int) $id);

        if (!$event) {
            return response()->json([
                'status'  => 404,
                'message' => 'Event not found.',
                'data'    => null,
            ], 404);
        }

        $event->status = $request->status;
        $event->save();

        $this->appendQrCode($event);

        return response()->json([
            'status'  => 200,
            'message' => 'Event status updated successfully.',
            'data'    => $event,
        ], 200);
    }

    /**
     * Update event details.
     */
    public function update(Request $request, $id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'mr_id'          => 'required|exists:users,id',
            'doctor_id'      => 'nullable|exists:doctors,id',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'location'       => 'nullable|string|max:255',
            'pin_code'       => 'nullable|string|max:20',
            'start_datetime' => 'required|date',
            'end_datetime'   => 'required|date|after_or_equal:start_datetime',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 400,
                'message' => $validator->errors()->first(),
                'data'    => null,
            ], 400);
        }

        $event = $this->findEventForManager((int) $id);

        if (!$event) {
            return response()->json([
                'status'  => 404,
                'message' => 'Event not found.',
                'data'    => null,
            ], 404);
        }

        $oldMrId = $event->mr_id;

        $event->update([
            'mr_id'          => $request->mr_id,
            'manager_id'     => Auth::id(),
            'doctor_id'      => $request->doctor_id,
            'title'          => $request->title,
            'description'    => $request->description,
            'location'       => $request->location,
            'pin_code'       => $request->pin_code,
            'created_by'     => 'manager',
            'start_datetime' => $request->start_datetime,
            'end_datetime'   => $request->end_datetime,
        ]);

        if ($request->filled('doctor_id')) {
            DoctorMrAssignement::firstOrCreate([
                'doctor_id' => $request->doctor_id,
                'mr_id'     => $request->mr_id,
            ]);
        }

        //Send notification
        $fcmResponses = [];
        if ($oldMrId !== (int) $request->mr_id) {
            $user = User::find($request->mr_id);
            if ($user) {
                $user->notify(new EventAssignedNotification($event));

                $fcmResponses = $this->fcmService->sendToUser($user, [
                    'id'         => $event->id,
                    'title'      => $event->title, 
                    'message'    => 'You have been assigned a new event: ' . $event->title,
                    'type'       => 'event',
                    'is_read'    => 'false',
                    'created_at'=> now()->toDateTimeString(),
                ]);
            }
        }

        $event->refresh()->load(['mr', 'doctor_detail']);
        $this->appendQrCode($event);

        return response()->json([
            'status'  => 200,
            'message' => 'Event updated successfully.',
            'data'    => $event,
            'fcm_response' => $fcmResponses,
        ], 200);
    }

    /**
     * Approve an event.
     */
    public function approve($id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $event = $this->findEventForManager((int) $id);

        if (!$event) {
            return response()->json([
                'status'  => 404,
                'message' => 'Event not found.',
                'data'    => null,
            ], 404);
        }

        $event->is_active = 1;
        $event->save();

        $this->generateAndStoreQrCode($event);
        $event->refresh()->load(['mr', 'doctor_detail']);
        $this->appendQrCode($event);

        return response()->json([
            'status'  => 200,
            'message' => 'Event approved successfully.',
            'data'    => $event,
        ], 200);
    }

    /**
     * Reject an event.
     */
    public function reject($id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $event = $this->findEventForManager((int) $id);

        if (!$event) {
            return response()->json([
                'status'  => 404,
                'message' => 'Event not found.',
                'data'    => null,
            ], 404);
        }

        $event->is_active = 0;
        $event->save();

        $this->appendQrCode($event);

        return response()->json([
            'status'  => 200,
            'message' => 'Event rejected successfully.',
            'data'    => $event,
        ], 200);
    }

    /**
     * Delete an event.
     */
    public function destroy($id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $event = $this->findEventForManager((int) $id);

        if (!$event) {
            return response()->json([
                'status'  => 404,
                'message' => 'Event not found.',
                'data'    => null,
            ], 404);
        }

        if ($event->qr_code_path) {
            $qrCodePath = public_path('qr_codes/' . $event->qr_code_path);
            if (file_exists($qrCodePath)) {
                @unlink($qrCodePath);
            }
        }

        $event->delete();

        return response()->json([
            'status'  => 200,
            'message' => 'Event deleted successfully.',
            'data'    => null,
        ], 200);
    }

    /**
     * List event participations.
     */
    public function participations(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $participations = EventUser::with(['event_detail' => function ($query) {
                $query->with(['mr', 'doctor_detail']);
            }])
            ->whereHas('event_detail', function ($query) {
                $query->where('manager_id', Auth::id());
            })
            ->orderByDesc('id')
            ->simplePaginate($this->resolvePerPage($request));

        if (method_exists($participations, 'getCollection')) {
            $participations->getCollection()->transform(function ($participant) {
                if ($participant->event_detail) {
                    $this->appendQrCode($participant->event_detail);
                }
                return $participant;
            });
        }

        $message = $participations->count() ? 'Event participations fetched successfully.' : 'No event participations found.';

        return response()->json([
            'status'  => 200,
            'message' => $message,
            'data'    => $participations,
        ], 200);
    }
}

