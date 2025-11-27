<?php

namespace App\Http\Controllers;

use App\Models\Fabric;
use App\Models\FabricCategory;
use App\Models\SuitModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
  /**
   * Generate main sitemap index
   */
  public function index(): Response
  {
    $sitemaps = [
      [
        'loc' => route('sitemap.pages'),
        'lastmod' => now()->toIso8601String(),
      ],
      [
        'loc' => route('sitemap.fabrics'),
        'lastmod' => Fabric::latest()->first()?->updated_at?->toIso8601String() ?? now()->toIso8601String(),
      ],
      [
        'loc' => route('sitemap.models'),
        'lastmod' => SuitModel::latest()->first()?->updated_at?->toIso8601String() ?? now()->toIso8601String(),
      ],
    ];

    $content = view('sitemaps.index', compact('sitemaps'))->render();

    return response($content)
      ->header('Content-Type', 'application/xml');
  }

  /**
   * Generate pages sitemap
   */
  public function pages(): Response
  {
    $pages = [
      [
        'loc' => route('home'),
        'lastmod' => now()->toIso8601String(),
        'changefreq' => 'daily',
        'priority' => '1.0',
      ],
      [
        'loc' => route('configurator.index'),
        'lastmod' => now()->toIso8601String(),
        'changefreq' => 'daily',
        'priority' => '0.9',
      ],
      [
        'loc' => route('fabrics.index'),
        'lastmod' => now()->toIso8601String(),
        'changefreq' => 'weekly',
        'priority' => '0.8',
      ],
      [
        'loc' => route('about'),
        'lastmod' => now()->toIso8601String(),
        'changefreq' => 'monthly',
        'priority' => '0.6',
      ],
      [
        'loc' => route('contact'),
        'lastmod' => now()->toIso8601String(),
        'changefreq' => 'monthly',
        'priority' => '0.6',
      ],
      [
        'loc' => route('warranty'),
        'lastmod' => now()->toIso8601String(),
        'changefreq' => 'monthly',
        'priority' => '0.5',
      ],
      [
        'loc' => route('size-guide'),
        'lastmod' => now()->toIso8601String(),
        'changefreq' => 'monthly',
        'priority' => '0.5',
      ],
      [
        'loc' => route('terms'),
        'lastmod' => now()->toIso8601String(),
        'changefreq' => 'yearly',
        'priority' => '0.3',
      ],
      [
        'loc' => route('privacy'),
        'lastmod' => now()->toIso8601String(),
        'changefreq' => 'yearly',
        'priority' => '0.3',
      ],
    ];

    // Add fabric categories
    $categories = FabricCategory::where('is_active', true)->get();
    foreach ($categories as $category) {
      $pages[] = [
        'loc' => route('fabrics.category', $category->slug),
        'lastmod' => $category->updated_at->toIso8601String(),
        'changefreq' => 'weekly',
        'priority' => '0.7',
      ];
    }

    $content = view('sitemaps.urls', compact('pages'))->render();

    return response($content)
      ->header('Content-Type', 'application/xml');
  }

  /**
   * Generate fabrics sitemap
   */
  public function fabrics(): Response
  {
    $fabrics = Fabric::where('is_active', true)
      ->orderBy('updated_at', 'desc')
      ->get();

    $pages = [];
    foreach ($fabrics as $fabric) {
      $pages[] = [
        'loc' => route('configurator.index') . '?fabric=' . $fabric->id,
        'lastmod' => $fabric->updated_at->toIso8601String(),
        'changefreq' => 'weekly',
        'priority' => '0.6',
      ];
    }

    $content = view('sitemaps.urls', compact('pages'))->render();

    return response($content)
      ->header('Content-Type', 'application/xml');
  }

  /**
   * Generate suit models sitemap
   */
  public function models(): Response
  {
    $models = SuitModel::where('is_active', true)
      ->orderBy('updated_at', 'desc')
      ->get();

    $pages = [];
    foreach ($models as $model) {
      $pages[] = [
        'loc' => route('configurator.index') . '?model=' . $model->id,
        'lastmod' => $model->updated_at->toIso8601String(),
        'changefreq' => 'weekly',
        'priority' => '0.6',
      ];
    }

    $content = view('sitemaps.urls', compact('pages'))->render();

    return response($content)
      ->header('Content-Type', 'application/xml');
  }
}
