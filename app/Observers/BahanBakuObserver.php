<?php

namespace App\Observers;

use App\Models\BahanBaku;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class BahanBakuObserver
{
    public function saved(BahanBaku $bahanBaku): void
    {
        if ($bahanBaku->stok <= $bahanBaku->stok_minimum) {
            $notifiableUser = Auth::user() ?? User::first(); // fallback user jika auth null

            Notification::make()
                ->title('⚠️ Stok bahan baku rendah')
                ->body("Stok untuk <strong>{$bahanBaku->nama_bahan_baku}</strong> telah mencapai atau di bawah minimum.")
                ->danger()
                ->sendToDatabase($notifiableUser); 
        }
    }
}
