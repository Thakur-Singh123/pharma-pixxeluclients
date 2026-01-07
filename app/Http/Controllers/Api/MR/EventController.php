<?php

namespace App\Http\Controllers\Api\MR;

use App\Http\Controllers\Controller;
use App\Models\EventUser;
use App\Models\Events;
use App\Models\MangerMR;
use App\Models\User;
use App\Notifications\MrEventCreatedNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Services\FirebaseService;

class EventController extends Controller
{
    Protected  $fcmService;

    public function __construct(FirebaseService $fcmService)
    {
        $this->fcmService = $fcmService;
    }
    /**
     * Ensure the current request is authenticated.
     */
    private function ensureAuthenticated(): ?JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized access. Please login first.',
                'data' => null,
            ], 401);
        }

        return null;
    }

    /**
     * Build full qr code path.
     */
    private function buildQrCodePath(?string $path): ?string
    {
        return $path ? asset('public/qr_codes/' . ltrim($path, '/')) : null;
    }

    /**
     * Append qr code url on a single event.
     */
    private function appendQrCode($event)
    {
        if ($event && isset($event->qr_code_path)) {
            $event->qr_code_path = $this->buildQrCodePath($event->qr_code_path);
        }

        return $event;
    }

    /**
     * Append qr code url on paginated events.
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
     * Apply created_by filter when provided.
     */
    private function applyCreatedByFilter(Request $request, Builder $query): ?JsonResponse
    {
        if (!$request->filled('created_by')) {
            return null;
        }

        $createdBy = strtolower($request->query('created_by'));

        if (!in_array($createdBy, ['mr', 'manager'], true)) {
            return response()->json([
                'status' => 422,
                'message' => 'Invalid created_by filter. Allowed values: mr, manager.',
                'data' => null,
            ], 422);
        }

        $query->where('created_by', $createdBy);

        return null;
    }

    /**
     * Common query builder for MR events.
     */
    private function mrEventQuery(): Builder
    {
        return Events::where('mr_id', auth()->id())
            ->with('doctor_detail')
            ->orderByDesc('created_at');
    }

    /**
     * Fetch all events for authenticated MR.
     */
    public function index(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $query = $this->mrEventQuery();

        if ($response = $this->applyCreatedByFilter($request, $query)) {
            return $response;
        }

        $events = $this->appendQrCodeOnPaginator(
            $query->simplePaginate(10)
        );
        $message = $events->count() ? 'Events fetched successfully.' : 'No events found.';

        return response()->json([
            'status' => 200,
            'message' => $message,
            'data' => $events,
        ], 200);
    }

    /**
     * Fetch pending approval events.
     */
    public function pendingForApproval(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $query = $this->mrEventQuery()->where('is_active', 0);

        if ($response = $this->applyCreatedByFilter($request, $query)) {
            return $response;
        }

        $events = $this->appendQrCodeOnPaginator(
            $query->simplePaginate(10)
        );
        $message = $events->count() ? 'Pending approval events fetched successfully.' : 'No pending approval events found.';

        return response()->json([
            'status' => 200,
            'message' => $message,
            'data' => $events,
        ], 200);
    }

    /**
     * Manager assigned events.
     */
    public function assign_manger(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $events = $this->appendQrCodeOnPaginator(
            $this->mrEventQuery()
                ->where('created_by', 'manager')
                ->simplePaginate(10)
        );

        $message = $events->count() ? 'Manager assigned events fetched successfully.' : 'No manager assigned events found.';

        return response()->json([
            'status' => 200,
            'message' => $message,
            'data' => $events,
        ], 200);
    }

    /**
     * Events created by MR themselves.
     */
    public function himself(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $events = $this->appendQrCodeOnPaginator(
            $this->mrEventQuery()
                ->where('created_by', 'mr')
                ->simplePaginate(10)
        );

        $message = $events->count() ? 'Self created events fetched successfully.' : 'No self created events found.';

        return response()->json([
            'status' => 200,
            'message' => $message,
            'data' => $events,
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'pin_code' => 'nullable|string|max:20',
            'doctor_id' => 'nullable|exists:doctors,id',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 400);
        }

        $managerId = MangerMR::where('mr_id', auth()->id())->value('manager_id');

        $event = Events::create([
            'mr_id' => auth()->id(),
            'manager_id' => $managerId,
            'doctor_id' => $request->doctor_id,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'pin_code' => $request->pin_code,
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
            'status' => 'pending',
            'created_by' => 'mr',
        ]);

        $event->load('doctor_detail');
        $this->appendQrCode($event);

        $fcmResponses = [];
        $user = User::find($managerId);
        if ($user) {
            $user->notify(new MrEventCreatedNotification($event));
            //fcm notification
            $fcmResponses = $this->fcmService->sendToUser($user, [
                'id'         => $event->id,
                'title'      => $event->title, 
                'message'    => auth()->user()->name  . ' has submitted a new event for approval: '  . $event->title,
                'type'       => 'event',
                'is_read'    => 'false',
                'created_at'=> now()->toDateTimeString(),
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => 'Event created successfully.',
            'data' => $event,
            'fcm_responses' => $fcmResponses
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'pin_code' => 'nullable|string|max:20',
            'doctor_id' => 'nullable|exists:doctors,id',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 400);
        }

        $event = $this->appendQrCode(
            Events::where('id', $id)
                ->where('mr_id', auth()->id())
                ->first()
        );

        if (!$event) {
            return response()->json([
                'status' => 404,
                'message' => 'Event not found.',
                'data' => null,
            ], 404);
        }

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'pin_code' => $request->pin_code,
            'doctor_id' => $request->doctor_id,
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
        ]);

        $event->refresh()->load('doctor_detail');
        $this->appendQrCode($event);

        return response()->json([
            'status' => 200,
            'message' => 'Event updated successfully.',
            'data' => $event,
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

        $event = Events::where('id', $id)
            ->where('mr_id', auth()->id())
            ->first();

        if (!$event) {
            return response()->json([
                'status' => 404,
                'message' => 'Event not found.',
                'data' => null,
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
            'status' => 200,
            'message' => 'Event deleted successfully.',
            'data' => null,
        ], 200);
    }

    /**
     * Active participations for MR events.
     */
    public function participations(Request $request)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $participations = EventUser::with(['event_detail' => function ($query) {
                    $query->with('mr', 'doctor_detail');
                }])
                ->whereHas('event_detail', function ($query) {
                    $query->where('mr_id', auth()->id());
                })
                ->orderByDesc('id')
                ->simplePaginate(10);

        if ($participations && method_exists($participations, 'getCollection')) {
            $participations->getCollection()->transform(function ($participant) {
                if ($participant->event_detail) {
                    $this->appendQrCode($participant->event_detail);
                }

                return $participant;
            });
        }

        $message = $participations->count() ? 'Event participations fetched successfully.' : 'No event participations found.';

        return response()->json([
            'status' => 200,
            'message' => $message,
            'data' => $participations,
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

        $event = Events::where('id', $id)
            ->where('mr_id', auth()->id())
            ->first();

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

}
