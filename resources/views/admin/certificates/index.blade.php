@extends('admin.layouts.app')

@section('title', 'Certificate Management')
@section('page-heading', 'Certificate Management')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1">Certificate Management</h1>
            <p class="text-secondary mb-0">Approve interest-course certificates after verifying the student completed all three game stages. You can also issue or revoke perfect-quest certificates.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h2 class="h5 mb-0">Interest Course Certificates</h2>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>Interest</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courseCertificates as $certificate)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $certificate->user->name }}</div>
                                <div class="small text-secondary">{{ $certificate->user->email }}</div>
                            </td>
                            <td>{{ ucfirst($certificate->interest) }}</td>
                            <td>{{ $certificate->title }}</td>
                            <td>
                                @if($certificate->status === 'pending_review')
                                    <span class="badge text-bg-warning">Awaiting approval</span>
                                @elseif($certificate->status === 'issued')
                                    <span class="badge text-bg-primary">Issued</span>
                                    <div class="small text-secondary">
                                        {{ $certificate->issued_at?->format('M d, Y') }}
                                    </div>
                                @else
                                    <span class="badge text-bg-danger">
                                        {{ $certificate->status === 'revoked' ? 'Revoked' : 'Not issued' }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if(in_array($certificate->status, ['pending_review', 'revoked'], true))
                                    <form method="POST" action="{{ route('admin.certificates.approve', $certificate) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            {{ $certificate->status === 'revoked' ? 'Re-issue certificate' : 'Approve & issue' }}
                                        </button>
                                    </form>
                                @elseif($certificate->status === 'issued')
                                    <form method="POST" action="{{ route('admin.certificates.revoke', $certificate) }}"
                                        onsubmit="return confirm('Revoke this certificate? The student will no longer be able to view it.')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Revoke</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-secondary py-4">
                                No interest-course certificates are awaiting approval.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>Quest</th>
                        <th>Score</th>
                        <th>Attempt date</th>
                        <th>Certificate</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attempts as $attempt)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $attempt->user->name }}</div>
                                <div class="small text-secondary">{{ $attempt->user->email }}</div>
                            </td>
                            <td>{{ $attempt->task->title }}</td>
                            <td><span class="badge text-bg-success">{{ $attempt->score }}%</span></td>
                            <td>{{ $attempt->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($attempt->certificate?->status === 'issued')
                                    <span class="badge text-bg-primary">Issued</span>
                                    <div class="small text-secondary">{{ $attempt->certificate->certificate_number }}</div>
                                @elseif($attempt->certificate?->status === 'revoked')
                                    <span class="badge text-bg-danger">Revoked</span>
                                @else
                                    <span class="badge text-bg-secondary">Not issued</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($attempt->certificate?->status === 'issued')
                                    <form method="POST" action="{{ route('admin.certificates.revoke', $attempt->certificate) }}"
                                        onsubmit="return confirm('Revoke this certificate? The student will no longer be able to view it.')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Revoke</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.certificates.issue', $attempt) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            {{ $attempt->certificate?->status === 'revoked' ? 'Re-issue' : 'Issue certificate' }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-5">
                                No students have achieved a 100% quest score yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($attempts->hasPages())
            <div class="card-footer bg-white">{{ $attempts->links() }}</div>
        @endif
    </div>
@endsection
