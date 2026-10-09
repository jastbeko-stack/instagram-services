<?php

namespace App\Services;

use App\Models\SmmProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmmApiService
{
    /**
     * Add an order to an external SMM provider using Standard API v2.
     */
    public function placeOrder(SmmProvider $provider, string $serviceId, string $link, int $quantity): array
    {
        try {
            $response = Http::timeout(20)->asForm()->post($provider->api_url, [
                'key' => $provider->api_key,
                'action' => 'add',
                'service' => $serviceId,
                'link' => $link,
                'quantity' => $quantity,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['order'])) {
                    return [
                        'success' => true,
                        'order_id' => (string)$data['order'],
                        'raw' => $data,
                    ];
                }

                return [
                    'success' => false,
                    'error' => $data['error'] ?? 'فشل إنشاء الطلب لدى المزود',
                    'raw' => $data,
                ];
            }

            return [
                'success' => false,
                'error' => 'تعذر الاتصال بسيرفر المزود: كود الحالة ' . $response->status(),
            ];
        } catch (\Throwable $e) {
            Log::error('SMM API Order Placement Exception: ' . $e->getMessage(), [
                'provider' => $provider->id,
                'service' => $serviceId,
            ]);

            return [
                'success' => false,
                'error' => 'خطأ اتصال: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check status of an order from external provider.
     */
    public function checkOrderStatus(SmmProvider $provider, string $providerOrderId): array
    {
        try {
            $response = Http::timeout(15)->asForm()->post($provider->api_url, [
                'key' => $provider->api_key,
                'action' => 'status',
                'order' => $providerOrderId,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['status'])) {
                    return [
                        'success' => true,
                        'status' => $this->normalizeStatus($data['status']),
                        'charge' => $data['charge'] ?? null,
                        'start_count' => $data['start_count'] ?? null,
                        'remains' => $data['remains'] ?? null,
                    ];
                }
            }

            return ['success' => false, 'error' => 'تعذر استرجاع حالة الطلب'];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get account balance from external provider.
     */
    public function getProviderBalance(SmmProvider $provider): array
    {
        try {
            $response = Http::timeout(15)->asForm()->post($provider->api_url, [
                'key' => $provider->api_key,
                'action' => 'balance',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['balance'])) {
                    return [
                        'success' => true,
                        'balance' => (float)$data['balance'],
                        'currency' => $data['currency'] ?? 'USD',
                    ];
                }
            }

            return ['success' => false, 'error' => 'تعذر قراءة رصيد المزود'];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Fetch list of available services from external provider.
     */
    public function fetchServices(SmmProvider $provider): array
    {
        try {
            $response = Http::timeout(30)->asForm()->post($provider->api_url, [
                'key' => $provider->api_key,
                'action' => 'services',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (is_array($data)) {
                    return ['success' => true, 'services' => $data];
                }
            }

            return ['success' => false, 'error' => 'تعذر جلب الخدمات من المزود'];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Map external provider status to internal status format.
     */
    public function normalizeStatus(string $externalStatus): string
    {
        $status = strtolower(trim($externalStatus));

        return match ($status) {
            'pending' => 'pending',
            'in progress', 'processing' => 'in_progress',
            'completed' => 'completed',
            'partial' => 'partial',
            'canceled', 'refunded' => 'canceled',
            default => 'pending',
        };
    }
}
