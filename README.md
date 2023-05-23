## Code

- Store contact API endpoint
  - App\Http\Controllers\api\v1\ContactController.php
  - Illuminate\Foundation\Http\FormRequest\StoreContactRequest.php
  - Tests\Feature\Http\Controllers\api\v1\ContactControllerTest.php
- Send Email after store a new contact. I created an event an a listener to manage that (Change env config to test this)
  - App\Events\ContactStored.php
  - App\Listeners\SendNewContactStoredEmail.php
- Model and migration to manage the data
- Basic component using Vue. (It is pretty basic just to do the endpoint call)
  - resources/js/Nu.vue


## Run docker and app

This project is on docker, in order to run docker

- Clone the repo
- Run `docker compose up`
- Go into the docker containe `docker exec -it nu_test-nu-api-php-1 /bin/bash` check docker ps to confirm docker php name
- Update some folder permissions `chmod 777 -R storage/` `chmod 777 -R bootstrap/cache/`
- To init laravel app run `php artisan key:generate` and `php artisan migrate`
- And build the vue component `npm run build`

The url is going to be http://localhost:8070/ and the contact endpoint is localhost:8070/api/v1/contact using POST. Don forget add `Accept application/json` in the headers if you are going to test directly the endpoint.

