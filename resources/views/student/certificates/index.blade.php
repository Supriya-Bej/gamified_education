@extends('student.layouts.app')

@section('title', 'My Certificates')

@section('content')
    <div class="container py-4">
        <div class="mb-4">
            <h1 class="h3">My Certificates</h1>
            <p class="text-secondary mb-0">After completing all three game stages for an interest, your certificate will appear here once an admin approves it.</p>
        </div>

        <div class="row g-3">
            @forelse($certificates as $certificate)
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <div class="text-success mb-2"><i class="bi bi-award-fill fs-3"></i></div>
                            <h2 class="h5">{{ $certificate->title }}</h2>
                            <p class="mb-2">{{ $certificate->task->title }}</p>
                            <div class="small text-secondary">
                                Issued {{ $certificate->issued_at->format('M d, Y') }}
                            </div>
                            <div class="small text-secondary">{{ $certificate->certificate_number }}</div>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <a class="btn btn-primary" href="{{ route('student.certificates.show', $certificate) }}">
                                View certificate
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info mb-0">
                        No approved certificates yet. Complete all 25 games in each of an interest's three levels, then wait for admin approval.
                    </div>
                </div>
            @endforelse
        </div>

        @if($certificates->hasPages())
            <div class="mt-4">{{ $certificates->links() }}</div>
        @endif
    </div>
@endsection
