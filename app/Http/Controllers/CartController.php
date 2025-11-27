<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Fabric;
use App\Models\SuitConfiguration;
use App\Models\SuitModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
  /**
   * Get or create cart for current session/user
   */
  private function getCart()
  {
    $sessionId = session()->getId();
    $userId = auth()->id();

    $cart = Cart::where(function ($query) use ($sessionId, $userId) {
      $query->where('session_id', $sessionId);
      if ($userId) {
        $query->orWhere('user_id', $userId);
      }
    })->first();

    if (!$cart) {
      $cart = Cart::create([
        'session_id' => $sessionId,
        'user_id' => $userId,
      ]);
    } elseif ($userId && !$cart->user_id) {
      // Associate cart with logged in user
      $cart->update(['user_id' => $userId]);
    }

    return $cart;
  }

  /**
   * Display cart page
   */
  public function index()
  {
    $cart = $this->getCart();
    $items = $cart->items()->with(['suitModel', 'fabric', 'configuration'])->get();

    return view('cart.index', compact('cart', 'items'));
  }

  /**
   * Add item to cart (API)
   */
  public function add(Request $request)
  {
    $validated = $request->validate([
      'model_id' => 'required|exists:suit_models,id',
      'fabric_id' => 'required|exists:fabrics,id',
      'lining_id' => 'nullable|exists:fabrics,id',
      'options' => 'array',
      'price' => 'required|numeric|min:0',
      'quantity' => 'integer|min:1',
    ]);

    $cart = $this->getCart();

    // Create configuration first
    $shareCode = Str::random(8);
    while (SuitConfiguration::where('share_code', $shareCode)->exists()) {
      $shareCode = Str::random(8);
    }

    $configuration = SuitConfiguration::create([
      'user_id' => auth()->id(),
      'suit_model_id' => $validated['model_id'],
      'fabric_id' => $validated['fabric_id'],
      'lining_fabric_id' => $validated['lining_id'] ?? null,
      'options_data' => $validated['options'] ?? [],
      'total_price' => $validated['price'],
      'share_code' => $shareCode,
    ]);

    // Add to cart
    $cartItem = CartItem::create([
      'cart_id' => $cart->id,
      'suit_model_id' => $validated['model_id'],
      'fabric_id' => $validated['fabric_id'],
      'suit_configuration_id' => $configuration->id,
      'options_data' => $validated['options'] ?? [],
      'quantity' => $validated['quantity'] ?? 1,
      'unit_price' => $validated['price'],
      'total_price' => $validated['price'] * ($validated['quantity'] ?? 1),
    ]);

    // Update cart totals
    $this->updateCartTotals($cart);

    return response()->json([
      'success' => true,
      'message' => 'Đã thêm vào giỏ hàng',
      'cart_count' => $cart->items()->sum('quantity'),
      'item_id' => $cartItem->id,
    ]);
  }

  /**
   * Update item quantity (API)
   */
  public function update(Request $request, CartItem $item)
  {
    $validated = $request->validate([
      'quantity' => 'required|integer|min:1|max:10',
    ]);

    $item->update([
      'quantity' => $validated['quantity'],
      'total_price' => $item->unit_price * $validated['quantity'],
    ]);

    $this->updateCartTotals($item->cart);

    return response()->json([
      'success' => true,
      'message' => 'Đã cập nhật số lượng',
      'cart_count' => $item->cart->items()->sum('quantity'),
      'item_total' => $item->fresh()->total_price,
      'cart_total' => $item->cart->fresh()->total,
    ]);
  }

  /**
   * Remove item from cart (API)
   */
  public function remove(CartItem $item)
  {
    $cart = $item->cart;
    $item->delete();

    $this->updateCartTotals($cart);

    return response()->json([
      'success' => true,
      'message' => 'Đã xóa khỏi giỏ hàng',
      'cart_count' => $cart->items()->sum('quantity'),
      'cart_total' => $cart->fresh()->total,
    ]);
  }

  /**
   * Apply discount code
   */
  public function applyDiscount(Request $request)
  {
    $validated = $request->validate([
      'code' => 'required|string|max:50',
    ]);

    $cart = $this->getCart();

    // TODO: Implement discount code logic
    // For now, return error
    return response()->json([
      'success' => false,
      'message' => 'Mã giảm giá không hợp lệ',
    ], 400);
  }

  /**
   * Get cart count for header (API)
   */
  public function count()
  {
    $cart = $this->getCart();

    return response()->json([
      'count' => $cart->items()->sum('quantity'),
    ]);
  }

  /**
   * Update cart totals
   */
  private function updateCartTotals(Cart $cart)
  {
    $subtotal = $cart->items()->sum('total_price');
    $shipping = $subtotal >= 5000000 ? 0 : 50000; // Free shipping over 5M
    $discount = $cart->discount ?? 0;
    $total = $subtotal + $shipping - $discount;

    $cart->update([
      'subtotal' => $subtotal,
      'shipping_fee' => $shipping,
      'discount' => $discount,
      'total' => max(0, $total),
    ]);
  }
}
