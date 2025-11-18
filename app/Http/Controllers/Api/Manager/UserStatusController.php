<?php
namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Models\ManagerCounsellor;
use App\Models\ManagerPurchaseManager;
use App\Models\ManagerVendor;
use App\Models\MangerMR;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserStatusController extends Controller
{
    private const USER_TYPES = ['MR', 'vendor', 'purchase_manager', 'counsellor'];

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status'    => 'nullable|string|in:pending,suspend,active',
            'user_type' => 'nullable|string|in:MR,vendor,purchase_manager,counsellor',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 400);
        }

        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $manager = Auth::user();

        $status = $this->normalizeStatus($request->input('status', 'active'));
        $type   = $request->input('user_type');

        // Fetch users
        if ($status === 'Active') {
            $users = $this->activeUsers($manager, $type);
        } else {
            $users = $this->usersByStatus($status, $type);
        }

        return $this->success('Users fetched successfully.', $users);
    }

    public function approve($id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $managerId = Auth::id();

        $user = User::find($id);
        if (! $user) {
            return $this->error('User not found.', 404);
        }

        if (! in_array($user->user_type, self::USER_TYPES)) {
            return $this->error('Invalid user type.', 400);
        }

        if ($user->status === 'Active') {
            return $this->error('User already approved.', 400);
        }

        $user->status = 'Active';
        $user->save();

        $this->assignManager($user, $managerId);

        return $this->success('User approved successfully.', $user->fresh());
    }

    public function suspend($id)
    {
        if ($response = $this->ensureAuthenticated()) {
            return $response;
        }

        $user = User::find($id);
        if (! $user) {
            return $this->error('User not found.', 404);
        }

        if (! in_array($user->user_type, self::USER_TYPES)) {
            return $this->error('Invalid user type.', 400);
        }

        if ($user->status === 'Suspend') {
            return $this->success('User already suspended.', $user);
        }

        $user->status = 'Suspend';
        $user->save();

        DB::table('sessions')->where('user_id', $user->id)->delete();

        return $this->success('User suspended successfully.', $user->fresh());
    }

    // -------------------------------------------------------
    // SIMPLE PAGINATED FETCH (paginate(10))
    // -------------------------------------------------------

    private function activeUsers($manager, $type)
    {
        $map = [
            'MR'               => 'mrs',
            'vendor'           => 'vendors',
            'purchase_manager' => 'purchaseManagers',
            'counsellor'       => 'counsellors',
        ];

        // If a specific type requested, use its relation (query builder -> paginate(10))
        if ($type && isset($map[$type])) {
            return $manager->{$map[$type]}()
                ->orderBy('id', 'DESC')
                ->paginate(10);
        }

        // No type specified: collect all related user IDs, then query User model and paginate
        $allIds = collect();
        foreach ($map as $relation) {
            if (! method_exists($manager, $relation)) {
                continue;
            }

            // get() returns a collection of models — pluck ids and merge
            $ids    = $manager->{$relation}()->pluck('users.id')->all();
            $allIds = $allIds->merge($ids);
        }

        $allIds = $allIds->unique()->values()->all();

        // If no related users, return empty paginator
        if (empty($allIds)) {
            return User::whereIn('id', [0])->paginate(10); // empty result
        }

        // Query the User model with the collected IDs and paginate
        return User::whereIn('id', $allIds)
            ->orderBy('id', 'DESC')
            ->paginate(10);
    }

    private function usersByStatus($status, $type)
    {
        $query = User::where('status', $status)
            ->whereIn('user_type', self::USER_TYPES);

        if ($type) {
            $query->where('user_type', $type);
        }

        return $query->orderBy('id', 'DESC')->paginate(10);
    }

    // -------------------------------------------------------

    private function normalizeStatus($status)
    {
        return match (strtolower($status)) {
            'pending' => 'Pending',
            'suspend' => 'Suspend',
            default   => 'Active',
        };
    }

    private function assignManager($user, $managerId)
    {
        match ($user->user_type) {
            'MR'               => MangerMR::updateOrCreate(['mr_id' => $user->id], ['manager_id' => $managerId]),
            'vendor'           => ManagerVendor::updateOrCreate(['vendor_id' => $user->id], ['manager_id' => $managerId]),
            'purchase_manager' => ManagerPurchaseManager::updateOrCreate(['purchase_manager_id' => $user->id], ['manager_id' => $managerId]),
            'counsellor'       => ManagerCounsellor::updateOrCreate(['counsellor_id' => $user->id], ['manager_id' => $managerId]),
        };
    }

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

    private function error($message, $code)
    {
        return response()->json([
            'status'  => $code,
            'message' => $message,
            'data'    => null,
        ], $code);
    }

    private function success($message, $data)
    {
        return response()->json([
            'status'  => 200,
            'message' => $message,
            'data'    => $data,
        ]);
    }
}
