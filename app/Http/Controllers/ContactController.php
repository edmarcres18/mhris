<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Mail\ContactFormMailBgpdi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function sendEmailMhrhci(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|min:10'
        ]);

        try {
            // Send to company email
            Mail::to('csr.mhrhealthcare@gmail.com')
                ->send(new ContactFormMail($validatedData));

            // Send confirmation to user
            Mail::to($validatedData['email'])
                ->send(new ContactFormMail([
                    'name' => 'MHRHCI Team',
                    'email' => 'csr.mhrhealthcare@gmail.com',
                    'message' => "Thank you for contacting Medical & Hospital Resources Health Care, Inc. We have received your message and will get back to you shortly.\n\nYour original message:\n\n" . $validatedData['message']
                ]));

            return redirect()
                ->back()
                ->with('success', 'Thank you for your message. We will contact you soon!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Sorry, there was an error sending your message. Please try again later.')
                ->withInput();
        }
    }
    public function sendEmailBgpdi(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|min:10'
        ]);

        try {
            // Send to company email
            Mail::to('baygaspdi@mhrpci.ph')
                ->send(new ContactFormMailBgpdi($validatedData));

            // Send confirmation to user
            Mail::to($validatedData['email'])
                ->send(new ContactFormMailBgpdi([
                    'name' => 'Bay Gas Petroleum Distribution Inc.',
                    'email' => 'baygaspdi@mhrpci.ph',
                    'message' => "Thank you for contacting Bay Gas Petroleum Distribution Inc. We have received your message and will get back to you shortly.\n\nYour original message:\n\n" . $validatedData['message']
                ]));

            return redirect()
                ->back()
                ->with('success', 'Thank you for your message. We will contact you soon!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Sorry, there was an error sending your message. Please try again later.')
                ->withInput();
        }
    }
} 