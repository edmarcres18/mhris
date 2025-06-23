<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Jobs - MHR</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0056b3;
            --secondary-color: #6c757d;
            --text-color: #333;
            --light-bg: #f8f9fa;
            --white: #ffffff;
            --gray: #6c757d;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--light-bg);
        }

        .navbar {
            background-color: var(--white);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link {
            color: var(--text-color);
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--primary-color);
        }

        .hero {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 6rem 0;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: 0;
            right: 0;
            height: 100px;
            background: var(--light-bg);
            transform: skewY(-3deg);
        }

        .hero h1 {
            font-weight: 700;
            margin-bottom: 1rem;
            font-size: 3rem;
        }

        .hero p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        .saved-job-item {
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .saved-job-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .saved-job-item h2 {
            color: var(--primary-color);
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .saved-job-item-content {
            padding: 2rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #004494;
            border-color: #004494;
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: var(--white);
            transform: translateY(-2px);
        }

        .footer {
            background-color: var(--white);
            color: var(--gray);
            padding: 3rem 0;
            margin-top: 4rem;
        }

        @media (max-width: 768px) {
            .navbar-nav {
                background-color: var(--white);
                padding: 1rem;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }

            .hero {
                padding: 4rem 0;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .saved-job-item {
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>
<body>
    @include('preloader')
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('vendor/adminlte/dist/img/LOGO4.png') }}" alt="MHR Logo" height="30" class="d-inline-block align-top me-2">
                MHRPCI
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('careers*') ? 'active' : '' }}" href="{{ url('/careers') }}">Careers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('saved-jobs') ? 'active' : '' }}" href="{{ url('/saved-jobs') }}">
                            Saved Jobs
                            <span class="badge bg-primary saved-jobs-count">{{ $savedJobs->count() }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero">
        <div class="container text-center">
            <h1>Your Saved Jobs</h1>
            <p class="lead">Manage and review the positions you're interested in</p>
        </div>
    </div>

    <div class="container">
        @if($savedJobs->count() > 0)
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($savedJobs as $job)
                    <div class="col">
                        <div class="saved-job-item h-100 d-flex flex-column">
                            <div class="saved-job-item-content flex-grow-1">
                                <h2>{{ $job->position }}</h2>
                                <p>{{ Str::limit($job->description, 150) }}</p>
                                <div class="mt-3">
                                    <a href="{{ route('careers.show', $job->id) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-info-circle me-1"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info text-center">
                <p class="mb-0">You haven't saved any jobs yet. <a href="{{ url('/careers') }}">Browse available positions</a>.</p>
            </div>
        @endif
    </div>

    <footer class="footer">
        <div class="container text-center">
            <p>&copy; {{ date('Y') }} MHR. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ... (keep the existing JavaScript)
    </script>
</body>
</html>
