@extends('layout.master')

@section('content')
<div class="page-wrapper">
    <div class="content pb-0">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4">
            <div>
                <h4 class="mb-1">Payment Details #{{ $payment->id }}</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('commission-payments') }}">Payments</a></li>
                        <li class="breadcrumb-item active">#{{ $payment->id }}</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('commission-payments') }}" class="btn btn-light">
                <i class="ti ti-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="row">
            <!-- Payment Info -->
            <div class="col-lg-8">
                <div class="card border-0 rounded-0 mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Payment Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Status</label>
                                <p class="mb-0">
                                    <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Amount</label>
                                <p class="mb-0 fs-5 fw-bold">{{ number_format($payment->calculated_amount, 2) }} {{ $payment->currency }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Commission Type</label>
                                <p class="mb-0">{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }} ({{ $payment->commission_value }}{{ $payment->payment_type === 'percentage' ? '%' : '' }})</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Created</label>
                                <p class="mb-0">{{ $payment->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                            @if($payment->payment_reference)
                            <div class="col-md-6">
                                <label class="text-muted small">Payment Reference</label>
                                <p class="mb-0">{{ $payment->payment_reference }}</p>
                            </div>
                            @endif
                            @if($payment->payment_date)
                            <div class="col-md-6">
                                <label class="text-muted small">Paid Date</label>
                                <p class="mb-0">{{ $payment->payment_date->format('d M Y') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Info -->
            <div class="col-lg-4">
                <!-- Consultant -->
                <div class="card border-0 rounded-0 mb-3">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Consultant</h6>
                        <p class="mb-1 fw-medium">{{ $payment->consultant->name ?? 'N/A' }}</p>
                        <p class="mb-0 text-muted small">{{ $payment->consultant->email ?? '' }}</p>
                    </div>
                </div>

                <!-- Lead -->
                <div class="card border-0 rounded-0 mb-3">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Lead</h6>
                        <p class="mb-1 fw-medium">{{ $payment->admissionRequest->lead->full_name ?? 'N/A' }}</p>
                        <p class="mb-0 text-muted small">{{ $payment->admissionRequest->lead->mobile ?? '' }}</p>
                    </div>
                </div>

                <!-- Admission -->
                <div class="card border-0 rounded-0">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Admission Request</h6>
                        <p class="mb-1">
                            <strong>Course:</strong> {{ $payment->admissionRequest->course->name ?? $payment->admissionRequest->course_name ?? 'N/A' }}
                        </p>
                        <p class="mb-1">
                            <strong>College:</strong> {{ $payment->admissionRequest->college->name ?? 'N/A' }}
                        </p>
                        <p class="mb-0">
                            <strong>Status:</strong> 
                            <span class="badge bg-{{ $payment->admissionRequest->status === 'accepted' ? 'success' : 'secondary' }}">
                                {{ ucfirst($payment->admissionRequest->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($payment->notes)
        <div class="card border-0 rounded-0 mt-4">
            <div class="card-body">
                <h6 class="card-title mb-3">Notes</h6>
                <p class="mb-0">{{ $payment->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection