<?php

namespace App\Exports;

use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PurchaseOrdersExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = PurchaseOrder::with(['vendor', 'purchaseManager']);

        if (Auth::check() && Auth::user()->user_type === 'vendor') {
            $query->where('vendor_id', Auth::id());
        }

        if (Auth::check() && Auth::user()->user_type === 'purchase_manager') {
            $query->where('purchase_manager_id', Auth::id());
        }

        if (Auth::check() && Auth::user()->user_type === 'manager') {
            $query->where('manager_id', Auth::id());
        }

        // Vendor filter
        if (!empty($this->filters['vendor_id'])) {
            $query->where('vendor_id', $this->filters['vendor_id']);
        }

        // Purchase Manager filter
        if (!empty($this->filters['purchase_manager_id'])) {
            $query->where('purchase_manager_id', $this->filters['purchase_manager_id']);
        }
        // Delivery status filter
        if (!empty($this->filters['is_delivered'])) {
            $query->where('is_delivered', $this->filters['is_delivered']);
        }

        // Status filter
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        // Date range filter
        if (!empty($this->filters['date_range'])) {
            switch ($this->filters['date_range']) {
                case 'today':
                    $query->whereDate('order_date', now());
                    break;
                case 'this_week':
                    $query->whereBetween('order_date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('order_date', now()->month)
                        ->whereYear('order_date', now()->year);
                    break;
                case 'this_year':
                    $query->whereYear('order_date', now()->year);
                    break;
                case 'all':
                default:
                    // no filter
                    break;
            }
        }

        // Fetch and map to export structure
        return $query->get()->map(function ($po) {
            return [
                'PO #'                  => $po->id,
                'Order Date'            => $po->order_date,
                'Vendor Name'           => $po->vendor?->name,
                'Vendor Email'          => $po->vendor?->email,
                'Nature of Vendor'      => $po->nature_of_vendor,
                // 'Subtotal'              => $po->subtotal,
                // 'Discount'              => $po->discount_total,
                // 'Grand Total'           => $po->grand_total,
                'Status'                => ucfirst($po->status),
                'Delivery'              => ucfirst($po->is_delivered),
                'Purchase Manager Name' => $po->purchaseManager?->name,
                'Purchase Manager Email'=> $po->purchaseManager?->email,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'PO #',
            'Order Date',
            'Vendor Name',
            'Vendor Email',
            'Nature of Vendor',
            // 'Subtotal',
            // 'Discount',
            // 'Grand Total',
            'Status',
            'Delivery',
            'Purchase Manager Name',
            'Purchase Manager Email',
        ];
    }
}
