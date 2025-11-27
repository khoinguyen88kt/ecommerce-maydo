<?php

namespace App\Http\Controllers;

use App\Models\Fabric;
use App\Models\FabricCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
  /**
   * About page
   */
  public function about()
  {
    return view('about');
  }

  /**
   * Contact page
   */
  public function contact()
  {
    return view('contact');
  }

  /**
   * Handle contact form submission
   */
  public function submitContact(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|max:255',
      'phone' => 'nullable|string|max:20',
      'subject' => 'required|string|max:255',
      'message' => 'required|string|max:2000',
    ]);

    // TODO: Send email or save to database
    // Mail::to('info@suitconfigurator.vn')->send(new ContactFormMail($validated));

    return back()->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.');
  }

  /**
   * Warranty policy page
   */
  public function warranty()
  {
    return view('warranty');
  }

  /**
   * Size guide page
   */
  public function sizeGuide()
  {
    return view('size-guide');
  }

  /**
   * Fabrics collection page
   */
  public function fabrics(Request $request)
  {
    $categories = FabricCategory::where('is_active', true)
      ->orderBy('sort_order')
      ->get();

    $query = Fabric::where('is_active', true);

    // Filter by category if specified
    if ($request->has('category')) {
      $category = FabricCategory::where('slug', $request->category)->first();
      if ($category) {
        $query->where('fabric_category_id', $category->id);
      }
    }

    $fabrics = $query->with('category')
      ->orderBy('sort_order')
      ->paginate(24);

    return view('fabrics.index', compact('categories', 'fabrics'));
  }

  /**
   * Single fabric category page
   */
  public function fabricCategory($slug)
  {
    $category = FabricCategory::where('slug', $slug)
      ->where('is_active', true)
      ->firstOrFail();

    $categories = FabricCategory::where('is_active', true)
      ->orderBy('sort_order')
      ->get();

    $fabrics = Fabric::where('fabric_category_id', $category->id)
      ->where('is_active', true)
      ->with('category')
      ->orderBy('sort_order')
      ->paginate(24);

    return view('fabrics.index', compact('category', 'categories', 'fabrics'));
  }

  /**
   * Terms of service page
   */
  public function terms()
  {
    return view('terms');
  }

  /**
   * Privacy policy page
   */
  public function privacy()
  {
    return view('privacy');
  }
}
