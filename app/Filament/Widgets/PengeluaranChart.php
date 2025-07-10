<?php

namespace App\Filament\Widgets;

use App\Models\PengeluaranDetail;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class PengeluaranChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Pengeluaran Bahan per Bulan';
    protected int | string | array $columnSpan = '1';

    protected function getData(): array
    {
        $query = PengeluaranDetail::query()
        ->join('pengeluarans', 'pengeluarans.id', '=', 'pengeluaran_details.pengeluaran_id');

        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        if ($startDate) {
            $query->whereDate('pengeluarans.tanggal_pengeluaran', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('pengeluarans.tanggal_pengeluaran', '<=', $endDate);
        }


        $data = $query
        ->selectRaw('MONTH(pengeluarans.tanggal_pengeluaran) as bulan, SUM(pengeluaran_details.qty) as total')
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->pluck('total', 'bulan');

        $labels = [];
        $values = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = Carbon::create()->month($i)->format('M');
            $values[] = $data[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Pengeluaran',
                    'data' => $values,
                    'borderColor' => '#f97316', // orange
                    'backgroundColor' => 'rgba(249, 115, 22, 0.2)', // translucent fill
                    'tension' => 0.4, // smooth curve
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
