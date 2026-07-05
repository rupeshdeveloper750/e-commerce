<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCouponRequest;
use App\Http\Requests\Admin\UpdateCouponRequest;
use App\Models\Coupon;
use App\Repositories\Admin\CouponRepository;
use App\Services\Admin\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class CouponController extends Controller
{
    public function __construct(
        protected CouponRepository $couponRepository,
        protected CouponService $couponService
    ) {}

    public function index(Request $request)
    {
        Gate::authorize('manage-coupons');

        $filters = $request->only(['search', 'status', 'trashed', 'sort_by', 'sort_order']);
        $coupons = $this->couponRepository->getAllPaginated($filters, 10);

        return view('admin.coupon.index', compact('coupons'));
    }

    public function create()
    {
        Gate::authorize('manage-coupons');

        return view('admin.coupon.create');
    }

    public function store(StoreCouponRequest $request)
    {
        Gate::authorize('manage-coupons');

        $this->couponService->store($request->validated());

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    public function show($id)
    {
        Gate::authorize('manage-coupons');

        $coupon = $this->couponRepository->find($id, true);

        return view('admin.coupon.show', compact('coupon'));
    }

    public function edit($id)
    {
        Gate::authorize('manage-coupons');

        $coupon = $this->couponRepository->find($id, true);

        return view('admin.coupon.edit', compact('coupon'));
    }

    public function update(UpdateCouponRequest $request, $id)
    {
        Gate::authorize('manage-coupons');

        $coupon = Coupon::withTrashed()->findOrFail($id);
        $this->couponService->update($coupon, $request->validated());

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        Gate::authorize('manage-coupons');

        $this->couponService->destroy($coupon);

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Coupon soft-deleted successfully.');
    }

    public function restore($id)
    {
        Gate::authorize('manage-coupons');

        $coupon = Coupon::onlyTrashed()->findOrFail($id);
        $this->couponService->restore($coupon);

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Coupon restored successfully.');
    }

    public function forceDelete($id)
    {
        Gate::authorize('manage-coupons');

        $coupon = Coupon::withTrashed()->findOrFail($id);
        $this->couponService->forceDelete($coupon);

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Coupon permanently deleted.');
    }

    public function bulkAction(Request $request)
    {
        Gate::authorize('manage-coupons');

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'numeric',
            'action' => 'required|string|in:delete,restore,force_delete',
        ]);

        $ids = $request->ids;
        $action = $request->action;
        $count = 0;

        if ($action === 'delete') {
            $count = $this->couponService->bulkDelete($ids);
            $msg = "{$count} coupons soft-deleted successfully.";
        } elseif ($action === 'restore') {
            $count = $this->couponService->bulkRestore($ids);
            $msg = "{$count} coupons restored successfully.";
        } elseif ($action === 'force_delete') {
            $count = $this->couponService->bulkForceDelete($ids);
            $msg = "{$count} coupons permanently deleted.";
        }

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', $msg);
    }

    public function export()
    {
        Gate::authorize('manage-coupons');

        $csv = $this->couponService->exportCsv();

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="coupons-export-' . now()->format('Y-m-d') . '.csv"',
        ]);
    }

    public function import(Request $request)
    {
        Gate::authorize('manage-coupons');

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:4096',
        ]);

        $file = $request->file('csv_file');
        $count = $this->couponService->importCsv($file->getRealPath());

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', "Successfully imported {$count} coupons.");
    }
}
