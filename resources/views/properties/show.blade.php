@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h3 class="card-title mb-0 font-weight-bold">{{ $property->property_name }}</h3>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <div id="propertyCarousel" class="carousel slide" data-ride="carousel">
                                <ol class="carousel-indicators">
                                    <li data-target="#propertyCarousel" data-slide-to="0" class="active"></li>
                                    <li data-target="#propertyCarousel" data-slide-to="1"></li>
                                    <li data-target="#propertyCarousel" data-slide-to="2"></li>
                                    <li data-target="#propertyCarousel" data-slide-to="3"></li>
                                    @if($property->fourth_image)
                                        <li data-target="#propertyCarousel" data-slide-to="4"></li>
                                    @endif
                                    @if($property->fifth_image)
                                        <li data-target="#propertyCarousel" data-slide-to="5"></li>
                                    @endif
                                </ol>
                                <div class="carousel-inner rounded">
                                    <div class="carousel-item active">
                                        <img src="{{ asset('storage/' . $property->main_image) }}" class="d-block w-100" alt="Main Image">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="{{ asset('storage/' . $property->first_image) }}" class="d-block w-100" alt="First Image">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="{{ asset('storage/' . $property->second_image) }}" class="d-block w-100" alt="Second Image">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="{{ asset('storage/' . $property->third_image) }}" class="d-block w-100" alt="Third Image">
                                    </div>
                                    @if($property->fourth_image)
                                    <div class="carousel-item">
                                        <img src="{{ asset('storage/' . $property->fourth_image) }}" class="d-block w-100" alt="Fourth Image">
                                    </div>
                                    @endif
                                    @if($property->fifth_image)
                                    <div class="carousel-item">
                                        <img src="{{ asset('storage/' . $property->fifth_image) }}" class="d-block w-100" alt="Fifth Image">
                                    </div>
                                    @endif
                                </div>
                                <a class="carousel-control-prev" href="#propertyCarousel" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#propertyCarousel" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="property-details">
                                <div class="mb-4">
                                    <h5 class="text-muted mb-2">Location</h5>
                                    <p class="lead">{{ $property->location }}</p>
                                </div>
                                <div class="mb-4">
                                    <h5 class="text-muted mb-2">Contact Info</h5>
                                    <p class="lead">{{ $property->contact_info }}</p>
                                </div>
                                <div class="mb-4">
                                    <h5 class="text-muted mb-2">Email</h5>
                                    <p class="lead">{{ $property->email }}</p>
                                </div>
                                <div class="mb-4">
                                    <h5 class="text-muted mb-2">Description</h5>
                                    <p class="lead">{{ $property->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 d-flex flex-wrap justify-content-start">
                        <a href="{{ route('properties.edit', $property->id) }}" class="btn btn-primary btn-lg mr-2 mb-2">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <form action="{{ route('properties.destroy', $property->id) }}" method="POST" class="d-inline-block mr-2 mb-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-lg" onclick="return confirm('Are you sure you want to delete this property?')">
                                <i class="fas fa-trash-alt mr-1"></i> Delete
                            </button>
                        </form>
                        <a href="{{ route('properties.index') }}" class="btn btn-secondary btn-lg mb-2">
                            <i class="fas fa-arrow-left mr-1"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .card {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
    }
    .card-header {
        border-bottom: none;
    }
    .carousel-item img {
        object-fit: cover;
        height: 400px;
        width: 100%;
    }
    .carousel-indicators {
        bottom: -50px;
    }
    .carousel-indicators li {
        background-color: #777;
    }
    .carousel-indicators .active {
        background-color: #333;
    }
    .lead {
        font-size: 1.1rem;
        color: #333;
    }
    .property-details h5 {
        font-size: 1rem;
        font-weight: 600;
        color: #6c757d;
    }
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
    }
    @media (max-width: 767px) {
        .carousel-item img {
            height: 300px;
        }
        .property-details {
            margin-top: 2rem;
        }
    }
</style>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('.carousel').carousel({
            interval: 5000
        });
    });
</script>
@endsection
