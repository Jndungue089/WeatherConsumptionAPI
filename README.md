# Weather Consumption API

The Weather Consumption API is a Laravel-based application that provides user management with CRUD operations and integrates with the OpenWeatherMap API to fetch weather data for a user's city. It uses `Tymon\JWTAuth` for secure authentication, Laravel's HTTP Client for external API calls, and Swagger for API documentation.

## Features
- User registration and login with JWT authentication.
- CRUD operations for users (list, show, create, update, delete).
- Weather data retrieval for a user’s city via OpenWeatherMap API.
- Secure storage of API credentials in `.env`.
- Comprehensive error handling for internal and external API calls.
- Swagger/OpenAPI documentation for endpoint exploration.

## Requirements
- PHP >= 8.1
- Composer
- Laravel >= 10.x
- MySQL or another supported database
- OpenWeatherMap API key (free tier available at [https://openweathermap.org/api](https://openweathermap.org/api))
- Node.js and npm (for frontend assets, if applicable)

## Installation

1. **Clone the Repository**:
   ```bash
   git clone <repository-url>
   cd weather-consumption-api
   ```

2. **Install Dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Configure Environment**:
   - Copy the example environment file:
     ```bash
     cp .env.example .env
     ```
   - Update `.env` with your database credentials and OpenWeatherMap API key:
     ```env
     APP_NAME="Weather Consumption API"
     APP_URL=http://localhost:8000

     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=weather_api
     DB_USERNAME=root
     DB_PASSWORD=

     OPENWEATHERMAP_API_KEY=your_openweathermap_api_key
     ```
   - Obtain an OpenWeatherMap API key by signing up at [https://openweathermap.org/api](https://openweathermap.org/api) and copying it from the "API keys" tab (activation may take a few hours).

4. **Generate Keys**:
   ```bash
   php artisan key:generate
   php artisan jwt:secret
   ```

5. **Run Migrations**:
   Create the database and run migrations to set up the `users` table:
   ```bash
   php artisan migrate
   ```

6. **Install Swagger**:
   ```bash
   composer require darkaonline/l5-swagger
   php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
   ```

7. **Start the Server**:
   ```bash
   php artisan serve
   ```

## API Endpoints

All endpoints are prefixed with `/api`. Authentication-required endpoints use a Bearer JWT token in the `Authorization` header.

### Authentication Endpoints

| Method | Endpoint                | Description                          |
|--------|-------------------------|--------------------------------------|
| POST   | `/auth/register`        | Register a new user                  |
| POST   | `/auth/login`           | Login and obtain a JWT token         |

### User Endpoints (Authenticated)

| Method | Endpoint                     | Description                     |
|--------|------------------------------|---------------------------------|
| GET    | `/users`                     | List all users                  |
| GET    | `/users/show/{id}`           | Get a user by ID                |
| POST   | `/users`                     | Create a new user               |
| PUT    | `/users/update/{id}`         | Update a user                   |
| DELETE | `/users/delete/{id}`         | Delete a user                   |
| GET    | `/users/weather`             | Get weather for user's city     |

## Endpoint Details

### Register a User
- **URL**: `POST /api/auth/register`
- **Body**:
  ```json
  {
      "name": "John Doe",
      "email": "john@example.com",
      "password": "password123",
      "password_confirmation": "password123",
      "city": "London"
  }
  ```
- **Response** (201):
  ```json
  {
      "data": {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com",
          "city": "London"
      },
      "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
  }
  ```
- **Error** (422):
  ```json
  {
      "error": {
          "email": ["The email has already been taken."]
      }
  }
  ```

### Login
- **URL**: `POST /api/auth/login`
- **Body**:
  ```json
  {
      "email": "john@example.com",
      "password": "password123"
  }
  ```
- **Response** (200):
  ```json
  {
      "user": {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com",
          "city": "London"
      },
      "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
  }
  ```
- **Error** (401):
  ```json
  {
      "error": "Unauthorized"
  }
  ```

### List Users
- **URL**: `GET /api/users`
- **Headers**:
  ```
  Authorization: Bearer {token}
  Accept: application/json
  ```
- **Response** (200):
  ```json
  {
      "data": [
          {
              "id": 1,
              "name": "John Doe",
              "email": "john@example.com",
              "city": "London"
          }
      ]
  }
  ```

### Get User by ID
- **URL**: `GET /api/users/show/{id}`
- **Headers**:
  ```
  Authorization: Bearer {token}
  Accept: application/json
  ```
- **Response** (200):
  ```json
  {
      "data": {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com",
          "city": "London"
      }
  }
  ```
- **Error** (404):
  ```json
  {
      "message": "No query results for model [App\\Models\\User] {id}"
  }
  ```

### Create a User
- **URL**: `POST /api/users`
- **Headers**:
  ```
  Authorization: Bearer {token}
  Accept: application/json
  Content-Type: application/json
  ```
- **Body**:
  ```json
  {
      "name": "Jane Doe",
      "email": "jane@example.com",
      "password": "password123",
      "password_confirmation": "password123",
      "city": "Paris"
  }
  ```
- **Response** (201):
  ```json
  {
      "data": {
          "id": 2,
          "name": "Jane Doe",
          "email": "jane@example.com",
          "city": "Paris"
      }
  }
  ```

### Update a User
- **URL**: `PUT /api/users/update/{id}`
- **Headers**:
  ```
  Authorization: Bearer {token}
  Accept: application/json
  Content-Type: application/json
  ```
- **Body**:
  ```json
  {
      "name": "Jane Updated",
      "email": "jane.updated@example.com",
      "city": "Paris"
  }
  ```
- **Response** (200):
  ```json
  {
      "data": {
          "id": 2,
          "name": "Jane Updated",
          "email": "jane.updated@example.com",
          "city": "Paris"
      }
  }
  ```
- **Error** (403):
  ```json
  {
      "message": "This action is unauthorized."
  }
  ```

### Delete a User
- **URL**: `DELETE /api/users/delete/{id}`
- **Headers**:
  ```
  Authorization: Bearer {token}
  Accept: application/json
  ```
- **Response** (204): No content
- **Error** (403):
  ```json
  {
      "message": "This action is unauthorized."
  }
  ```

### Get Weather
- **URL**: `GET /api/users/weather`
- **Headers**:
  ```
  Authorization: Bearer {token}
  Accept: application/json
  ```
- **Response** (200):
  ```json
  {
      "data": {
          "city": "London",
          "temperature": 15,
          "description": "clear sky"
      }
  }
  ```
- **Error Responses**:
  - `400`: City not set for user.
    ```json
    {
        "error": "City not set for user"
    }
    ```
  - `401`: Unauthorized (invalid/missing token).
    ```json
    {
        "error": "Unauthorized"
    }
    ```
  - `500`: Failed to fetch weather data.
    ```json
    {
        "error": "Failed to fetch weather data: {exception_message}"
    }
    ```

## Swagger Documentation
- **Access**: [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)
- **Generate**:
  ```bash
  php artisan l5-swagger:generate
  ```

## Testing with Postman

1. **Register a User**:
   ```bash
   curl -X POST http://localhost:8000/api/auth/register \
   -H "Content-Type: application/json" \
   -d '{"name":"John Doe","email":"john@example.com","password":"password123","password_confirmation":"password123","city":"London"}'
   ```

2. **Login**:
   ```bash
   curl -X POST http://localhost:8000/api/auth/login \
   -H "Content-Type: application/json" \
   -d '{"email":"john@example.com","password":"password123"}'
   ```

3. **Get Weather**:
   ```bash
   curl -X GET http://localhost:8000/api/users/weather \
   -H "Authorization: Bearer {token}" \
   -H "Accept: application/json"
   ```

4. **List Users**:
   ```bash
   curl -X GET http://localhost:8000/api/users \
   -H "Authorization: Bearer {token}" \
   -H "Accept: application/json"
   ```

5. **Update a User** (replace `{id}` with user ID):
   ```bash
   curl -X PUT http://localhost:8000/api/users/update/{id} \
   -H "Authorization: Bearer {token}" \
   -H "Accept: application/json" \
   -H "Content-Type: application/json" \
   -d '{"name":"John Updated","email":"john.updated@example.com","city":"Paris"}'
   ```

## Configuration Details

### OpenWeatherMap API Key
- **Sign Up**: Register at [https://openweathermap.org/api](https://openweathermap.org/api).
- **Get Key**: Copy your API key from the “API keys” tab.
- **Store Securely**: Add to `.env`:
  ```env
  OPENWEATHERMAP_API_KEY=your_api_key_here
  ```
- **Access**: The key is accessed via `config('services.openweathermap.key')` to prevent hardcoding.

### JWT Authentication
- **Setup**:
  ```bash
  composer require tymon/jwt-auth
  php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
  php artisan jwt:secret
  ```
- **Configuration**:
  Update `config/auth.php`:
  ```php
  'guards' => [
      'api' => [
          'driver' => 'jwt',
          'provider' => 'users',
      ],
  ],
  'providers' => [
      'users' => [
          'driver' => 'eloquent',
          'model' => App\Models\User::class,
      ],
  ],
  ```
- **Token Usage**: Include the token in the `Authorization` header as `Bearer {token}` for protected endpoints.

### Database
- **Schema**:
  - `users` table: `id`, `name`, `email`, `password`, `city`, `api_token`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`.
- **Migration**:
  ```bash
  php artisan migrate
  ```

## Error Handling
- **Validation Errors** (422): Invalid input (e.g., missing fields, duplicate email).
- **Authentication Errors** (401): Invalid or missing JWT token.
- **Authorization Errors** (403): User attempts to update/delete another user’s data.
- **Weather API Errors**:
  - `400`: User has no city set.
  - `404`: City not found by OpenWeatherMap.
  - `500`: Network issues or API downtime.
- **Logs**: Check `storage/logs/laravel.log` for detailed errors.

## Troubleshooting
- **401 Unauthorized**:
  - Verify `JWT_SECRET` in `.env`.
  - Ensure `Authorization: Bearer {token}` header is included.
  - Check `config/auth.php` for `api` guard set to `jwt`.
- **500 Weather API Error**:
  - Confirm OpenWeatherMap API key is valid and activated.
  - Ensure user’s `city` field is set (update via `/api/users/update/{id}`).
  - Check network connectivity to `api.openweathermap.org`.
- **Swagger Documentation Issues**:
  - Ensure `app/Http/OpenApi/Swagger.php` exists with `@OA\Info`.
  - Update `config/l5-swagger.php`:
    ```php
    'annotations' => [
        app_path('Http/Controllers'),
        app_path('Http/OpenApi'),
    ],
    ```
  - Clear caches:
    ```bash
    php artisan config:clear
    php artisan cache:clear
    ```
  - Regenerate:
    ```bash
    php artisan l5-swagger:generate
    ```
  - Check permissions:
    ```bash
    chmod -R 775 storage
    chown -R www-data:www-data storage
    ```

## Running Tests
Unit tests verify user CRUD and weather functionality.

1. **Setup**:
   Ensure `phpunit` is installed via Composer.
2. **Test File**: Create `tests/Feature/UserApiTest.php`:
   ```php
   <?php
   namespace Tests\Feature;

   use App\Models\User;
   use Illuminate\Foundation\Testing\RefreshDatabase;
   use Tests\TestCase;
   use Tymon\JWTAuth\Facades\JWTAuth;

   class UserApiTest extends TestCase
   {
       use RefreshDatabase;

       protected $user;
       protected $token;

       protected function setUp(): void
       {
           parent::setUp();
           $this->user = User::factory()->create(['city' => 'London']);
           $this->token = JWTAuth::fromUser($this->user);
       }

       public function test_can_register_user()
       {
           $response = $this->postJson('/api/auth/register', [
               'name' => 'Jane Doe',
               'email' => 'jane@example.com',
               'password' => 'password123',
               'password_confirmation' => 'password123',
               'city' => 'Paris',
           ]);

           $response->assertStatus(201)
               ->assertJsonStructure(['data' => ['id', 'name', 'email', 'city'], 'token']);
       }

       public function test_can_get_weather()
       {
           \Illuminate\Support\Facades\Http::fake([
               'api.openweathermap.org/*' => \Illuminate\Support\Facades\Http::response([
                   'main' => ['temp' => 15],
                   'weather' => [['description' => 'clear sky']],
               ], 200),
           ]);

           $response = $this->getJson('/api/users/weather', [
               'Authorization' => "Bearer {$this->token}",
           ]);

           $response->assertStatus(200)
               ->assertJsonStructure(['data' => ['city', 'temperature', 'description']]);
       }
   }
   ```
3. **Run Tests**:
   ```bash
   php artisan test
   ```

## Project Structure
- **Controllers**: `app/Http/Controllers/Api/UserController.php`
- **Models**: `app/Models/User.php`
- **Policies**: `app/Policies/UserPolicy.php`
- **Routes**: `routes/api.php`
- **Swagger**: `app/Http/OpenApi/Swagger.php`
- **Tests**: `tests/Feature/UserApiTest.php`
- **Configuration**: `config/services.php`, `.env`

## Future Improvements
- **Rate Limiting**: Throttle OpenWeatherMap API calls to stay within free tier limits.
- **Caching**: Cache weather responses to reduce API usage (e.g., using Laravel’s Cache facade).
- **City Update Endpoint**: Dedicated endpoint for updating a user’s city.
- **Additional Weather Data**: Include humidity, wind speed, or forecasts.
- **Role-Based Access**: Restrict user CRUD operations to admins.

## License
This project is open-source and available under the MIT License.

---
*Generated on May 30, 2025, for the Weather Consumption API project.*