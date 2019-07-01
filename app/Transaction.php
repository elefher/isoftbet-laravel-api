<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'amount',
    ];

    /**
     * Create alias for columns
     * @var array
     */
    protected $maps = [
        'id' => 'transactionId',
        'user_id' => 'customerId',
        'created_at' => 'date',
    ];

    /**
     * Appends the alias columns
     * @var array
     */
    protected $appends = ['transactionId','customerId', 'date'];

    /**
     * Hide the original columns
     * @var array
     */
    protected $hidden = ['id', 'user_id', 'created_at', 'updated_at'];

    /**
     * Returns the created date
     * @return string
     */
    public function getDateAttribute(): string {
        return $this->attributes['created_at'];
    }

    /**
     * Returns the id
     * @return int
     */
    public function getTransactionIdAttribute(): int {
        return $this->attributes['id'];
    }

    /**
     * Returns the user id
     * @return int
     */
    public function getCustomerIdAttribute(): int {
        return $this->attributes['user_id'];
    }

    /**
     * Checks if user has access to this transaction
     * @param int $id
     * @return bool
     */
    public function hasAccess(int $id): bool {
        return $this->attributes['id'] == $id;
    }
}