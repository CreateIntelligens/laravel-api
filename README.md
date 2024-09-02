# Laravel API Package

A robust Laravel package for implementing the Repository and Service patterns in your API development. This package provides a solid foundation for building scalable and maintainable APIs by separating concerns and promoting clean code architecture.

## Features

- Base Repository and Service classes
- Artisan commands for generating Repositories and Services
- Automatic binding of Repositories and Services in the Service Container
- JSON response handling
- Easy integration with Laravel projects

## Requirements

- PHP 8.2+
- Laravel 11.0+

## Installation

1. Add the package to your Laravel project:

```bash
composer require bleuren/laravel-api
```

2. Publish the package configuration (optional):

```bash
php artisan vendor:publish --provider="Bleuren\LaravelApi\Providers\LaravelApiServiceProvider" --tag="config"
```

## Usage

### Creating a Repository

To create a new repository, use the following Artisan command:

```bash
php artisan make:repository User
```

This will create a new `UserRepository` class in the `app/Repositories` directory and a corresponding `UserRepositoryInterface` in the `app/Contracts` directory.

### Creating a Service

To create a new service, use the following Artisan command:

```bash
php artisan make:service User
```

This will create a new `UserService` class in the `app/Services` directory and a corresponding `UserServiceInterface` in the `app/Contracts` directory.

### Using Repositories and Services in Controllers

After creating your repositories and services, you can use them in your controllers like this:

```php
use App\Contracts\UserServiceInterface;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return $this->userService->all();
    }

    public function show($id)
    {
        return $this->userService->find($id);
    }

    // ... other methods
}
```

## Extending Base Classes

You can extend the base Repository and Service classes to add custom functionality:

```php
use Bleuren\LaravelApi\BaseRepository;
use App\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    // Add custom methods here
}
```

```php
use Bleuren\LaravelApi\BaseService;
use App\Contracts\UserRepositoryInterface;

class UserService extends BaseService
{
    public function __construct(UserRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    // Add custom methods here
}
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).