<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Mail\CompanyNotification;
use App\Models\Customer;
use App\Mail\CustomerNotification;
use App\Models\MailConfiguration;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class BookingController extends Controller
{
    protected function getMailConfiguration()
    {
        $config = MailConfiguration::where('is_active', true)->first();
        if ($config) {
            $mailPassword = $config->mail_password;

            config([
                'mail.mailers.smtp.host' => $config->mail_host,
                'mail.mailers.smtp.port' => $config->mail_port,
                'mail.mailers.smtp.username' => $config->mail_username,
                'mail.mailers.smtp.password' => $mailPassword,
                'mail.mailers.smtp.encryption' => $config->mail_encryption,
                'mail.from.address' => $config->mail_from_address,
                'mail.from.name' => $config->mail_from_name,
            ]);
        }
    }

    public function store(Request $request)
    {
        $this->getMailConfiguration();

        // Validasi input termasuk reCAPTCHA
        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255',
            'name' => 'required|string|max:255',
            'gender' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'age' => 'nullable|integer|min:0',
            'service_id' => 'required|string|exists:services,id',
            'schedule_id' => 'required|string|exists:schedules,id',
            'notes' => 'nullable|string|max:1000',
            'g-recaptcha-response' => 'required',
        ]);

        // Check if the time slot is already booked
        if (!$this->isTimeSlotAvailable($validatedData['schedule_id'])) {
            return redirect()->back()->withErrors(['schedule_id' => 'Jadwal ini sudah dipesan. Silakan pilih jadwal lain.'])->withInput();
        }

        // Verifikasi CAPTCHA
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (!$response->json('success')) {
            return redirect()->back()->withErrors(['g-recaptcha-response' => 'Verifikasi CAPTCHA gagal.'])->withInput();
        }
        // Generate kode booking
        $bookingCode = $this->generateBookingCode();

        // Buat atau update customer
        $customer = Customer::where('email', $validatedData['email'])->first();
        if (!$customer) {
            $customer = Customer::create([
                'email' => $validatedData['email'],
                'name' => $validatedData['name'],
                'gender' => $validatedData['gender'],
                'phone' => $validatedData['phone'],
                'age' => $validatedData['age'],
            ]);
        } else {
            $customer->update([
                'name' => $validatedData['name'],
                'gender' => $validatedData['gender'],
                'phone' => $validatedData['phone'],
                'age' => $validatedData['age'],
            ]);
        }

        $validatedData['customer_id'] = $customer->id;
        $validatedData['booking_code'] = $bookingCode;
        $validatedData['url_book'] = config('app.url') . '/book';

        // Simpan data booking
        $bookingData = Booking::create([
            'booking_code' => $bookingCode,
            'customer_id' => $validatedData['customer_id'],
            'service_id' => $validatedData['service_id'],
            'schedule_id' => $validatedData['schedule_id'],
            'notes' => $validatedData['notes'],
        ]);

        // Kirim email
        Mail::to($validatedData['email'])->send(new CustomerNotification($validatedData));
        $this->sendCompanyNotification($validatedData);

        return redirect()->back()->with('success', 'Janji berhasil dibuat dan email notifikasi telah dikirim.');
    }

    public function show($id)
    {
        return Customer::with(['blog'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'blog_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|string',
            'comment' => 'required|string',
        ]);

        $blog = Customer::findOrFail($id);
        $blog->update($validatedData);

        return $blog;
    }

    public function destroy($id)
    {
        Customer::destroy($id);
        return response()->noContent();
    }

    /**
     * Send notification to company.
     *
     * @param  array  $data
     * @return void
     */
    protected function sendCompanyNotification($data)
    {
        $filteredData = collect($data)->only(['email', 'name', 'notes'])->toArray();

        $filteredData['customerMessage'] = $filteredData['notes'];
        unset($filteredData['notes']);

        $companyEmail = config('mail.from.address');

        Mail::to($companyEmail)->send(new CompanyNotification($filteredData));
    }

    protected function generateBookingCode(): string
    {
        $prefix = 'HG';
        $datePart = date('Ymd');
        $randomPart = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6)); // 6 karakter acak

        return $prefix . '-' . $datePart . '-' . $randomPart;
    }

    /**
     * Check if a time slot is available for booking.
     *
     * @param  int  $scheduleId
     * @return bool
     */
    protected function isTimeSlotAvailable($scheduleId): bool
    {
        return !Booking::where('schedule_id', $scheduleId)
            ->whereIn('status', ['booked', 'in_session'])
            ->exists();
    }
}
