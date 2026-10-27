<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\Database\Configuration;
use App\Models\Database\Contact;
use App\Models\Database\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendContactController extends Controller
{
    public function sendContact(Request $request)
    {
        $bypassLocal = app()->isLocal() && config('services.recaptcha.bypass_local', false);
        $recaptchaRequired = ! $bypassLocal && filled(config('services.recaptcha.site_key'));

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:100'],
            'service_id' => ['nullable'],
            'service_ids' => ['nullable', 'array'],
            'service_ids.*' => ['integer', 'exists:services,id'],
            'message' => ['required', 'string', 'max:5000'],
            'g-recaptcha-response' => [$recaptchaRequired ? 'required' : 'nullable', 'string'],
        ], [
            'g-recaptcha-response.required' => 'Verifikasi keamanan belum berhasil. Silakan coba lagi.',
        ]);

        if (! $bypassLocal) {
            $secretKey = config('services.recaptcha.secret_key');

            if (blank($secretKey)) {
                return back()
                    ->withInput()
                    ->withFragment('contact-form')
                    ->withErrors([
                        'g-recaptcha-response' => 'Konfigurasi reCAPTCHA belum tersedia.',
                    ]);
            }

            try {
                $recaptchaResponse = Http::asForm()
                    ->timeout(10)
                    ->post('https://www.google.com/recaptcha/api/siteverify', [
                        'secret' => $secretKey,
                        'response' => $validated['g-recaptcha-response'] ?? '',
                        'remoteip' => $request->ip(),
                    ]);

                $recaptcha = $recaptchaResponse->json();
            } catch (\Throwable $exception) {
                return back()
                    ->withInput()
                    ->withFragment('contact-form')
                    ->withErrors([
                        'g-recaptcha-response' => 'Verifikasi keamanan sedang bermasalah. Silakan coba lagi.',
                    ]);
            }

            $errorCodes = (array) ($recaptcha['error-codes'] ?? []);
            $recaptchaContext = [
                'http_status' => $recaptchaResponse->status(),
                'success' => (bool) ($recaptcha['success'] ?? false),
                'action' => $recaptcha['action'] ?? null,
                'score' => isset($recaptcha['score']) ? (float) $recaptcha['score'] : null,
                'hostname' => $recaptcha['hostname'] ?? null,
                'error_codes' => $errorCodes,
            ];

            if (! $recaptchaResponse->successful() || ($recaptcha['success'] ?? false) !== true) {
                Log::warning('Contact reCAPTCHA verification was rejected.', $recaptchaContext);

                $message = match (true) {
                    in_array('invalid-input-secret', $errorCodes, true) => 'Secret key reCAPTCHA tidak valid.',
                    in_array('timeout-or-duplicate', $errorCodes, true) => 'Verifikasi keamanan sudah kedaluwarsa. Silakan kirim ulang formulir.',
                    in_array('invalid-input-response', $errorCodes, true) => 'Token reCAPTCHA ditolak. Pastikan site key dan secret key berasal dari reCAPTCHA v3 yang sama.',
                    in_array('browser-error', $errorCodes, true) => 'Domain localhost/127.0.0.1 belum didaftarkan di Google reCAPTCHA Console untuk site key ini. Silakan tambahkan localhost di Google reCAPTCHA Admin Console.',
                    default => 'Verifikasi keamanan ditolak oleh Google. Silakan coba lagi.',
                };

                return back()
                    ->withInput()
                    ->withFragment('contact-form')
                    ->withErrors([
                        'g-recaptcha-response' => $message,
                    ]);
            }

            if (($recaptcha['action'] ?? null) !== 'contact_form') {
                Log::warning('Contact reCAPTCHA action did not match.', $recaptchaContext);

                return back()
                    ->withInput()
                    ->withFragment('contact-form')
                    ->withErrors([
                        'g-recaptcha-response' => 'Action reCAPTCHA tidak sesuai. Silakan muat ulang halaman.',
                    ]);
            }

            $score = (float) ($recaptcha['score'] ?? 0);
            $minimumScore = (float) config('services.recaptcha.min_score', 0.5);

            if ($score < $minimumScore) {
                Log::warning('Contact reCAPTCHA score was below the minimum.', $recaptchaContext + [
                    'minimum_score' => $minimumScore,
                ]);

                return back()
                    ->withInput()
                    ->withFragment('contact-form')
                    ->withErrors([
                        'g-recaptcha-response' => "Skor keamanan terlalu rendah ({$score}). Silakan coba lagi.",
                    ]);
            }
        }

        $serviceIds = collect($request->input('service_ids', []))
            ->filter()
            ->all();

        if (empty($serviceIds) && filled($request->input('service_id'))) {
            $serviceIds = is_array($request->input('service_id'))
                ? $request->input('service_id')
                : [$request->input('service_id')];
        }

        $selectedServices = ! empty($serviceIds)
            ? Service::whereIn('id', $serviceIds)->pluck('title')->all()
            : [];

        $serviceTitles = ! empty($selectedServices)
            ? implode(', ', $selectedServices)
            : null;

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'company' => $validated['company'] ?? null,
            'industry' => $validated['industry'] ?? null,
            'service' => $serviceTitles,
            'message' => $validated['message'],
        ];

        $configuration = Configuration::first();
        $contact = Contact::first();

        if (! $contact) {
            return back()->with('error', 'Data contact belum tersedia!');
        }

        $email = $contact->email ?: $contact->email_2;

        if (! $email) {
            return back()->with('error', 'Email tujuan belum diatur!');
        }

        $siteName = $configuration?->name_page ?: config('app.name', 'Website');

        // Cari path fisik dan URL logo yang valid
        $logoCandidates = [
            $configuration?->logo_hitam_page,
            $configuration?->logo_putih_page,
            $configuration?->favicon_page,
            'seeders/logo-putih.webp',
            'frontend/assets/images/logo.png',
        ];

        $logoPath = null;
        $logoUrl = null;

        foreach ($logoCandidates as $candidate) {
            if (! filled($candidate)) {
                continue;
            }

            $relativePath = ltrim(parse_url($candidate, PHP_URL_PATH) ?: $candidate, '/\\');

            $candidatePaths = [
                ($relativePath),
                base_path('public/'.$relativePath),
                base_path($relativePath),
            ];

            foreach ($candidatePaths as $p) {
                if (is_file($p)) {
                    $logoPath = $p;
                    $logoUrl = url($relativePath);
                    break 2;
                }
            }
        }

        // Konversi WebP ke PNG khusus untuk email agar kompatibel di semua email client (seperti Outlook & Gmail)
        $embeddedLogoPath = $logoPath;
        if ($logoPath && strtolower(pathinfo($logoPath, PATHINFO_EXTENSION)) === 'webp' && function_exists('imagecreatefromwebp') && function_exists('imagepng')) {
            try {
                $cacheDir = storage_path('framework/cache');
                if (! is_dir($cacheDir)) {
                    @mkdir($cacheDir, 0755, true);
                }
                $pngTarget = $cacheDir.'/email_logo_'.md5_file($logoPath).'.png';
                if (! file_exists($pngTarget) || filemtime($pngTarget) < filemtime($logoPath)) {
                    $im = @imagecreatefromwebp($logoPath);
                    if ($im) {
                        imagealphablending($im, true);
                        imagesavealpha($im, true);
                        imagepng($im, $pngTarget);
                        imagedestroy($im);
                    }
                }
                if (file_exists($pngTarget) && filesize($pngTarget) > 0) {
                    $embeddedLogoPath = $pngTarget;
                }
            } catch (\Throwable $e) {
                // Gunakan file asli bila terjadi error konversi
            }
        }

        $contactMail = (new ContactMail($data, $siteName, $logoUrl ?: null, $embeddedLogoPath ?: null))
            ->from(config('mail.from.address'), config('mail.from.name'));

        if (filter_var($data['email'] ?? null, FILTER_VALIDATE_EMAIL)) {
            $contactMail->replyTo($data['email'], $data['name'] ?? null);
        }

        Mail::to($email)->send($contactMail);

        return back()
            ->withFragment('contact-form')
            ->with('success', "Terima kasih. Tim {$siteName} akan segera menghubungi Anda.");
    }
}
