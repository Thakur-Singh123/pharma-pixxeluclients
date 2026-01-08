<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Http\Request;

class DeleteAccountController extends Controller
{
    //Function for delete account
    public function destroy($id) {
        //Get user detail
        $user = User::find($id);
        //Check if user found or not
        if (!$user) {
            return back()->with('unsuccess', 'User not found.');
        }
        //Get role & user id
        $role = trim($user->user_type);
        $user_id = $user->id;
        //Role based column
        $roleColumn = match ($role) {
            'Manager' => 'manager_id',
            'MR' => 'mr_id',
            'vendor' => 'vendor_id',
            'purchase_manager' => 'purchase_manager_id',
            'counsellor' => 'counsellor_id',
            default => 'user_id',
        };
        //Tables list
        $tables = [
            'visit_plan_interests',
            'visit_plan_comment',
            'visit_plan_assignments',
            'visit_plans',
            'ta_da_records',
            'task_tour_plans',
            'tasks',
            'sales_items',
            'sales',
            'referred_patients',
            'purchase_order_items',
            'purchase_orders',
            'problems',
            'patients',
            'mr_daily_reports',
            'notifications',
            'mr_attendances',
            'monthly_tasks',
            'manager_vendors',
            'manager_purchase_managers',
            'manager_mr',
            'manager_counsellors',
            'event_users',
            'events',
            'doctor_mr_assignments',
            'doctors',
            'daily_visits',
            'daily_report_details',
            'daily_reports',
            'counselor_patients',
            'clients',
        ];
        //Db
        DB::beginTransaction();
        try {
            foreach ($tables as $table) {
                if (
                    !Schema::hasColumn($table, $roleColumn) &&
                    !Schema::hasColumn($table, 'user_id')
                ) {
                    continue;
                }
                if (Schema::hasColumn($table, $roleColumn)) {
                    DB::table($table)->where($roleColumn, $user_id)->delete();
                }
                if ($roleColumn !== 'user_id' && Schema::hasColumn($table, 'user_id')) {
                    DB::table($table)->where('user_id', $user_id)->delete();
                }
            }
            //Delete user account
            $is_delete_account = User::where('id', $id)->delete();
            DB::commit();
            //Check if account deleted or not
            if ($is_delete_account) {
                return back()->with(
                    'success',
                    'Your account has been permanently deleted.'
                );
            }
            return back()->with('unsuccess', 'Account delete failed.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with(
                'unsuccess',
                'Oops! Something went wrong.'
            );
        }
    }
}
