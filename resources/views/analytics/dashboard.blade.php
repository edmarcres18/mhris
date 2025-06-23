@extends('layouts.medical-products')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="container-fluid" style="padding: 2rem; background-color: #f8f9fa;">
    <h1 style="font-size: 2.5rem; color: #2c3e50; margin-bottom: 2rem; font-weight: 600; border-bottom: 3px solid #3498db; padding-bottom: 1rem;">
        Analytics Dashboard
        <span style="font-size: 1rem; color: #7f8c8d; margin-left: 1rem;">Real-time Product Insights</span>
    </h1>
    
    <!-- Summary Cards -->
    <div class="row" style="margin-bottom: 2rem;">
        <div class="col-xl-4 col-md-6 mb-4">
            <div style="background: linear-gradient(135deg, #3498db, #2980b9); border-radius: 15px; box-shadow: 0 4px 15px rgba(52, 152, 219, 0.2); transition: all 0.3s ease; padding: 1.5rem; color: white;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h4 style="font-size: 1.1rem; opacity: 0.9; margin-bottom: 0.5rem;">Total Quotations</h4>
                        <h2 style="font-size: 2.5rem; font-weight: 600; margin: 0;">{{ $totalQuotations }}</h2>
                    </div>
                    <div style="font-size: 3rem; opacity: 0.8;">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div style="background: linear-gradient(135deg, #2ecc71, #27ae60); border-radius: 15px; box-shadow: 0 4px 15px rgba(46, 204, 113, 0.2); transition: all 0.3s ease; padding: 1.5rem; color: white;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h4 style="font-size: 1.1rem; opacity: 0.9; margin-bottom: 0.5rem;">Total Products</h4>
                        <h2 style="font-size: 2.5rem; font-weight: 600; margin: 0;">{{ $totalProducts }}</h2>
                    </div>
                    <div style="font-size: 3rem; opacity: 0.8;">
                        <i class="fas fa-box-open"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div style="background: linear-gradient(135deg, #e74c3c, #c0392b); border-radius: 15px; box-shadow: 0 4px 15px rgba(231, 76, 60, 0.2); transition: all 0.3s ease; padding: 1.5rem; color: white;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h4 style="font-size: 1.1rem; opacity: 0.9; margin-bottom: 0.5rem;">Total Categories</h4>
                        <h2 style="font-size: 2.5rem; font-weight: 600; margin: 0;">{{ $totalCategories }}</h2>
                    </div>
                    <div style="font-size: 3rem; opacity: 0.8;">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Content -->
    <div class="row">
        <!-- Top Products -->
        <div class="col-xl-6 mb-4">
            <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden;">
                <div style="padding: 1.5rem; border-bottom: 1px solid #eee; background: #fafafa;">
                    <h5 style="margin: 0; color: #2c3e50; font-weight: 600;">
                        <i class="fas fa-chart-bar" style="color: #3498db; margin-right: 0.5rem;"></i>
                        Top Requested Products
                    </h5>
                </div>
                <div style="padding: 1.5rem;">
                    <div class="table-responsive">
                        <table style="width: 100%; border-collapse: separate; border-spacing: 0 0.5rem;">
                            <thead>
                                <tr style="background: #f8f9fa;">
                                    <th style="padding: 1rem; color: #7f8c8d; font-weight: 600; border-bottom: 2px solid #eee;">Product Name</th>
                                    <th style="padding: 1rem; color: #7f8c8d; font-weight: 600; border-bottom: 2px solid #eee;">Requests</th>
                                    <th style="padding: 1rem; color: #7f8c8d; font-weight: 600; border-bottom: 2px solid #eee;">Share</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $product)
                                @php
                                    $percentage = $totalQuotations > 0 ? ($product->total / $totalQuotations) * 100 : 0;
                                @endphp
                                <tr style="background: #fff; transition: all 0.3s ease;">
                                    <td style="padding: 1rem; border-bottom: 1px solid #eee;">
                                        <span style="font-weight: 500; color: #2c3e50;">{{ $product->product_name }}</span>
                                    </td>
                                    <td style="padding: 1rem; border-bottom: 1px solid #eee;">{{ $product->total }}</td>
                                    <td style="padding: 1rem; border-bottom: 1px solid #eee;">
                                        <div style="background: #f1f1f1; border-radius: 10px; height: 8px; width: 100%; overflow: hidden;">
                                            <div style="background: linear-gradient(90deg, #3498db, #2980b9); height: 100%; width: {{ $percentage }}%;"></div>
                                        </div>
                                        <span style="font-size: 0.9rem; color: #7f8c8d; margin-top: 0.5rem; display: inline-block;">
                                            {{ number_format($percentage, 1) }}%
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Analytics -->
        <div class="col-xl-6 mb-4">
            <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden;">
                <div style="padding: 1.5rem; border-bottom: 1px solid #eee; background: #fafafa;">
                    <h5 style="margin: 0; color: #2c3e50; font-weight: 600;">
                        <i class="fas fa-chart-pie" style="color: #e74c3c; margin-right: 0.5rem;"></i>
                        Category Performance
                    </h5>
                </div>
                <div style="padding: 1.5rem;">
                    <div class="table-responsive">
                        <table style="width: 100%; border-collapse: separate; border-spacing: 0 0.5rem;">
                            <thead>
                                <tr style="background: #f8f9fa;">
                                    <th style="padding: 1rem; color: #7f8c8d; font-weight: 600; border-bottom: 2px solid #eee;">Category</th>
                                    <th style="padding: 1rem; color: #7f8c8d; font-weight: 600; border-bottom: 2px solid #eee;">Products</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categoryAnalytics as $category)
                                @php
                                    $categoryQuotations = $category->products->sum(function($product) {
                                        return $product->quotation_requests_count ?? 0;
                                    });
                                    $sharePercentage = $totalQuotations > 0 ? ($categoryQuotations / $totalQuotations) * 100 : 0;
                                @endphp
                                <tr style="background: #fff; transition: all 0.3s ease;">
                                    <td style="padding: 1rem; border-bottom: 1px solid #eee;">
                                        <span style="font-weight: 500; color: #2c3e50;">{{ $category->name }}</span>
                                    </td>
                                    <td style="padding: 1rem; border-bottom: 1px solid #eee;">{{ $category->products_count }}</td>
                                    <td style="padding: 1rem; border-bottom: 1px solid #eee;">
                                        <div style="background: #f1f1f1; border-radius: 10px; height: 8px; width: 100%; overflow: hidden;">
                                            <div style="background: linear-gradient(90deg, #e74c3c, #c0392b); height: 100%; width: {{ $sharePercentage }}%;"></div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Demand Levels -->
    <div class="row">
        <div class="col-12">
            <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 2rem;">
                <div style="padding: 1.5rem; border-bottom: 1px solid #eee; background: #fafafa;">
                    <h5 style="margin: 0; color: #2c3e50; font-weight: 600;">
                        <i class="fas fa-chart-line" style="color: #9b59b6; margin-right: 0.5rem;"></i>
                        Product Demand Analysis
                    </h5>
                </div>
                <div style="padding: 1.5rem;">
                    <div class="row">
                        <!-- High Demand -->
                        <div class="col-md-4 mb-4">
                            <div style="background: linear-gradient(135deg, #2ecc71, #27ae60); border-radius: 15px; padding: 1.5rem; color: white;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                    <h6 style="margin: 0; font-weight: 600;">High Demand</h6>
                                    <span style="background: rgba(255,255,255,0.2); padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem;">
                                        66-100%
                                    </span>
                                </div>
                                @foreach($quotationLevels['high'] as $product)
                                <div style="background: rgba(255,255,255,0.1); border-radius: 10px; padding: 1rem; margin-bottom: 0.5rem;">
                                    @php
                                        $maxQuotations = $products->max('quotation_requests_count');
                                        $percentage = $maxQuotations > 0 ? ($product['count'] / $maxQuotations) * 100 : 0;
                                    @endphp
                                    <div style="font-weight: 500; margin-bottom: 0.5rem;">{{ $product['name'] }}</div>
                                    <div style="font-size: 0.9rem; opacity: 0.9;">{{ $product['count'] }} requests</div>
                                    <div style="background: rgba(255,255,255,0.2); border-radius: 10px; height: 6px; margin-top: 0.5rem;">
                                        <div style="background: white; height: 100%; width: {{ $percentage }}%; border-radius: 10px;"></div>
                                    </div>
                                    <div style="font-size: 0.8rem; margin-top: 0.5rem;">{{ number_format($percentage, 1) }}% of max</div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Medium Demand -->
                        <div class="col-md-4 mb-4">
                            <div style="background: linear-gradient(135deg, #f1c40f, #f39c12); border-radius: 15px; padding: 1.5rem; color: white;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                    <h6 style="margin: 0; font-weight: 600;">Medium Demand</h6>
                                    <span style="background: rgba(255,255,255,0.2); padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem;">
                                        33-65%
                                    </span>
                                </div>
                                @foreach($quotationLevels['mid'] as $product)
                                <div style="background: rgba(255,255,255,0.1); border-radius: 10px; padding: 1rem; margin-bottom: 0.5rem;">
                                    @php
                                        $maxQuotations = $products->max('quotation_requests_count');
                                        $percentage = $maxQuotations > 0 ? ($product['count'] / $maxQuotations) * 100 : 0;
                                    @endphp
                                    <div style="font-weight: 500; margin-bottom: 0.5rem;">{{ $product['name'] }}</div>
                                    <div style="font-size: 0.9rem; opacity: 0.9;">{{ $product['count'] }} requests</div>
                                    <div style="background: rgba(255,255,255,0.2); border-radius: 10px; height: 6px; margin-top: 0.5rem;">
                                        <div style="background: white; height: 100%; width: {{ $percentage }}%; border-radius: 10px;"></div>
                                    </div>
                                    <div style="font-size: 0.8rem; margin-top: 0.5rem;">{{ number_format($percentage, 1) }}% of max</div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Low Demand -->
                        <div class="col-md-4 mb-4">
                            <div style="background: linear-gradient(135deg, #e74c3c, #c0392b); border-radius: 15px; padding: 1.5rem; color: white;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                    <h6 style="margin: 0; font-weight: 600;">Low Demand</h6>
                                    <span style="background: rgba(255,255,255,0.2); padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem;">
                                        0-32%
                                    </span>
                                </div>
                                @foreach($quotationLevels['low'] as $product)
                                <div style="background: rgba(255,255,255,0.1); border-radius: 10px; padding: 1rem; margin-bottom: 0.5rem;">
                                    @php
                                        $maxQuotations = $products->max('quotation_requests_count');
                                        $percentage = $maxQuotations > 0 ? ($product['count'] / $maxQuotations) * 100 : 0;
                                    @endphp
                                    <div style="font-weight: 500; margin-bottom: 0.5rem;">{{ $product['name'] }}</div>
                                    <div style="font-size: 0.9rem; opacity: 0.9;">{{ $product['count'] }} requests</div>
                                    <div style="background: rgba(255,255,255,0.2); border-radius: 10px; height: 6px; margin-top: 0.5rem;">
                                        <div style="background: white; height: 100%; width: {{ $percentage }}%; border-radius: 10px;"></div>
                                    </div>
                                    <div style="font-size: 0.8rem; margin-top: 0.5rem;">{{ number_format($percentage, 1) }}% of max</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover effects to cards
        const cards = document.querySelectorAll('[style*="box-shadow"]');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.1)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 4px 15px rgba(0,0,0,0.1)';
            });
        });
    });
</script>
@endpush
@endsection 
