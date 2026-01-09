<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanyProfileController extends Controller
{
    public function home()
    {
        $mService = Service::all();
        $schedules = $mService->flatMap(function ($service) {
            return $service->schedules;
        });
        $experts = UserProfile::all();
        $news = News::all();
        return view('company-profile.home', compact('mService', 'schedules', 'experts', 'news'));
    }

    public function getSchedules($service_id)
    {
        if (!Service::find($service_id)) {
            return response()->json(['error' => 'Service not found'], 404);
        }

        $schedules = Schedule::where('service_id', $service_id)->get();
        return response()->json($schedules);
    }

    public function getTherapis($serviceId)
    {
        $psychologists = UserProfile::whereHas('services', function ($query) use ($serviceId) {
            $query->where('services.id', $serviceId);
        })
            ->select('name', 'gender', 'attachment')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'gender' => $item->gender,
                    'attachment' => asset('storage/' . $item->attachment),
                ];
            });
        return response()->json($psychologists);
    }

    public function getTherapisByid($id)
    {
        $therapist = UserProfile::with('services') // eager load services
            ->select('id', 'name', 'gender', 'attachment', 'str_number', 'sipp_number', 'bio')
            ->find($id);

        if (!$therapist) {
            return response()->json(['error' => 'Terapis tidak ditemukan'], 404);
        }

        return response()->json([
            'name' => $therapist->name,
            'gender' => $therapist->gender,
            'attachment' => $therapist->attachment ? asset('storage/' . $therapist->attachment) : null,
            'str_number' => $therapist->str_number,
            'sipp_number' => $therapist->sipp_number,
            'bio' => $therapist->bio,
            'services' => $therapist->services->pluck('name')->toArray(), // array nama layanan
        ]);
    }
}
