<?php
namespace App\Exports;

use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VenodrPurchaseOrdersExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = PurchaseOrder::with('vendor')->where('vendor_id', Auth::id());

        // Delivery status filter
        if (! empty($this->filters['is_delivered'])) {
            $query->where('is_delivered', $this->filters['is_delivered']);
        }

        // Date range filter
        if (! empty($this->filters['date_range'])) {
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
            }
        }

        // Return mapped collection
        return $query->get()->map(function ($po) {
            return [
                'PO #'         => $po->id,
                'Order Date'   => $po->order_date,
                'Vendor Name'  => $po->vendor?->name,
                'Vendor Email' => $po->vendor?->email,
                'Items Count'  => $po->items->count(),
                'Subtotal'     => $po->subtotal,
                'Discount'     => $po->discount_total,
                'Grand Total'  => $po->grand_total,
                'Status'       => ucfirst($po->status),
                'Delivery'     => ucfirst($po->is_delivered),
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
            'Items Count',
            'Subtotal',
            'Discount',
            'Grand Total',
            'Status',
            'Delivery',
        ];
    }
}
