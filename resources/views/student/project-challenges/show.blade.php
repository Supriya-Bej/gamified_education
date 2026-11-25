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
                    <span class="badge text-bg-success">
                        <i class="bi bi-shield-check me-1"></i>
                        Automatic challenge checks: {{ ucfirst($projectChallenge->ai_verification_status ?? 'passed') }}
                    </span>
                    <span class="badge text-bg-warning">+{{ $projectChallenge->xp }} XP after admin approval</span>
                </div>
                <h1 class="h2">{{ $projectChallenge->title }}</h1>
                <p class="text-secondary">Difficulty: {{ ucfirst($projectChallenge->difficulty) }}</p>
                <div class="alert alert-info">
                    The generated challenge passed automatic checks for its interest match, required details, and configured limits.
                    This does not verify your activity or photo. An admin reviews your evidence after you submit it.
                </div>
                <h2 class="h5 mt-4">Project brief</h2>
                <p class="mb-4">{{ $projectChallenge->project_brief }}</p>

                <div class="row g-4">
                    <div class="col-md-6">
                        <h2 class="h5">Do these steps</h2>
                        <ul class="list-unstyled" id="objective-checklist">
                            @foreach ($projectChallenge->objectives as $objective)
                                <li class="mb-3">
                                    <label class="d-flex align-items-start gap-2">
                                        <input class="form-check-input mt-1 objective-check" type="checkbox">
                                        <span>{{ $objective }}</span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                        <div class="progress" role="progressbar" aria-label="Activity step progress" aria-valuemin="0" aria-valuemax="{{ count($projectChallenge->objectives) }}">
                            <div id="objective-progress" class="progress-bar" style="width: 0%"></div>
                        </div>
                        <small id="objective-progress-label" class="text-secondary">0 of {{ count($projectChallenge->objectives) }} steps done</small>
                    </div>
                    <div class="col-md-6">
                        <h2 class="h5">Show what you did</h2>
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
                <strong>Evidence submitted — admin review pending.</strong>
                Your photo or link is available to the reviewer below. AI did not judge the photo.
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
                        Take a photo of your result, or share a link. Add a short note about what you did and learned.
                        You can submit a photo, a link, or both.
                    </p>
                    <form method="POST" enctype="multipart/form-data" action="{{ route('student.project-challenges.submit', $projectChallenge) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="project_url" class="form-label">Project or evidence link (optional if you upload a photo)</label>
                            <input id="project_url" name="project_url" type="url" maxlength="2048"
                                value="{{ old('project_url', $latestSubmission?->project_url) }}"
                                class="form-control @error('project_url') is-invalid @enderror"
                                placeholder="https://...">
                            @error('project_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="evidence_image" class="form-label">Photo evidence (optional if you share a link)</label>
                            <input id="evidence_image" name="evidence_image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                class="form-control @error('evidence_image') is-invalid @enderror">
                            <div class="form-text">JPG, PNG, or WebP; up to 10 MB. A preview appears before you submit.</div>
                            @error('evidence_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <img id="evidence-preview" class="img-fluid rounded mt-3 d-none" alt="Preview of selected activity evidence" style="max-height: 360px;">
                        </div>
                        <div class="mb-3">
                            <label for="reflection" class="form-label">What did you do and learn?</label>
                            <textarea id="reflection" name="reflection" required minlength="30" maxlength="3000"
                                rows="5" class="form-control @error('reflection') is-invalid @enderror"
                                placeholder="Describe the activity, what you learned, and how it meets the objectives.">{{ old('reflection', $latestSubmission?->reflection) }}</textarea>
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
                    @if ($latestSubmission->project_url)
                        <p><a href="{{ $latestSubmission->project_url }}" target="_blank" rel="noopener noreferrer">{{ $latestSubmission->project_url }}</a></p>
                    @endif
                    @if ($latestSubmission->evidence_image)
                        <p><img src="{{ asset('storage/' . $latestSubmission->evidence_image) }}" alt="Uploaded project evidence" class="img-fluid rounded" style="max-height: 480px;"></p>
                    @endif
                    <p class="mb-0">{{ $latestSubmission->reflection }}</p>
                </div>
            </section>
        @endif
    </main>
    <script>
        const objectiveChecks = [...document.querySelectorAll('.objective-check')];
        const progress = document.getElementById('objective-progress');
        const progressLabel = document.getElementById('objective-progress-label');

        function updateObjectiveProgress() {
            const completed = objectiveChecks.filter((checkbox) => checkbox.checked).length;
            const percentage = objectiveChecks.length === 0 ? 0 : (completed / objectiveChecks.length) * 100;
            progress.style.width = `${percentage}%`;
            progress.setAttribute('aria-valuenow', completed);
            progressLabel.textContent = `${completed} of ${objectiveChecks.length} steps done`;
        }

        objectiveChecks.forEach((checkbox) => checkbox.addEventListener('change', updateObjectiveProgress));

        const imageInput = document.getElementById('evidence_image');
        const imagePreview = document.getElementById('evidence-preview');
        let previewUrl = null;
        imageInput?.addEventListener('change', () => {
            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
                previewUrl = null;
            }

            const file = imageInput.files?.[0];
            if (!file) {
                imagePreview.classList.add('d-none');
                imagePreview.removeAttribute('src');
                return;
            }

            previewUrl = URL.createObjectURL(file);
            imagePreview.src = previewUrl;
            imagePreview.classList.remove('d-none');
        });
    </script>
</body>
</html>
