<?php

namespace App\Services\Admin;

use App\Models\Coupon;
use App\Models\ActivityLog;
use App\Repositories\Admin\CouponRepository;
use Illuminate\Support\Facades\DB;

class CouponService
{
    public function __construct(
        protected CouponRepository $couponRepository
    ) {}

    public function store(array $data): Coupon
    {
        return DB::transaction(function () use ($data) {
            $data['code'] = strtoupper($data['code']);
            $coupon = $this->couponRepository->create($data);

            ActivityLog::log('created', Coupon::class, $coupon->id, [
                'code' => $coupon->code,
                'value' => $coupon->value,
            ]);

            return $coupon;
        });
    }

    public function update(Coupon $coupon, array $data): bool
    {
        return DB::transaction(function () use ($coupon, $data) {
            $original = $coupon->only(['code', 'type', 'value', 'status']);
            
            $data['code'] = strtoupper($data['code']);
            $updated = $this->couponRepository->update($coupon, $data);

            if ($updated) {
                ActivityLog::log('updated', Coupon::class, $coupon->id, [
                    'old' => $original,
                    'new' => $coupon->only(['code', 'type', 'value', 'status']),
                ]);
            }

            return $updated;
        });
    }

    /**
     * Soft delete coupon
     */
    public function destroy(Coupon $coupon): bool
    {
        return DB::transaction(function () use ($coupon) {
            $deleted = $this->couponRepository->delete($coupon);

            if ($deleted) {
                ActivityLog::log('deleted', Coupon::class, $coupon->id, [
                    'code' => $coupon->code,
                ]);
            }

            return $deleted;
        });
    }

    /**
     * Restore soft deleted coupon
     */
    public function restore(Coupon $coupon): bool
    {
        return DB::transaction(function () use ($coupon) {
            $restored = $coupon->restore();

            if ($restored) {
                ActivityLog::log('restored', Coupon::class, $coupon->id, [
                    'code' => $coupon->code,
                ]);
            }

            return $restored;
        });
    }

    /**
     * Force delete coupon
     */
    public function forceDelete(Coupon $coupon): bool
    {
        return DB::transaction(function () use ($coupon) {
            $couponCode = $coupon->code;
            $couponId = $coupon->id;
            $deleted = $coupon->forceDelete();

            if ($deleted) {
                ActivityLog::log('force_deleted', Coupon::class, $couponId, [
                    'code' => $couponCode,
                ]);
            }

            return $deleted;
        });
    }

    /**
     * Bulk Soft Delete
     */
    public function bulkDelete(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $count = 0;
            foreach ($ids as $id) {
                $coupon = Coupon::find($id);
                if ($coupon && $this->destroy($coupon)) {
                    $count++;
                }
            }
            return $count;
        });
    }

    /**
     * Bulk Restore
     */
    public function bulkRestore(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $count = 0;
            foreach ($ids as $id) {
                $coupon = Coupon::onlyTrashed()->find($id);
                if ($coupon && $this->restore($coupon)) {
                    $count++;
                }
            }
            return $count;
        });
    }

    /**
     * Bulk Force Delete
     */
    public function bulkForceDelete(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            $count = 0;
            foreach ($ids as $id) {
                $coupon = Coupon::withTrashed()->find($id);
                if ($coupon && $this->forceDelete($coupon)) {
                    $count++;
                }
            }
            return $count;
        });
    }

    /**
     * Export Coupons to CSV
     */
    public function exportCsv(): string
    {
        $coupons = Coupon::withTrashed()->get();
        $handle = fopen('php://temp', 'r+');
        
        // CSV headers
        fputcsv($handle, ['ID', 'Code', 'Type', 'Value', 'Cart Value', 'Expiry Date', 'Status', 'Deleted At']);

        foreach ($coupons as $coupon) {
            fputcsv($handle, [
                $coupon->id,
                $coupon->code,
                $coupon->type,
                $coupon->value,
                $coupon->cart_value,
                $coupon->expiry_date ? $coupon->expiry_date->toDateString() : '',
                $coupon->status ? 'Active' : 'Inactive',
                $coupon->deleted_at ? $coupon->deleted_at->toDateTimeString() : '',
            ]);
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        ActivityLog::log('exported', Coupon::class, null, ['format' => 'CSV']);

        return $csvContent;
    }

    /**
     * Import Coupons from CSV
     */
    public function importCsv(string $filePath): int
    {
        if (!file_exists($filePath)) {
            return 0;
        }

        $count = 0;
        if (($handle = fopen($filePath, 'r')) !== false) {
            fgetcsv($handle); // Read headers
            
            DB::transaction(function () use ($handle, &$count) {
                while (($row = fgetcsv($handle)) !== false) {
                    if (count($row) < 5) continue;

                    $code = strtoupper($row[1] ?? '');
                    $type = $row[2] ?? 'percent';
                    $value = (float)($row[3] ?? 0.00);
                    $cart_value = (float)($row[4] ?? 0.00);
                    $expiry_date = !empty($row[5]) ? $row[5] : now()->addDays(30)->toDateString();
                    $status = isset($row[6]) && strtolower($row[6]) === 'active';

                    Coupon::updateOrCreate(
                        ['code' => $code],
                        [
                            'type' => $type,
                            'value' => $value,
                            'cart_value' => $cart_value,
                            'expiry_date' => $expiry_date,
                            'status' => $status,
                        ]
                    );
                    $count++;
                }
            });
            
            fclose($handle);
        }

        ActivityLog::log('imported', Coupon::class, null, ['count' => $count]);

        return $count;
    }
}
