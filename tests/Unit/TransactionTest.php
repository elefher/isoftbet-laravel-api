<?php

namespace Tests\Unit;

use App\User;
use Faker\Factory;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TransactionTest extends TestCase {

    /**
     * User
     * @var null
     */
    private static $user = null;

    /**
     * Faker
     * @var null
     */
    private static $faker = null;

    /**
     * Var to store response of create a new transaction
     * @var array
     */
    static $createResponse = null;

    /**
     * Set up a faker and a new authorized user
     */
    protected function setUp(): void {
        parent::setUp();

        if (!self::$faker) {
            self::$faker = Factory::create();
        }

        if (!self::$user) {
            self::$user = User::create([
                'name' => self::$faker->realText(10),
                'email' => self::$faker->safeEmail,
                'password' => bcrypt('isoftbet'),
                'cnp' => Str::uuid()
            ]);
        }
    }

    /**
     * Create a new transaction
     */
    public function testCreateTransaction(): void {
        $data = ['amount' => self::$faker->randomNumber(3)];

        Passport::actingAs(self::$user);

        $response = $this->post(route('createTransaction'), $data);

        self::$createResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
    }

    /**
     * Display all transactions
     */
    public function testShowTransactions(): void {
        Passport::actingAs(self::$user);

        $response = $this->get(route('showTransaction'));

        $response->assertStatus(200);

        $data = $response->decodeResponseJson();
        $this->assertEquals(self::$createResponse['data']['transactionId'], $data['data'][0]['transactionId']);
    }

    /**
     * Display only a specific transaction
     */
    public function testViewTransaction(): void {
        Passport::actingAs(self::$user);

        $response = $this->get('/api/transaction/' . self::$createResponse['data']['transactionId'] . '/' . self::$user->id);

        $response->assertStatus(200);

        $data = $response->decodeResponseJson();
        $this->assertEquals(self::$createResponse['data']['transactionId'], $data['data']['transactionId']);
    }

    /**
     * Update the transaction
     */
    public function testUpdateTransaction(): void {
        Passport::actingAs(self::$user);

        $dataToUpdate = ['amount' => 150.34];

        $response = $this->put('/api/transaction/' . self::$createResponse['data']['transactionId'] . '/update', $dataToUpdate);

        $response->assertStatus(200);

        $data = $response->decodeResponseJson();
        $this->assertEquals($dataToUpdate['amount'], $data['data']['amount']);
    }

    /**
     * Delete the transaction that we created
     */
    public function testDeleteTransaction(): void {
        Passport::actingAs(self::$user);

        $response = $this->delete('/api/transaction/' . self::$createResponse['data']['transactionId'] . '/delete');

        $response->assertStatus(200);
    }
}
