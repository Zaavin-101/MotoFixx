<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
class InvoiceController extends Controller { public function show(Request $r,Invoice $invoice){abort_unless($invoice->order->user_id===$r->user()->id,403);return response()->json($invoice->load(['order.user','order.items','order.payment']));} }
