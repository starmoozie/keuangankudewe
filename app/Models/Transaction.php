<?php

namespace App\Models;

use Carbon\Carbon;

class Transaction extends BaseModel
{
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
        'notes',
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

    /**
     * Select only incomes
     */
    public function scopeSelectIncome($query)
    {
        return $query->where('is_income', true);
    }

    /**
     * Select only expenses
     */
    public function scopeSelectExpense($query)
    {
        return $query->where('is_income', false);
    }

    /**
     * Select by created_at range
     */
    function scopeSelectByCreatedRange($query, $dates)
    {
        return $query->whereDate('created_at', '>=', dateFormat($dates->from))
            ->whereDate('created_at', '<=', dateFormat($dates->to));
    }

    /**
     * Select by dates range
     */
    function scopeSelectByDatesRange($query, object $dates)
    {
        return $query->whereDate('dates', '>=', dateFormat($dates->from))
            ->whereDate('dates', '<=', dateFormat($dates->to));
    }

    /**
     * Select by user creator
     */
    function scopeSelectByCreator($query, string $user_id)
    {
        return $query->whereCreatedBy($user_id);
    }

    /**
     * Select sum each type
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

    /**
     * Select only current month
     */
    public function scopeSelectCurrentMonth($query)
    {
        return $query->whereMonth('dates', Carbon::now());
    }

    /**
     * Select order by relationship
     */
    public function scopeOrderByRelationship($query, $relationship, $orderDirection)
    {
        $to_snake_relation_name = \Str::snake($relationship['name']);

        return $query->withAggregate($relationship['name'], $relationship['column'])
            ->orderBy("{$to_snake_relation_name}_{$relationship['column']}", $orderDirection);
    }

    /**
     * Select order by amount
     */
    public function scopeOrderByAmount($query, $direction)
    {
        return $query->orderByRaw("CONVERT(amount, SIGNED) {$direction}");
    }

    /**
     * 
     */
    public function scopeWhereInJson($query, string $column, array $values)
    {
        return $query->where(function ($query) use ($column, $values) {
            foreach ($values as $value) {
                $query->orWhereJsonContains("{$column}", $value);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Append income attributes
     */
    public function getIncomeAttribute(): string
    {
        return $this->is_income ? $this->amount_formatted : 0;
    }

    /**
     * Append expense attributes
     */
    public function getExpenseAttribute(): string
    {
        return !$this->is_income ? $this->amount_formatted : 0;
    }

    /**
     * Append amount_formatted to rupiah attributes
     */
    public function getAmountFormattedAttribute(): string
    {
        return \rupiah($this->amount);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    /**
     * Set amount attributes before store to db
     */
    public function setAmountAttribute($value): void
    {
        $this->attributes['amount'] = rupiahToNumber($value);
    }

    /**
     * Set user for attributes before store to db
     */
    public function setDetailsAttribute($value)
    {
        $this->attributes['details'] = \json_encode($value);
    }
}
