<?php

namespace App\Filament\Widgets;

use App\Models\Penerimaan;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class PenerimaanChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected int|string|array $columnSpan = '1';
    protected static ?string $heading = 'Penerimaan Bahan';

    protected function getData(): array
    {
        $query = Penerimaan::query();

        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        if ($startDate) {
            $query->whereDate('tanggal_terima', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('tanggal_terima', '<=', $endDate);
        }

        $data = $query->selectRaw('MONTH(tanggal_terima) as bulan, SUM(total_biaya) as total')
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
                    'label' => 'Total Penerimaan (Rp)',
                    'data' => $values,
                    'borderColor' => '#f59e0b', // orange line
                    'backgroundColor' => 'rgba(245, 158, 11, 0.2)', // translucent fill
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
