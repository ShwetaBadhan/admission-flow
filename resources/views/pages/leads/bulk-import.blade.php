@extends('layout.master')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4">
            <div>
                <h4 class="mb-1">Bulk Import Leads</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('leads.index') }}">Leads</a></li>
                        <li class="breadcrumb-item active">Bulk Import</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('leads.index') }}" class="btn btn-outline-light">
                <i class="ti ti-arrow-left me-1"></i> Back to Leads
            </a>
        </div>

        <!-- Import Card -->
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <!-- Sample Download -->
                <div class="alert alert-info d-flex align-items-center gap-2 mb-4">
                    <i class="ti ti-file-download fs-5"></i>
                    <div>
                        <strong>Need a template?</strong> 
                        <a href="{{ route('leads.sample.csv') }}" class="ms-2 text-decoration-underline">
                            Download Sample CSV File
                        </a>
                    </div>
                </div>

                <!-- Upload Form -->
                <form action="{{ route('leads.bulk.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-4">
                                <label class="form-label fw-medium">Select CSV File <span class="text-danger">*</span></label>
                                <input type="file" name="csv_file" class="form-control @error('csv_file') is-invalid @enderror" 
                                       accept=".csv,.txt" required>
                                <small class="text-muted">Maximum file size: 10MB. Only CSV format supported.</small>
                                @error('csv_file')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card bg-light border-0 mb-4">
                                <div class="card-body">
                                    <h6 class="mb-3"><i class="ti ti-info-circle me-1"></i> CSV Format Guidelines</h6>
                                    <ul class="mb-0 small">
                                        <li>First row must contain headers exactly as in sample file</li>
                                        <li>Required fields: <code>full_name*</code>, <code>mobile*</code></li>
                                        <li>Use names for relationships (state_name, city_name, etc.) - they will be auto-matched</li>
                                        <li>Leads with existing mobile/email will be updated, new ones will be created</li>
                                        <li>Empty optional fields will be left blank</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2">
                        <a href="{{ route('leads.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-upload me-1"></i> Import Leads
                        </button>
                    </div>
                </form>

                <!-- Error Summary (if any) -->
                @if(session('import_errors'))
                <div class="mt-4">
                    <h6 class="text-danger mb-2">⚠️ Import Errors ({{ count(session('import_errors')) }} rows failed)</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Row</th>
                                    <th>Data Preview</th>
                                    <th>Error</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(session('import_errors') as $error)
                                <tr>
                                    <td>{{ $error['row'] }}</td>
                                    <td><code>{{ implode(' | ', $error['data']) }}</code></td>
                                    <td class="text-danger small">{{ $error['error'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection