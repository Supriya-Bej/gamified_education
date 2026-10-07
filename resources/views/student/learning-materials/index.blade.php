@extends('student.layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-primary-subtle text-primary px-3 py-2">
                    <i class="bi bi-book-half me-1"></i>
                    LEARN
                </span>
            </div>

            <h2 class="fw-bold mb-1">
                Learning Materials
            </h2>

            <p class="text-muted mb-0">
                Explore topics, learn something new, and grow your knowledge.
            </p>
        </div>

        <div class="text-end">
            <div class="small text-muted">
                Welcome back
            </div>

            <div class="fw-semibold">
                {{ $student->name }}
            </div>
        </div>

    </div>

    

    {{-- Topic Section --}}
    <div class="mb-4">

        <h5 class="fw-bold mb-3">
            <i class="bi bi-grid-fill me-2 text-primary"></i>
            Explore Topics
        </h5>

        <div class="d-flex flex-wrap gap-2">

            {{-- All Topics --}}
            <a href="{{ route('student.learning-materials.index') }}"
                class="btn {{ !request('topic') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4">

                <i class="bi bi-stars me-1"></i>
                All Topics

            </a>


            {{-- Database Topics --}}
            @foreach($topics as $topic)

            <a href="{{ route(
                    'student.learning-materials.index',
                    ['topic' => $topic->id]
                ) }}"
                class="btn {{ request('topic') == $topic->id
                    ? 'btn-primary'
                    : 'btn-outline-secondary' }}
                    rounded-pill px-4">

                {{ $topic->name }}

            </a>

            @endforeach

        </div>

    </div>

    {{-- Materials --}}
    @if($materials->count() > 0)

    <div class="row g-4">

        @foreach($materials as $material)

        <div class="col-md-6 col-xl-4 material-card"
            data-topic="{{ strtolower($material->topic) }}">

            <div class="card border-0 shadow-sm h-100 overflow-hidden">

                {{-- Image --}}
                @if($material->image)

                <img src="{{ asset('storage/' . $material->image) }}"
                    alt="{{ $material->title }}"
                    class="w-100"
                    style="height:190px; object-fit:cover;">

                @else

                <div class="d-flex align-items-center justify-content-center bg-primary-subtle"
                    style="height:190px;">

                    <i class="bi bi-book-half text-primary"
                        style="font-size:55px;"></i>

                </div>

                @endif


                <div class="card-body p-4">

                    {{-- Topic + Difficulty --}}
                    <div class="d-flex flex-wrap gap-2 mb-3">

                        <span class="badge bg-primary-subtle text-primary">
                            {{ $material->topic }}
                        </span>

                        <span class="badge
                                    @if($material->difficulty === 'beginner')
                                        bg-success-subtle text-success
                                    @elseif($material->difficulty === 'intermediate')
                                        bg-warning-subtle text-warning
                                    @else
                                        bg-danger-subtle text-danger
                                    @endif">

                            {{ ucfirst($material->difficulty) }}

                        </span>

                    </div>


                    {{-- Title --}}
                    <h5 class="fw-bold mb-2">
                        {{ $material->title }}
                    </h5>


                    {{-- Description --}}
                    <p class="text-muted small mb-4">

                        {{ \Illuminate\Support\Str::limit(
                                    $material->description,
                                    110
                                ) }}

                    </p>


                    {{-- Read Button --}}
                    <a href="{{ route(
                                'student.learning-materials.show',
                                $material
                            ) }}"
                        class="btn btn-outline-primary w-100">

                        <i class="bi bi-book-open me-1"></i>
                        Start Learning

                    </a>

                </div>

            </div>

        </div>

        @endforeach

    </div>


    {{-- Pagination --}}
    <div class="mt-4">

        {{ $materials->links() }}

    </div>

    @else

    {{-- Empty State --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body text-center py-5">

            <div class="mb-3">
                <i class="bi bi-journal-x text-muted"
                    style="font-size:65px;"></i>
            </div>

            <h4 class="fw-bold">
                No Learning Materials Yet
            </h4>

            <p class="text-muted mb-0">
                New learning materials will appear here soon.
            </p>

        </div>

    </div>

    @endif

</div>


@endsection