@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Accountability Details</h1>
        </div>

        <div class="card-body">
            <div class="section">
                <h2 class="section-title">Employee Information</h2>
                <div class="info-block">
                    <div class="info-item">
                        <span class="info-label">Name:</span>
                        <span class="info-value">{{ $accountability->employee->last_name }} {{ $accountability->employee->first_name }}, {{ $accountability->employee->middle_name ?? ' ' }} {{ $accountability->employee->suffix ?? ' ' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Employee ID:</span>
                        <span class="info-value">{{ $accountability->employee->company_id }}</span>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2 class="section-title">IT Inventories</h2>
                <div class="info-block">
                    @forelse($accountability->itInventories as $inventory)
                        <div class="info-item">
                            <span class="info-label">{{ $inventory->name }}:</span>
                            <span class="info-value">{{ $inventory->description }}</span>
                        </div>
                    @empty
                        <p class="info-value">No IT inventories assigned.</p>
                    @endforelse
                </div>
            </div>

            @if(count($documents) > 0)
            <div class="section">
                <h2 class="section-title">Documents</h2>
                <div class="info-block">
                    <ul class="document-list">
                        @foreach($documents as $document)
                            <li class="document-item">
                                <a href="{{ Storage::url($document) }}" target="_blank" class="document-link">
                                    <i class="fas fa-file-alt document-icon"></i>
                                    {{ basename($document) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if($accountability->notes)
            <div class="section">
                <h2 class="section-title">Notes</h2>
                <div class="info-block">
                    <p class="info-value">{{ $accountability->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="card-footer">
            <a href="{{ route('accountabilities.edit', $accountability) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('accountabilities.destroy', $accountability) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this accountability?')">
                    Delete
                </button>
            </form>
            <a href="{{ route('accountabilities.index') }}" class="btn btn-secondary">Back to List</a>
            <a href="{{ route('accountabilities.transfer', $accountability) }}" class="btn btn-warning">Transfer Items</a>
        </div>
    </div>
</div>

<style>
    .container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 0 1rem;
    }
    .card {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: box-shadow 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        padding: 1.5rem;
    }
    .card-title {
        color: #2c3e50;
        font-size: 28px;
        font-weight: 700;
        margin: 0;
        letter-spacing: 0.5px;
    }
    .card-body {
        padding: 2rem;
    }
    .section {
        margin-bottom: 2.5rem;
    }
    .section:last-child {
        margin-bottom: 0;
    }
    .section-title {
        color: #34495e;
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #eaeaea;
        position: relative;
    }
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 60px;
        height: 2px;
        background-color: #3498db;
    }
    .info-block {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 1.25rem;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    .info-item {
        margin-bottom: 1rem;
        display: flex;
        align-items: baseline;
    }
    .info-item:last-child {
        margin-bottom: 0;
    }
    .info-label {
        font-weight: 600;
        color: #2c3e50;
        width: 140px;
        flex-shrink: 0;
    }
    .info-value {
        color: #34495e;
        flex-grow: 1;
    }
    .document-list {
        list-style-type: none;
        padding-left: 0;
    }
    .document-item {
        margin-bottom: 0.75rem;
        transition: transform 0.2s ease;
    }
    .document-item:hover {
        transform: translateX(5px);
    }
    .document-link {
        color: #3498db;
        text-decoration: none;
        display: flex;
        align-items: center;
    }
    .document-link:hover {
        text-decoration: underline;
    }
    .document-icon {
        margin-right: 0.5rem;
        font-size: 1.1em;
    }
    .card-footer {
        background-color: #f8f9fa;
        border-top: 2px solid #e9ecef;
        padding: 1.5rem;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 1rem;
    }
    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    .btn-primary {
        background-color: #3498db;
        color: #ffffff;
    }
    .btn-primary:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
    }
    .btn-danger {
        background-color: #e74c3c;
        color: #ffffff;
    }
    .btn-danger:hover {
        background-color: #c0392b;
        transform: translateY(-2px);
    }
    .btn-secondary {
        background-color: #95a5a6;
        color: #ffffff;
    }
    .btn-secondary:hover {
        background-color: #7f8c8d;
        transform: translateY(-2px);
    }
</style>
@endsection
