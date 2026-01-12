<?php
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\MangerMR;
use App\Models\User;
use App\Models\ManagerVendor;
use App\Models\ManagerPurchaseManager;
use App\Models\ManagerCounsellor;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Auth;
use DB;

class UserStatusController extends Controller
{
    //Function for active users
    public function all_active_users(Request $request)
    {
        $manager = auth()->user();
        $type = $request->user_type;

        // agar user_type select kiya gaya hai
        if ($type == 'MR') {
            $active_users = $manager->mrs()->where('status', 'Active')->orderBy('id', 'DESC')->paginate(5);
        } elseif ($type == 'vendor') {
            $active_users = $manager->vendors()->where('status', 'Active')->orderBy('id', 'DESC')->paginate(5);
        } elseif ($type == 'purchase_manager') {
            $active_users = $manager->purchaseManagers()->where('status', 'Active')->orderBy('id', 'DESC')->paginate(5);
        } elseif ($type == 'counsellor') {
            $active_users = $manager->counsellors()->where('status', 'Active')->orderBy('id', 'DESC')->paginate(5);
        } else {
            // agar koi specific type select nahi kiya gaya
            $mrs = $manager->mrs()->where('status', 'Active')->get();
            $vendors = $manager->vendors()->where('status', 'Active')->get();
            $purchaseManagers = $manager->purchaseManagers()->where('status', 'Active')->get();
            $counsellors = $manager->counsellors()->where('status', 'Active')->get();

            // sabko merge karke ek list me daal do
            $all = $mrs->merge($vendors)->merge($purchaseManagers)->merge($counsellors)->sortByDesc('id')->values();
            // manual paginate
            $page = $request->get('page', 1);
            $perPage = 10;
            $active_users = new LengthAwarePaginator(
                $all->forPage($page, $perPage),
                $all->count(),
                $perPage,
                $page,
                ['path' => $request->url()]
            );
        }

        return view('manager.users-status.active-users', compact('active_users', 'type'));
    }


    //Function for pending users
    public function all_pending_users(Request $request)
    {
        $user_type = $request->user_type;

        $pending_users = User::orderBy('id', 'DESC')
            ->where('status', 'Pending');
        if ($user_type) {
            $pending_users->where('user_type', $user_type);
        } else {
            $pending_users->whereIn('user_type', ['MR', 'vendor', 'purchase_manager', 'counsellor']);
        }
        $pending_users = $pending_users->paginate(5);

        return view('manager.users-status.pending-users', compact('pending_users', 'user_type'));
    }


    //Function for suspend users
    public function all_suspend_users(Request $request)
    {
        $user_type = $request->user_type;

        $query = User::orderBy('id', 'DESC')
            ->where('status', 'Suspend');

        if ($user_type) {
            $query->where('user_type', $user_type);
        } else {
            $query->whereIn('user_type', ['MR', 'vendor', 'purchase_manager', 'counsellor']);
        }

        // Paginate
        $all_users = $query->paginate(5);

        return view('manager.users-status.suspend-users', compact('all_users', 'user_type'));
    }


    //Function for approve user
    // public function approve_user($id) {
    //     try {
    //         $user = User::findOrFail($id);

    //         // Check if already approved
    //         if ($user->status === 'Active') {
    //             return redirect()->back()->with('error', 'User is already approved.');
    //         }

    //         //Approve user
    //         $user->status = 'Active';
    //         $user->save();

    //         //Remove old
    //         MangerMR::where('mr_id', $id)->delete();
    //         //Create update MR
    //         MangerMR::updateOrCreate([
    //             'manager_id' => Auth::id(),
    //             'mr_id'      => $id,
    //         ]);

    //         return redirect()->back()->with('success', 'User approved successfully.');

    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    //     }
    // }
    public function approve_user($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->status === 'Active') {
                return redirect()->back()->with('error', 'User is already approved.');
            }

            $user->status = 'Active';
            $user->save();

            switch ($user->user_type) {
                case 'MR':
                    MangerMR::where('mr_id', $id)->delete();
                    MangerMR::updateOrCreate([
                        'manager_id' => Auth::id(),
                        'mr_id'      => $id,
                    ]);
                    break;

                case 'vendor':
                    ManagerVendor::where('vendor_id', $id)->delete();
                    ManagerVendor::updateOrCreate([
                        'manager_id' => Auth::id(),
                        'vendor_id'  => $id,
                    ]);
                    break;

                case 'purchase_manager':
                    ManagerPurchaseManager::where('purchase_manager_id', $id)->delete();
                    ManagerPurchaseManager::updateOrCreate([
                        'manager_id'          => Auth::id(),
                        'purchase_manager_id' => $id,
                    ]);
                    break;

                case 'counsellor':
                    ManagerCounsellor::where('counsellor_id', $id)->delete();
                    ManagerCounsellor::updateOrCreate([
                        'manager_id'    => Auth::id(),
                        'counsellor_id' => $id,
                    ]);
                    break;

                default:
                    return redirect()->back()->with('error', 'Invalid user type.');
            }

            return redirect()->back()->with('success', 'User approved successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    //Function for suspend user
    public function reject_user($id)
    {
        //Get user detail
        $user_record         = User::findOrFail($id);
        $user_record->status = 'Suspend';
        $user_record->save();
        DB::table('sessions')->where('user_id', $user_record->id)->delete();

        return redirect()->back()->with('success', 'User suspend successfully.');
    }

    //Function for pending user
    public function pending_user($id)
    {
        //Get user detail
        $user_record         = User::findOrFail($id);
        $user_record->status = 'Pending';
        $user_record->save();
        DB::table('sessions')->where('user_id', $user_record->id)->delete();

        return redirect()->back()->with('success', 'User pending successfully.');
    }
}
