<?php


namespace Tests;


use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Passport\ClientRepository;

class PassportTestCase extends TestCase {
    use DatabaseTransactions;

    protected $headers = [];
    protected $scopes = [];
    protected $user;

    /**
     * Create token for testing
     */
    protected function setUp(): void {
        parent::setUp();

        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            null, 'Test Personal Access Client', $this->baseUrl ?? ''
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime(),
        ]);

        $faker = Factory::create();
        $this->user = User::create([
            'name' => $faker->realText(10),
            'email' => $faker->realText(10) . '@gmail.com',
            'password' => bcrypt('isoftbet'),
            'cnp' => Str::uuid()
        ]);
        $token = $this->user->createToken('TestToken', $this->scopes)->accessToken;
        $this->headers['Accept'] = 'application/json';
        $this->headers['Authorization'] = 'Bearer ' . $token;
    }

    /**
     * Merge headers before send get request
     * @param string $uri
     * @param array $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function get($uri, array $headers = []) {
        return parent::get($uri, array_merge($this->headers, $headers));
    }

    /**
     * Merge headers before send the put request
     * @param string $uri
     * @param array $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function put($uri, array $headers = []) {
        return parent::put($uri, array_merge($this->headers, $headers));
    }

    /**
     * Merge headers before send the post request
     * @param string $uri
     * @param array $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function post($uri, array $headers = []) {
        return parent::post($uri, array_merge($this->headers, $headers));
    }
}
