<?php
namespace App\Services;

use App\Interfaces\PaymentGatewayInterface;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymobPaymentService extends BasePaymentService implements PaymentGatewayInterface
{
    /**
     * Create a new class instance.
     */
    protected $api_key;
    protected $integrations_id;

    public function __construct()
    {
        $this->base_url = env("BAYMOB_BASE_URL");
        $this->api_key  = env("BAYMOB_API_KEY");
        $this->header   = [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $this->integrations_id = [5423510, 4480543];
    }

//first generate token to access api
    protected function generateToken()
    {
        $response = $this->buildRequest('POST', '/api/auth/tokens', ['api_key' => $this->api_key]);
        return $response->getData(true)['data']['token'];
    }

    protected function toCentsFromInput(array $data): int
    {
        return (int) round(((float) $data['amount_cents']) * 100);
    }

    public function sendPayment(Request $request): array
    {
        try {
            $this->header['Authorization'] = 'Bearer ' . $this->generateToken();

            // استقبل إما amount بالجنيه أو amount_cents بالقرش
            $data = $request->validate([
                'amount_cents'      => 'required',
                'currency'          => 'required|string',
                'group_register_id' => 'required|exists:group_registers,id',
            ]);

            $amountCents = $this->toCentsFromInput($data);

            $data['api_source']    = "INVOICE";
            $data['integrations']  = $this->integrations_id;
            $data['amount_cents']  = $amountCents;
            $user                  = Auth::user();
            $data['shipping_data'] = [
                'first_name'   => $user->first_name,
                'last_name'    => $user->last_name,
                'phone_number' => $user->phone,
                'email'        => $user->email,
                'group_id'     => 1,
            ];

            // أنشئ اشتراك pending محليًا قبل التحويل للبوابة

            $response = $this->buildRequest('POST', '/api/ecommerce/orders', $data);
            $order    = $response->getData(true)['data']['shipping_data']['order'];

// dd($response);
            if ($response->getData(true)['success']) {
                $this->createPendingSubscription($request, $order);
                return [
                    'success' => true,
                    'url'     => $response->getData(true)['data']['url'],
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error'   => $e->getMessage(),
            ];
        }

        return [
            'success' => false,
            'url'     => route('payment.failed'),
        ];
    }

    public function callBack(Request $request): bool
    {
        Storage::put('paymob_response.json', json_encode($request->all()));

        $success = filter_var($request->input('success'), FILTER_VALIDATE_BOOLEAN);

        if ($success) {
            $subscription = $this->markLatestPendingSubscriptionPaid($request['order']);
            $this->savePayment($request, $success, $subscription->user_id);
            return true;
        }

        return false;
    }

    protected function savePayment(Request $request, bool $success, $useId): void
    {
        // Paymob غالبًا يرجّع amount_cents في الـ callback
        $amountCents = (int) $request->input('amount_cents', 0);
        $calc_price  = $amountCents > 0 ? ($amountCents / 100) : 0;

        $payment                 = new Payment();
        $payment->user_id        = $useId;
        $payment->price          = $calc_price;
        $payment->currency       = $request->input('currency');
        $payment->payment_type   = 'paymob';
        $payment->status_payment = $success ? 'paid' : 'canceled';
        $payment->save();
    }

    protected function createPendingSubscription(Request $request, $order): void
    {

        $subscription                    = new Subscription();
        $subscription->user_id           = Auth::id();
        $subscription->group_register_id = $request->input('group_register_id');
        $subscription->price             = $request->input('amount_cents');
        $subscription->start_date        = now();
        $subscription->end_date          = now()->addDays(30);
        $subscription->order             = $order;
        $subscription->status_payment    = 'pending';
        $subscription->save();
    }

    protected function markLatestPendingSubscriptionPaid($order)
    {
        $subscription = Subscription::where('order', $order)
            ->where('status_payment', 'pending')
            ->latest('id')
            ->first();

        if ($subscription) {
            $subscription->status_payment = 'paid';
            $subscription->start_date     = $subscription->start_date ?? now();
            $subscription->end_date       = $subscription->end_date ?? now()->addDays(30);
            $subscription->save();
        }
        return $subscription;
    }

}
