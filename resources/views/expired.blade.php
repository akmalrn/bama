<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Expired</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background: #f8f9fb;
        }

        .expired-card {
            max-width: 480px;
            margin: auto;
            border-radius: 12px;
        }

        .expired-icon {
            font-size: 52px;
        }

        .btn-back {
            padding: 10px 24px;
            border-radius: 8px;
        }
    </style>
</head>

<body>

    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-sm expired-card">
            <div class="card-body text-center p-5">

                <div class="mb-4">
                    <i class="fas fa-clock text-warning expired-icon"></i>
                </div>

                <h4 class="mb-3 fw-semibold">
                    Halaman Sudah Kadaluarsa
                </h4>

                <p class="text-muted mb-4">
                    Session atau link yang kamu buka sudah kadaluarsa.
                    Silakan kembali ke halaman sebelumnya lalu refresh halaman tersebut.
                </p>

                <a href="{{ url()->previous() }}" class="btn btn-primary btn-back">
                    <i class="fas fa-arrow-left me-2"></i>
                    Kembali ke Halaman Sebelumnya
                </a>

            </div>
        </div>
    </div>

</body>

</html>
