<?php
namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class MidtransWebhookController extends Controller {
    public function __invoke(Request $request) {
        $payload = $request->validate(['order_id'=>'required|string','status_code'=>'required|string','gross_amount'=>'required|string','signature_key'=>'required|string','transaction_status'=>'required|string','transaction_id'=>'nullable|string','payment_type'=>'nullable|string','fraud_status'=>'nullable|string']);
        $key = (string) config('services.midtrans.server_key');
        if ($key === '') return response('Midtrans is not configured',503);
        $expected = hash('sha512', $payload['order_id'].$payload['status_code'].$payload['gross_amount'].$key);
        if (!hash_equals($expected, $payload['signature_key'])) { Log::warning('Rejected invalid Midtrans signature'); return response('Invalid signature',403); }
        $order = Order::where('order_number',$payload['order_id'])->with('payment')->first();
        if (!$order || !$order->payment) return response('Order not found',404);
        if ((int) round((float)$payload['gross_amount']) !== (int)$order->total) return response('Amount mismatch',422);
        DB::transaction(function () use ($order, $payload) {
            $payment = $order->payment()->lockForUpdate()->first();
            $status = $payload['transaction_status']; $fraud = $payload['fraud_status'] ?? null;
            $mapped = match (true) {
                $status === 'settlement' => 'paid',
                $status === 'capture' && in_array($fraud, ['accept', null], true) => 'paid',
                $status === 'pending' => 'pending',
                in_array($status, ['deny','cancel','expire'], true) => 'failed',
                default => $payment->status,
            };
            if ($payment->status !== 'paid' || $mapped === 'paid') $payment->update(['status'=>$mapped,'transaction_id'=>$payload['transaction_id'] ?? $payment->transaction_id,'raw_response'=>$payload]);
        });
        return response('OK',200);
    }
}
