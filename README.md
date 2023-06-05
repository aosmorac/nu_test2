## Code

- Register User
  - App\Http\Controllers\api\v1\AuthController method register and verifyNewUser
  - Illuminate\Foundation\Http\FormRequest\RegisterAuthRequest
  - App\Classes\Telesign\TelesignClient
  - App\Events\ContactStored
  - App\Listeners\SendNewContactStoredEmail
- Login User
  - App\Http\Controllers\api\v1\AuthController method getLoginToken and getAccessToken
  - App\Classes\Telesign\TelesignClient
  - App\Events\LoginFirstStepDone
  - App\Listeners\SendLoginVerifySMS
- Search VIN
  - App\Http\Controllers\api\v1\VinController
  - App\Classes\Vindecoder\VindecoderClient
- Model and migration to manage the data


## Run docker and app

This project is on docker, in order to run docker

- Clone the repo
- Run `docker compose up`
- Go into the docker containe `docker exec -it nu_test-nu-api-php-1 /bin/bash` check docker ps to confirm docker php name
- Update some folder permissions `chmod 777 -R storage/` `chmod 777 -R bootstrap/cache/`
- To init laravel app run `php artisan key:generate` and `php artisan migrate`
- Init laravel passport `php artisan passport:install`
- Run queues `php artisan queue:listen`
- Move .env.example to .env and set your credentials for telesign and vindecoder services.

For Vindecoder service I exceed the API limit, the line 24 in App\Http\Controllers\api\v1\VinController is commented to avoid problems but you can remove the comment and test the call.

## Endpoints

- Register User (POST http://localhost:8070/api/v1/auth/register). 
- Verify register (POST http://localhost:8070/api/v1/auth/register/verify/USER_ID)
- Login (POST http://localhost:8070/api/v1/auth/login)
- Login verify (POST and Bearer Token http://localhost:8070/api/v1/auth/login/verify)
- Search VIN (GET and Bearer Token http://localhost:8070/api/v1/vin/search/123)

I included a postman collection in the assets folder. It doesn't have CONS in the collection you need to update the tokens and IDs when those are necessary.

## Possible Improvements 

- Create adapters to manage the clients for telesign and vindecoder services. (Adapter pattern).
- Manage the clients, or adapters if those are created, using service providers to manage those in the controller as dependency injection an improve tests.
- If increase the traffic is possible to use cache to manage the results.
