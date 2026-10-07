@extends('student.layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Back Button --}}
    <div class="mb-4">

        <a href="{{ route('student.learning-materials.index') }}"
            class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Learning Materials

        </a>

    </div>


    {{-- Main Material --}}
    <div class="row g-4">

        {{-- Content --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm overflow-hidden">

                {{-- Image --}}
                @if($learningMaterial->image)

                <img src="{{ asset('storage/' . $learningMaterial->image) }}"
                    alt="{{ $learningMaterial->title }}"
                    class="w-100"
                    style="max-height:380px; object-fit:cover;">

                @else

                <div class="d-flex align-items-center justify-content-center bg-primary-subtle"
                    style="height:280px;">

                    <i class="bi bi-book-half text-primary"
                        style="font-size:80px;"></i>

                </div>

                @endif


                <div class="card-body p-4 p-lg-5">

                    {{-- Badges --}}
                    <div class="d-flex flex-wrap gap-2 mb-3">

                        <span class="badge bg-primary-subtle text-primary px-3 py-2">

                            <i class="bi bi-bookmark-fill me-1"></i>

                            {{ $learningMaterial->topic }}

                        </span>

                        <span class="badge
                            @if($learningMaterial->difficulty === 'beginner')
                                bg-success-subtle text-success
                            @elseif($learningMaterial->difficulty === 'intermediate')
                                bg-warning-subtle text-warning
                            @else
                                bg-danger-subtle text-danger
                            @endif
                            px-3 py-2">

                            {{ ucfirst($learningMaterial->difficulty) }}

                        </span>

                    </div>


                    {{-- Title --}}
                    <h1 class="fw-bold mb-3">

                        {{ $learningMaterial->title }}

                    </h1>


                    {{-- Description --}}
                    @if($learningMaterial->description)

                    <p class="lead text-muted mb-4">

                        {{ $learningMaterial->description }}

                    </p>

                    @endif


                    <hr class="my-4">


                    {{-- Learning Content --}}
                    <div class="learning-content">

                        {!! nl2br(e($learningMaterial->content)) !!}

                    </div>

                </div>

            </div>

        </div>

        {{-- Completion Section --}}
        <div class="card border-0 shadow-sm mt-4">

            <div class="card-body p-4">

                @if($progress->completed_at)

                {{-- Already Completed --}}
                <div class="text-center">

                    <div class="mb-3">
                        <i class="bi bi-check-circle-fill text-success"
                            style="font-size:55px;"></i>
                    </div>

                    <h4 class="fw-bold text-success">
                        Learning Completed!
                    </h4>

                    <p class="text-muted mb-0">
                        You completed this material on
                        {{ $progress->completed_at->format('d M Y, h:i A') }}.
                    </p>

                </div>

                @else

                {{-- Not Completed --}}
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                    <div>

                        <h5 class="fw-bold mb-1">
                            Finished reading?
                        </h5>

                        <p class="text-muted mb-0">
                            Mark this material as completed.
                        </p>

                    </div>


                    <form method="POST"
                        action="{{ route(
                          'student.learning-materials.complete',
                          $learningMaterial
                      ) }}">

                        @csrf

                        <button type="submit"
                            class="btn btn-success px-4">

                            <i class="bi bi-check2-circle me-1"></i>
                            Mark as Completed

                        </button>

                    </form>

                </div>

                @endif

            </div>

        </div>




        {{-- Sidebar --}}
        <div class="col-lg-4">

            {{-- Topic Card --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3 mb-3">

                        <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center"
                            style="width:50px; height:50px;">

                            <i class="bi bi-book text-primary fs-4"></i>

                        </div>

                        <div>

                            <div class="small text-muted">
                                Topic
                            </div>

                            <div class="fw-bold">
                                {{ $learningMaterial->topic }}
                            </div>

                        </div>

                    </div>


                    @if($topic)

                    @if($topic->description)

                    <p class="text-muted small mb-0">

                        {{ $topic->description }}

                    </p>

                    @endif

                    @endif

                </div>

            </div>


            {{-- Difficulty Card --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body p-4">

                    <div class="small text-muted mb-1">
                        Difficulty Level
                    </div>

                    <h5 class="fw-bold mb-0">

                        @if($learningMaterial->difficulty === 'beginner')

                        <i class="bi bi-emoji-smile text-success me-2"></i>
                        Beginner

                        @elseif($learningMaterial->difficulty === 'intermediate')

                        <i class="bi bi-lightning-charge text-warning me-2"></i>
                        Intermediate

                        @else

                        <i class="bi bi-fire text-danger me-2"></i>
                        Advanced

                        @endif

                    </h5>

                </div>

            </div>


            {{-- Learning Journey --}}
            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">

                        <i class="bi bi-signpost-2-fill text-primary me-2"></i>

                        Your Learning Journey

                    </h5>

                    <div class="d-flex gap-3 mb-3">

                        <div class="text-primary">
                            <i class="bi bi-1-circle-fill fs-5"></i>
                        </div>

                        <div>
                            <div class="fw-semibold">
                                Learn
                            </div>

                            <div class="small text-muted">
                                Read and understand the material.
                            </div>
                        </div>

                    </div>


                    <div class="d-flex gap-3 mb-3">

                        <div class="text-success">
                            <i class="bi bi-2-circle-fill fs-5"></i>
                        </div>

                        <div>
                            <div class="fw-semibold">
                                Play
                            </div>

                            <div class="small text-muted">
                                Test your knowledge through activities.
                            </div>
                        </div>

                    </div>


                    <div class="d-flex gap-3">

                        <div class="text-warning">
                            <i class="bi bi-3-circle-fill fs-5"></i>
                        </div>

                        <div>
                            <div class="fw-semibold">
                                Earn
                            </div>

                            <div class="small text-muted">
                                Complete quests and earn XP.
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<style>
    .learning-content {
        font-size: 1.05rem;
        line-height: 1.9;
        color: #374151;
        white-space: normal;
    }

    .learning-content p {
        margin-bottom: 1rem;
    }
</style>

@endsection