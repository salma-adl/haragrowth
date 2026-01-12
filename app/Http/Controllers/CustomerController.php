<?php

namespace App\Http\Controllers;

// use App\Models\Blog;

use App\Mail\CompanyNotification;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Mail\CustomerNotification;
use App\Models\CustomerFeedback;
use App\Models\MailConfiguration;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{

    protected function getMailConfiguration()
    {
        $config = MailConfiguration::where('is_active', true)->first();
        $explicitMailer = env('MAIL_MAILER');
        $resendKey = config('services.resend.key');

        if ((!$explicitMailer || $explicitMailer === 'smtp') && $resendKey && !app()->environment('local')) {
            config(['mail.default' => 'resend']);
        }

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
        // Validasi input
        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255',
            'name' => 'required|string|max:255',
            'company' => 'nullable|string',
            'topic' => 'required|string|max:255',
            'message' => 'required|string|max:255',
            'is_subscribe' => 'required|boolean',
        ]);

        $customer = Customer::where('email', $validatedData['email'])->first();
    
        if (!$customer) {
            $customer = Customer::create([
                'email' => $validatedData['email'],
                'name' => $validatedData['name'],
                'company' => $validatedData['company'],
                'is_subscribe' => $validatedData['is_subscribe'], 
            ]);
        }

        $validatedData['customer_id'] = $customer->id;
    
        $customerFeedback = CustomerFeedback::create([
            'customer_id' => $validatedData['customer_id'],
            'topic' => $validatedData['topic'],
            'message' => $validatedData['message'],  
            'is_subscribe' => $validatedData['is_subscribe'],
        ]);

        Mail::to($validatedData['email'])->send(new CustomerNotification($validatedData));
        $this->sendCompanyNotification($validatedData);
        return response()->json([
            'message' => 'Customer created successfully, and notification email sent.'
        ], 201);
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
        $filteredData = collect($data)->only(['email', 'name', 'company', 'topic', 'message'])->toArray();
        
        $filteredData['customerMessage'] = $filteredData['message'];
        unset($filteredData['message']);  
        
        $companyEmail = config('mail.from.address');
        
        Mail::to($companyEmail)->send(new CompanyNotification($filteredData));
    }
}
