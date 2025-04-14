<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup Files</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">

        @if (session('success') || session('error'))
            <div class="alert alert-{{ session('success') ? 'success' : 'danger' }} alert-dismissible fade show"
                role="alert">
                {{ session('success') ?? session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <script>
                setTimeout(function() {
                    document.querySelector('.alert').style.display = 'none';
                }, 7000);
            </script>
        @endif
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-4">Backup Files</h2>
            <a href="{{ route('laravel-backup.create') }}" class="btn btn-primary mb-3">Create Backup</a>

        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Database Backup</th>
                    @if (config('laravelBackup.backup_storage_directory'))
                        <th>Storage Backup</th>
                    @endif
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($backups as $backup)
                    <tr>
                        <td>{{ $backup['timestamp'] }}</td>
                        <td>
                            @if ($backup['database'])
                                <a href="{{ route('laravel-backup.download', ['file' => $backup['database']]) }}"
                                    class="btn btn-sm btn-primary">
                                    Download DB
                                </a>
                            @else
                                N/A
                            @endif
                        </td>
                        @if (config('laravelBackup.backup_storage_directory'))
                            <td>
                                @if ($backup['storage'])
                                    <a href="{{ route('laravel-backup.download', ['file' => $backup['storage']]) }}"
                                        class="btn btn-sm btn-success">
                                        Download Storage
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                        @endif
                        <td>
                            <form action="{{ route('laravel-backup.delete', ['timestamp' => $backup['timestamp']]) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
