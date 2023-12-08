<?php

namespace App\Models;

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

    public function scopeIncome($query)
    {
        return $query->where('is_income', true);
    }

    public function scopeExpense($query)
    {
        return $query->where('is_income', false);
    }

    public function scopeFromBank($query)
    {
        return $query->whereNotNull('details->bank');
    }

    public function scopeFromWallet($query)
    {
        return $query->whereNull('details->bank');
    }

    public function scopeSplitDebitCredit($query)
    {
        return $query->selectRaw("(CASE WHEN is_income = 1 THEN created_at ELSE NULL END) as aa");
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

    function scopeSelectByNominalRange($query, $nominal_range)
    {
        return $query
            ->get()
            ->filter(function ($query) use ($nominal_range) {
                $total_nominal = (int) str_replace('.', '', $query->total_nominal);

                return $total_nominal >= (int) $nominal_range->from && $total_nominal <= (int) $nominal_range->to;
            });
    }

    /**
     * Sum each type
     */
    public function scopeSumEachType($query)
    {
        return $query->groupBy('is_income')->select([
            'is_income',
            \DB::raw('SUM(amount) as total_price'),
        ]);
    }

    /**
     * Select by created_at less or equal than x
     */
    public function scopeLteCreated($query, $created_at)
    {
        return $query->where('created_at', '<=', $created_at);
    }

    /**
     * Select by transaction_category_ids
     */
    public function scopeSelectByTransactionCategory($query, array $transaction_category_ids)
    {
        return $query->whereIn('transaction_category_id', $transaction_category_ids);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Sum nominal as total_nominal
     */
    public function getTotalNominalAttribute(): string
    {
        return rupiah(array_sum(array_column($this->details, 'nominal')));
    }

    public function getDebitAttribute()
    {
        return $this->is_income ? $this->amount_formatted : 0;
    }

    public function getCreditAttribute()
    {
        return !$this->is_income ? $this->amount_formatted : 0;
    }

    public function getAmountFormattedAttribute()
    {
        return \rupiah($this->amount);
    }

    /**
     * Total price of debit where created less or equal than current created
     */
    public function getLastTotalDebitAttribute()
    {
        return Self::lteCreated($this->created_at)->income()->sum('amount');
    }

    /**
     * Total price of credit where created less or equal than current created
     */
    public function getLastTotalCreditAttribute()
    {
        return Self::lteCreated($this->created_at)->expense()->sum('amount');
    }

    /**
     * Current balance
     */
    public function getBalanceAttribute()
    {
        return rupiah($this->last_total_debit - $this->last_total_credit);
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
