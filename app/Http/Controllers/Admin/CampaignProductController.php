<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendCampaignProductStatusJob;
use App\Mail\CampaignProductStatusMail;
use App\Models\CampaignProduct;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CampaignProductController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->input('status', 'pending');
        $search = $request->input('search');
        $source = $request->input('source');
        $categoryId = $request->input('category');
        $from = $request->input('from');
        $to = $request->input('to');
        $sort = $request->input('sort', 'created_at');
        $dir = $request->input('direction', 'desc');

        $allowedSorts = ['name', 'price', 'quantity', 'created_at', 'approval_status'];
        if (! in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }
        $dir = $dir === 'asc' ? 'asc' : 'desc';

        $query = CampaignProduct::with([
            'campaign:id,title,slug',
            'user:id,name,email',
            'categoryProduct:id,name,category_id',
            'categoryProduct.category:id,name',
            'approver:id,name',
        ]);

        if ($status !== 'all') {
            $query->where('approval_status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('campaign', fn ($cq) => $cq->where('title', 'like', "%{$search}%"))
                    ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        if ($source) {
            $query->where('source', $source);
        }

        if ($categoryId) {
            $query->whereHas('categoryProduct.category', fn ($cq) => $cq->where('id', $categoryId));
        }

        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        // TODO: Replace leading-wildcard LIKE + orWhereHas with a full-text
        // index or search service (e.g. Scout) once the table grows large.

        $products = $query->orderBy($sort, $dir)->paginate(20);

        $counts = CampaignProduct::selectRaw("
            COUNT(*) as total,
            SUM(approval_status = 'pending') as pending,
            SUM(approval_status = 'approved') as approved,
            SUM(approval_status = 'rejected') as rejected
        ")->first();

        $cntPending = (int) $counts->pending;
        $cntApproved = (int) $counts->approved;
        $cntRejected = (int) $counts->rejected;
        $cntTotal = (int) $counts->total;

        $categories = Category::orderBy('name')->get();

        return view('admin.campaign-products.index', compact(
            'products', 'status', 'search', 'source', 'categoryId', 'categories',
            'from', 'to', 'sort', 'dir',
            'cntPending', 'cntApproved', 'cntRejected', 'cntTotal'
        ));
    }

    public function approve(CampaignProduct $product): RedirectResponse
    {
        if ($product->approval_status !== 'pending') {
            return back()->with('error', 'Only pending products can be approved.');
        }

        $product->update([
            'approval_status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'is_active' => true,
        ]);

        $admin = Auth::user();

        Mail::to($product->user)->queue(
            new CampaignProductStatusMail($product, 'approved', null, $admin)
        );

        return back()->with('success', "Product \"{$product->name}\" approved.");
    }

    public function reject(Request $request, CampaignProduct $product): RedirectResponse
    {
        if ($product->approval_status !== 'pending') {
            return back()->with('error', 'Only pending products can be rejected.');
        }

        $data = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $product->update([
            'approval_status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'is_active' => false,
        ]);

        $admin = Auth::user();

        Mail::to($product->user)->queue(
            new CampaignProductStatusMail($product, 'rejected', $data['reason'], $admin)
        );

        return back()->with('success', "Product \"{$product->name}\" rejected.");
    }

    public function bulkApprove(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:campaign_products,id'],
        ]);

        $adminId = Auth::id();

        $updated = DB::transaction(function () use ($data, $adminId) {
            $ids = CampaignProduct::whereIn('id', $data['ids'])
                ->where('approval_status', 'pending')
                ->pluck('id');

            if ($ids->isEmpty()) {
                return 0;
            }

            CampaignProduct::whereIn('id', $ids)->update([
                'approval_status' => 'approved',
                'approved_by'     => $adminId,
                'approved_at'     => now(),
                'is_active'       => true,
            ]);

            SendCampaignProductStatusJob::dispatch(
                $ids->toArray(), 'approved', null, $adminId
            );

            return $ids->count();
        });

        return back()->with('success', "{$updated} product(s) approved.");
    }

    public function bulkReject(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:campaign_products,id'],
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $adminId = Auth::id();

        $updated = DB::transaction(function () use ($data, $adminId) {
            $ids = CampaignProduct::whereIn('id', $data['ids'])
                ->where('approval_status', 'pending')
                ->pluck('id');

            if ($ids->isEmpty()) {
                return 0;
            }

            CampaignProduct::whereIn('id', $ids)->update([
                'approval_status' => 'rejected',
                'approved_by'     => $adminId,
                'approved_at'     => now(),
                'is_active'       => false,
            ]);

            SendCampaignProductStatusJob::dispatch(
                $ids->toArray(), 'rejected', $data['reason'], $adminId
            );

            return $ids->count();
        });

        return back()->with('success', "{$updated} product(s) rejected.");
    }

    public function destroy(CampaignProduct $product): RedirectResponse
    {
        if ($product->reservations()->where('expires_at', '>', now())->exists()) {
            return back()->with('error', 'Cannot delete: product has active reservations.');
        }

        if (\App\Models\DonationItem::where('product_id', $product->id)->exists()) {
            return back()->with('error', 'Cannot delete: product has associated donation items.');
        }

        $name = $product->name;
        $product->delete();

        return back()->with('success', "Product \"{$name}\" deleted.");
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $status = $request->input('status', 'all');
        $search = $request->input('search');
        $source = $request->input('source');
        $categoryId = $request->input('category');

        $query = CampaignProduct::with([
            'campaign:id,title,slug',
            'user:id,name,email',
            'categoryProduct:id,name,category_id',
            'categoryProduct.category:id,name',
            'approver:id,name',
        ]);

        if ($status !== 'all') {
            $query->where('approval_status', $status);
        }

        if ($search) {
            // TODO: Replace leading-wildcard LIKE + orWhereHas with a full-text
            // index or search service (e.g. Scout) once the table grows large.
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('campaign', fn ($cq) => $cq->where('title', 'like', "%{$search}%"))
                    ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        if ($source) {
            $query->where('source', $source);
        }

        if ($categoryId) {
            $query->whereHas('categoryProduct.category', fn ($cq) => $cq->where('id', $categoryId));
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="campaign-products-'.now()->format('Y-m-d').'.csv"',
        ];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID', 'Name', 'Description', 'Price', 'Quantity', 'Remaining',
                'Source', 'Status', 'Campaign', 'Owner', 'Category Product',
                'Category', 'Approved By', 'Approved At', 'Created At',
            ]);

            foreach ($query->latest()->cursor() as $p) {
                fputcsv($file, [
                    $p->id,
                    $p->name,
                    $p->description,
                    $p->price,
                    $p->quantity,
                    $p->remaining_quantity,
                    $p->source,
                    $p->approval_status,
                    $p->campaign?->title,
                    $p->user?->name.' ('.$p->user?->email.')',
                    $p->categoryProduct?->name,
                    $p->categoryProduct?->category?->name,
                    $p->approver?->name,
                    $p->approved_at?->format('Y-m-d H:i:s'),
                    $p->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
