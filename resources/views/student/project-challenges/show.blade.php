<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $projectChallenge->title }} | Project Challenge</title>
    <link rel="stylesheet" href="{{ asset('Asset/Bootstrap-5/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <main class="container py-5" style="max-width: 960px;">
        <a href="{{ route('student.dashboard') }}#project-challenges" class="btn btn-outline-secondary mb-4">
            <i class="bi bi-arrow-left me-1"></i> Back to dashboard
        </a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <section class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
                    <span class="badge text-bg-primary">{{ $projectChallenge->interest }} project</span>
                    <span class="badge text-bg-warning">+{{ $projectChallenge->xp }} XP after admin approval</span>
                </div>
                <h1 class="h2">{{ $projectChallenge->title }}</h1>
                <p class="text-secondary">Difficulty: {{ ucfirst($projectChallenge->difficulty) }}</p>
                <h2 class="h5 mt-4">Project brief</h2>
                <p class="mb-4">{{ $projectChallenge->project_brief }}</p>

                <div class="row g-4">
                    <div class="col-md-6">
                        <h2 class="h5">Objectives</h2>
                        <ul>@foreach ($projectChallenge->objectives as $objective)<li class="mb-2">{{ $objective }}</li>@endforeach</ul>
                    </div>
                    <div class="col-md-6">
                        <h2 class="h5">Deliverables</h2>
                        <ul>@foreach ($projectChallenge->deliverables as $deliverable)<li class="mb-2">{{ $deliverable }}</li>@endforeach</ul>
                    </div>
                </div>
            </div>
        </section>

        @php($latestSubmission = $projectChallenge->submissions->first())
        @if ($latestSubmission?->status === 'revision_requested')
            <div class="alert alert-warning">
                <strong>Admin requested changes:</strong> {{ $latestSubmission->review_note }}
            </div>
        @elseif ($latestSubmission?->status === 'submitted')
            <div class="alert alert-info">
                Your latest submission is being reviewed. You can see its link below.
            </div>
        @elseif ($latestSubmission?->status === 'accepted')
            <div class="alert alert-success">
                Your project has been accepted. {{ $projectChallenge->xp }} XP has been awarded.
            </div>
        @endif

        @if (! $latestSubmission || $latestSubmission->status === 'revision_requested')
            <section class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h4">Submit your project</h2>
                    <p class="text-secondary">
                        Share a public GitHub repository, live demo, or other link to your finished work.
                        Tell the reviewer what you built and how it meets the objectives.
                    </p>
                    <form method="POST" action="{{ route('student.project-challenges.submit', $projectChallenge) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="project_url" class="form-label">Project link</label>
                            <input id="project_url" name="project_url" type="url" required maxlength="2048"
                                value="{{ old('project_url', $latestSubmission?->project_url) }}"
                                class="form-control @error('project_url') is-invalid @enderror"
                                placeholder="https://github.com/your-name/your-project">
                            @error('project_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="reflection" class="form-label">What did you build?</label>
                            <textarea id="reflection" name="reflection" required minlength="30" maxlength="3000"
                                rows="5" class="form-control @error('reflection') is-invalid @enderror"
                                placeholder="Describe the solution, your choices, and how it meets the project objectives.">{{ old('reflection', $latestSubmission?->reflection) }}</textarea>
                            @error('reflection')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button class="btn btn-primary" type="submit">Submit for review</button>
                    </form>
                </div>
            </section>
        @elseif ($latestSubmission)
            <section class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h5">Your latest submission</h2>
                    <p><a href="{{ $latestSubmission->project_url }}" target="_blank" rel="noopener noreferrer">{{ $latestSubmission->project_url }}</a></p>
                    <p class="mb-0">{{ $latestSubmission->reflection }}</p>
                </div>
            </section>
        @endif
    </main>
</body>
</html>
