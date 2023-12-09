<?php

namespace App\Models;

use Carbon\Carbon;

class Transaction extends BaseModel
{
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'transaction_category_id',
        'created_by',
        'details',
        'is_income',
        'dates',
        'amount',
        'bank_id',
        'notes'
    ];
    protected $casts    = [
        'details' => 'array'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function transactionCategory()
    {
        return $this->belongsTo(TransactionCategory::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeSelectIncome($query)
    {
        return $query->where('is_income', true);
    }

    public function scopeSelectExpense($query)
    {
        return $query->where('is_income', false);
    }

    function scopeSelectByCreatedRange($query, $dates)
    {
        return $query->whereDate('created_at', '>=', dateFormat($dates->from))
            ->whereDate('created_at', '<=', dateFormat($dates->to));
    }

    function scopeSelectByDatesRange($query, $dates)
    {
        return $query->whereDate('dates', '>=', dateFormat($dates->from))
            ->whereDate('dates', '<=', dateFormat($dates->to));
    }

    function scopeSelectByCreator($query, $user_id)
    {
        return $query->whereCreatedBy($user_id);
    }

    /**
     * Sum each type
     */
    public function scopeSelectSumEachType($query)
    {
        return $query->groupBy('is_income')->select([
            'is_income',
            \DB::raw('SUM(amount) as total_price'),
        ]);
    }

    /**
     * Select by transaction_category_ids
     */
    public function scopeSelectByTransactionCategory($query, array $transaction_category_ids)
    {
        return $query->whereIn('transaction_category_id', $transaction_category_ids);
    }

    public function scopeSelectCurrentMonth($query)
    {
        return $query->whereMonth('dates', Carbon::now());
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getIncomeAttribute()
    {
        return $this->is_income ? $this->amount_formatted : 0;
    }

    public function getExpenseAttribute()
    {
        return !$this->is_income ? $this->amount_formatted : 0;
    }

    public function getAmountFormattedAttribute()
    {
        return \rupiah($this->amount);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = rupiahToNumber($value);
    }
}
