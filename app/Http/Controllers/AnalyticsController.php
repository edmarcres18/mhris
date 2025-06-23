<?php

namespace App\Http\Controllers;

use App\Models\QuotationRequest;
use App\Models\MedicalProduct;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function dashboard()
    {
        // Get total counts
        $totalQuotations = QuotationRequest::count();
        $totalProducts = MedicalProduct::count();
        $totalCategories = Category::count();

        // Get all products with quotation counts for percentage calculations
        $products = MedicalProduct::withCount('quotationRequests')->get();

        // Get top requested products
        $topProducts = QuotationRequest::select('product_name', DB::raw('count(*) as total'))
            ->groupBy('product_name')
            ->orderBy('total', 'desc')
            ->take(3)
            ->get();

        // Get category-wise quotation counts
        $categoryAnalytics = Category::withCount(['products' => function($query) {
            $query->withCount('quotationRequests');
        }])
        ->take(3)
        ->get();

        // Calculate quotation frequency levels
        $quotationLevels = $this->calculateQuotationLevels();

        return view('analytics.dashboard', compact(
            'totalQuotations',
            'totalProducts',
            'totalCategories',
            'topProducts',
            'categoryAnalytics',
            'quotationLevels',
            'products' // Added products for percentage calculations
        ));
    }

    private function calculateQuotationLevels()
    {
        $products = MedicalProduct::withCount('quotationRequests')->get();
        
        // Calculate thresholds for high, mid, and low levels
        $maxQuotations = $products->max('quotation_requests_count');
        $highThreshold = $maxQuotations * 0.66;
        $lowThreshold = $maxQuotations * 0.33;

        $levels = [
            'high' => [],
            'mid' => [],
            'low' => []
        ];

        foreach ($products as $product) {
            $count = $product->quotation_requests_count;
            
            if ($count >= $highThreshold) {
                $levels['high'][] = [
                    'name' => $product->name,
                    'count' => $count
                ];
            } elseif ($count >= $lowThreshold) {
                $levels['mid'][] = [
                    'name' => $product->name,
                    'count' => $count
                ];
            } else {
                $levels['low'][] = [
                    'name' => $product->name,
                    'count' => $count
                ];
            }
        }

        return $levels;
    }

    public function getProductAnalytics($productId)
    {
        $product = MedicalProduct::with(['category', 'quotationRequests'])->findOrFail($productId);
        
        $analytics = [
            'total_quotations' => $product->quotationRequests()->count(),
            'daily_average' => $product->quotationRequests()
                ->select(DB::raw('COUNT(*) / DATEDIFF(NOW(), MIN(created_at)) as daily_avg'))
                ->first()->daily_avg,
            'monthly_trend' => $product->quotationRequests()
                ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('count(*) as count'))
                ->groupBy('month')
                ->orderBy('month')
                ->take(5)
                ->get(),
        ];

        return response()->json($analytics);
    }
} 
