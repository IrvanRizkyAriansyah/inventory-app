<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\BahanBaku;
use App\Models\Penerimaan;
use App\Models\PengeluaranDetail;
use App\Models\Suplier;
use Illuminate\Support\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array    
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        // Total Bahan Baku (tidak perlu filter)
        $totalBahan = BahanBaku::count();

        // Stok Menipis (tidak perlu filter)
        $stokMenipis = BahanBaku::whereColumn('stok', '<', 'stok_minimum')->count();

        // Total Penerimaan sesuai filter
        $penerimaanQuery = Penerimaan::query();
        if ($startDate) {
            $penerimaanQuery->whereDate('tanggal_terima', '>=', $startDate);
        }
        if ($endDate) {
            $penerimaanQuery->whereDate('tanggal_terima', '<=', $endDate);
        }
        $totalPenerimaan = Penerimaan::count();

        // Total Pengeluaran sesuai filter
        $pengeluaranQuery = PengeluaranDetail::query()
            ->whereHas('pengeluaran', function ($query) use ($startDate, $endDate) {
                if ($startDate) {
                    $query->whereDate('tanggal_pengeluaran', '>=', $startDate);
                }
                if ($endDate) {
                    $query->whereDate('tanggal_pengeluaran', '<=', $endDate);
                }
            });
        
        $totalPengeluaran = $pengeluaranQuery->get()->sum(function ($item) {
            return ($item->bahanBaku?->harga_satuan ?? 0) * $item->qty;
        });

        // Total Supplier (tidak perlu filter)
        $totalSupplier = Suplier::count();

        return [
            Stat::make('Total Supplier', $totalSupplier)
                ->description('Partner penyedia bahan')
                ->icon('heroicon-o-user-group')
                ->color('info'),

            Stat::make('Total Bahan Baku', $totalBahan)
                ->description('Semua bahan yang tersedia')
                ->icon('heroicon-o-archive-box')
                ->color('primary'),

            Stat::make('Stok Menipis', $stokMenipis)
                ->description('Perlu reorder segera')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger'),

            Stat::make('Penerimaan', number_format($totalPenerimaan, 0, ',', '.'))
                ->description('Total nilai barang masuk')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success'),

            Stat::make('Pengeluaran', 'Rp ' . number_format($totalPengeluaran, 0, ',', '.'))
                ->description('Estimasi bahan keluar')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('warning'),
        ];
    }
}
