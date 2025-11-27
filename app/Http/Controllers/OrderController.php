<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrderController extends Controller
{
  /**
   * Display list of user's orders
   */
  public function index()
  {
    $orders = Order::where('user_id', auth()->id())
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('orders.index', compact('orders'));
  }

  /**
   * Display single order details
   */
  public function show(Order $order)
  {
    // Ensure user can only view their own orders
    if ($order->user_id !== auth()->id()) {
      abort(403);
    }

    $order->load(['items.suitModel', 'items.fabric', 'items.configuration']);

    return view('orders.show', compact('order'));
  }

  /**
   * Display user profile
   */
  public function profile()
  {
    $user = auth()->user();
    $orders = Order::where('user_id', $user->id)
      ->orderBy('created_at', 'desc')
      ->take(5)
      ->get();

    return view('profile.edit', compact('user', 'orders'));
  }

  /**
   * Update user profile
   */
  public function updateProfile(Request $request)
  {
    $user = auth()->user();

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|max:255|unique:users,email,' . $user->id,
      'phone' => 'nullable|string|max:20',
      'address' => 'nullable|string|max:500',
      'current_password' => 'nullable|required_with:new_password',
      'new_password' => 'nullable|min:8|confirmed',
    ]);

    $user->name = $validated['name'];
    $user->email = $validated['email'];
    $user->phone = $validated['phone'] ?? null;
    $user->address = $validated['address'] ?? null;

    // Update password if provided
    if (!empty($validated['current_password']) && !empty($validated['new_password'])) {
      if (!Hash::check($validated['current_password'], $user->password)) {
        return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
      }
      $user->password = Hash::make($validated['new_password']);
    }

    $user->save();

    return back()->with('success', 'Thông tin tài khoản đã được cập nhật');
  }
}
