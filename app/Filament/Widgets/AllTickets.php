<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class AllTickets extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    public function mount(): void
    {
        self::$heading = __('All tickets');
    }

    public static function canView(): bool
    {
        return auth()->user()->can('List tickets');
    }

    protected function getTableQuery(): Builder
    {
        return Ticket::query()
            ->where(function ($query) {
                return $query->where('owner_id', auth()->user()->id)
                    ->orWhere('responsible_id', auth()->user()->id)
                    ->orWhereHas('project', function ($query) {
                        return $query->where('owner_id', auth()->user()->id)
                            ->orWhereHas('users', function ($query) {
                                return $query->where('users.id', auth()->user()->id);
                            });
                    });
            })
            ->latest();
    }

    protected function isTablePaginationEnabled(): bool
    {
        return true;
    }

    protected function getTableColumns(): array
    {
        return \App\Filament\Resources\TicketResource::tableColumns();
    }
}
