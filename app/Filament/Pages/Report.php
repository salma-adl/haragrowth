<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Actions\Action;
use App\Models\Service;
use App\Models\User;
use App\Models\Booking;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Report extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Laporan';
    protected static ?string $title = 'Laporan Booking';
    protected static string $view = 'filament.pages.report';
    protected static ?string $slug = 'laporan';
    protected static ?int $navigationSort = 100;

    public ?array $data = [];
    public $reportData = null;

    public static function canAccess(): bool
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        return $user && ($user->is_superuser || $user->hasRole('admin'));
    }

    public function mount(): void
    {
        $this->form->fill([
            'start_date' => now()->subMonth()->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
            'service_id' => 'all',
            'therapist_id' => 'all',
            'report_types' => ['booking_count', 'client_count', 'service_recap'],
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Filter Laporan')
                    ->schema([
                        DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->required()
                            ->maxDate(now()),
                        DatePicker::make('end_date')
                            ->label('Tanggal Selesai')
                            ->required()
                            ->maxDate(now())
                            ->afterOrEqual('start_date'),
                        Select::make('service_id')
                            ->label('Layanan')
                            ->options(
                                ['all' => 'Semua Layanan'] + Service::pluck('name', 'id')->toArray()
                            )
                            ->default('all')
                            ->required(),
                        Select::make('therapist_id')
                            ->label('Terapis')
                            ->options(function () {
                                // Get users who have therapist role or admin role who might have profiles
                                // Ideally we list UserProfiles but the filter asks for Therapist Name
                                // We'll list Users who are therapists.
                                // Note: UserProfile has 'name' which might be different from User 'name'.
                                // Let's use UserProfile names since Bookings link to UserProfile.
                                return ['all' => 'Semua Terapis'] + UserProfile::pluck('name', 'id')->toArray();
                            })
                            ->default('all')
                            ->searchable()
                            ->required(),
                        CheckboxList::make('report_types')
                            ->label('Jenis Laporan')
                            ->options([
                                'booking_count' => 'Jumlah Booking',
                                'client_count' => 'Jumlah Klien Unik',
                                'service_recap' => 'Rekap Layanan',
                            ])
                            ->default(['booking_count', 'client_count', 'service_recap'])
                            ->columns(3),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function generateReport()
    {
        $data = $this->form->getState();
        
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);

        if ($startDate->diffInDays($endDate) > 365) {
            $this->addError('data.end_date', 'Rentang tanggal maksimal 1 tahun.');
            return;
        }

        $query = Booking::query()
            ->with(['service', 'userProfile', 'customer'])
            ->whereDate('booking_date', '>=', $startDate)
            ->whereDate('booking_date', '<=', $endDate);

        if ($data['service_id'] !== 'all') {
            $query->where('service_id', $data['service_id']);
        }

        if ($data['therapist_id'] !== 'all') {
            // Here therapist_id is actually UserProfile ID based on the options() logic above
            $query->where('user_profile_id', $data['therapist_id']);
        }
        
        $bookings = $query->get();

        // Process data for the table
        // Group by Therapist -> Service
        $grouped = $bookings->groupBy(function($booking) {
            return $booking->userProfile->name ?? 'Unassigned';
        });

        $report = [];
        foreach ($grouped as $therapistName => $therapistBookings) {
            $serviceGroups = $therapistBookings->groupBy(function($booking) {
                return $booking->service->name ?? 'Unknown Service';
            });

            foreach ($serviceGroups as $serviceName => $serviceBookings) {
                $report[] = [
                    'therapist' => $therapistName,
                    'service' => $serviceName,
                    'booking_count' => $serviceBookings->count(),
                    'client_count' => $serviceBookings->pluck('customer_id')->unique()->count(),
                ];
            }
        }

        $this->reportData = $report;
    }
}
