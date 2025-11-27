<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
  /**
   * Display checkout page
   */
  public function index()
  {
    $cart = $this->getCart();

    if (!$cart || $cart->items()->count() === 0) {
      return redirect()->route('cart.index')
        ->with('error', 'Giỏ hàng của bạn đang trống');
    }

    $items = $cart->items()->with(['suitModel', 'fabric', 'configuration'])->get();

    return view('checkout.index', compact('cart', 'items'));
  }

  /**
   * Process checkout
   */
  public function process(Request $request)
  {
    $validated = $request->validate([
      'customer_name' => 'required|string|max:255',
      'customer_email' => 'required|email|max:255',
      'customer_phone' => 'required|string|max:20',
      'shipping_address' => 'required|string|max:500',
      'city' => 'required|string|max:100',
      'district' => 'required|string|max:100',
      'ward' => 'nullable|string|max:100',
      'payment_method' => 'required|in:momo,vnpay,bank_transfer,cod',
      'customer_notes' => 'nullable|string|max:1000',
    ]);

    $cart = $this->getCart();

    if (!$cart || $cart->items()->count() === 0) {
      return redirect()->route('cart.index')
        ->with('error', 'Giỏ hàng của bạn đang trống');
    }

    try {
      DB::beginTransaction();

      // Create order
      $order = Order::create([
        'user_id' => auth()->id(),
        'customer_name' => $validated['customer_name'],
        'customer_email' => $validated['customer_email'],
        'customer_phone' => $validated['customer_phone'],
        'shipping_address' => $validated['shipping_address'],
        'city' => $validated['city'],
        'district' => $validated['district'],
        'ward' => $validated['ward'] ?? null,
        'subtotal' => $cart->subtotal,
        'shipping_fee' => $cart->shipping_fee,
        'discount' => $cart->discount,
        'discount_code' => $cart->discount_code,
        'total' => $cart->total,
        'payment_method' => $validated['payment_method'],
        'payment_status' => Order::PAYMENT_PENDING,
        'order_status' => Order::STATUS_PENDING,
        'customer_notes' => $validated['customer_notes'] ?? null,
      ]);

      // Create order items
      foreach ($cart->items as $cartItem) {
        OrderItem::create([
          'order_id' => $order->id,
          'suit_model_id' => $cartItem->suit_model_id,
          'fabric_id' => $cartItem->fabric_id,
          'suit_configuration_id' => $cartItem->suit_configuration_id,
          'options_data' => $cartItem->options_data,
          'quantity' => $cartItem->quantity,
          'unit_price' => $cartItem->unit_price,
          'total_price' => $cartItem->total_price,
        ]);
      }

      // Clear cart
      $cart->items()->delete();
      $cart->update([
        'subtotal' => 0,
        'shipping_fee' => 0,
        'discount' => 0,
        'total' => 0,
      ]);

      DB::commit();

      // Handle payment method
      switch ($validated['payment_method']) {
        case 'momo':
          return $this->initMoMoPayment($order);
        case 'vnpay':
          return $this->initVNPayPayment($order);
        case 'bank_transfer':
          return $this->showBankTransferInfo($order);
        case 'cod':
        default:
          return redirect()->route('checkout.success', $order->order_number);
      }
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại: ' . $e->getMessage());
    }
  }

  /**
   * Order success page
   */
  public function success($orderNumber)
  {
    $order = Order::where('order_number', $orderNumber)->firstOrFail();

    return view('checkout.success', compact('order'));
  }

  /**
   * Initialize MoMo payment
   */
  private function initMoMoPayment(Order $order)
  {
    // TODO: Implement MoMo API integration
    // For now, redirect to success with pending payment
    return redirect()->route('checkout.success', $order->order_number)
      ->with('info', 'Vui lòng hoàn tất thanh toán qua ví MoMo');
  }

  /**
   * Initialize VNPay payment
   */
  private function initVNPayPayment(Order $order)
  {
    // TODO: Implement VNPay API integration
    // For now, redirect to success with pending payment
    return redirect()->route('checkout.success', $order->order_number)
      ->with('info', 'Vui lòng hoàn tất thanh toán qua VNPay');
  }

  /**
   * Show bank transfer information
   */
  private function showBankTransferInfo(Order $order)
  {
    return redirect()->route('checkout.success', $order->order_number)
      ->with('bank_transfer', true);
  }

  /**
   * Payment callback from payment gateway
   */
  public function paymentCallback(Request $request, $gateway)
  {
    // TODO: Handle payment callbacks
    return redirect()->route('home');
  }

  /**
   * Get current cart
   */
  private function getCart()
  {
    $sessionId = session()->getId();
    $userId = auth()->id();

    return Cart::where(function ($query) use ($sessionId, $userId) {
      $query->where('session_id', $sessionId);
      if ($userId) {
        $query->orWhere('user_id', $userId);
      }
    })->first();
  }
}
