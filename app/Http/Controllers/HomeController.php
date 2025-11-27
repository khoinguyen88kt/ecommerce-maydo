<?php

namespace App\Http\Controllers;

use App\Models\Fabric;
use App\Models\FabricCategory;
use App\Models\SuitModel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
  /**
   * Display the home page
   */
  public function index()
  {
    // Featured suit models
    $featuredModels = SuitModel::where('is_active', true)
      ->where('is_featured', true)
      ->orderBy('sort_order')
      ->take(4)
      ->get();

    // Featured fabrics
    $featuredFabrics = Fabric::where('is_active', true)
      ->where('is_featured', true)
      ->orderBy('sort_order')
      ->take(8)
      ->get();

    // Fabric categories
    $categories = FabricCategory::where('is_active', true)
      ->orderBy('sort_order')
      ->get();

    return view('home', compact(
      'featuredModels',
      'featuredFabrics',
      'categories'
    ));
  }
}
