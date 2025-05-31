# Laravel Backup 🚀

![GitHub release](https://img.shields.io/github/release/phwaleed2024/laravel-backup.svg) ![PHP](https://img.shields.io/badge/PHP-7.4%2B-blue.svg) ![Laravel](https://img.shields.io/badge/Laravel-12-orange.svg)

Welcome to the **Laravel Backup** repository! This package offers a straightforward solution for backing up your database and storage with automatic cleanup features. 

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Configuration](#configuration)
- [Commands](#commands)
- [Contributing](#contributing)
- [License](#license)
- [Releases](#releases)

## Features

- **Automatic Backups**: Schedule your backups effortlessly.
- **Storage Backup**: Back up your storage files along with your database.
- **Auto-Cleanup**: Keep your backup storage tidy by removing old backups automatically.
- **Easy Integration**: Simple to integrate into any Laravel application.
- **Support for Multiple Storage Options**: Works with local and cloud storage.

## Installation

To install the Laravel Backup package, follow these steps:

1. **Install via Composer**:

   Run the following command in your terminal:

   ```bash
   composer require phwaleed2024/laravel-backup
   ```

2. **Publish the Configuration**:

   After installation, publish the configuration file:

   ```bash
   php artisan vendor:publish --provider="Phwaleed2024\LaravelBackup\BackupServiceProvider"
   ```

3. **Set Up Your Backup Schedule**:

   You can set up your backup schedule in the `app/Console/Kernel.php` file.

## Usage

To create a backup, you can use the following command:

```bash
php artisan backup:run
```

This command will create a backup of your database and storage files based on your configuration settings.

## Configuration

You can configure the backup settings in the `config/backup.php` file. Here are some important options you can set:

- **Database Connections**: Specify which database connections to back up.
- **Storage Disks**: Define which storage disks to back up.
- **Backup Frequency**: Set how often you want the backups to run.

## Commands

Here are some useful commands provided by the package:

- **Run Backup**: `php artisan backup:run`
- **List Backups**: `php artisan backup:list`
- **Restore Backup**: `php artisan backup:restore {backup-name}`
- **Cleanup Old Backups**: `php artisan backup:cleanup`

## Contributing

We welcome contributions to the Laravel Backup package. If you want to contribute, please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Make your changes and commit them.
4. Push your branch and open a pull request.

Please ensure that your code follows the coding standards and includes appropriate tests.

## License

This package is licensed under the MIT License. See the [LICENSE](LICENSE) file for more information.

## Releases

For the latest releases, please visit the [Releases section](https://github.com/phwaleed2024/laravel-backup/releases). Download the necessary files and execute them as needed to get the latest features and fixes.

## Conclusion

Thank you for checking out the Laravel Backup package! We hope it simplifies your backup process and enhances your Laravel applications. For more information, refer to the [Releases section](https://github.com/phwaleed2024/laravel-backup/releases) to stay updated on the latest changes. 

Happy coding! 🎉