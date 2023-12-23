<?php

namespace App\Http\Controllers\Admin\Api;

use App\Constants\TransactionConstant;
use App\Http\Controllers\Controller;
use App\Models\Transaction;

class DashboardApiController extends Controller
{
    public function fetch()
    {
        $transactions = Transaction::selectSumEachType()
            ->when($this->checkAccess(), fn ($q) => $q->selectByCreator(starmoozie_user()->id))
            ->orderByDesc('is_income')
            ->get();

        $data = collect(TransactionConstant::DASHBOARD)
            ->map(function($item) use($transactions) {
                $value = $item['value'] === TransactionConstant::BALANCE
                    ? $this->getBalance($transactions)
                    : $transactions->where('is_income', $item['value'])->first()?->total_price;
                $item['value'] = $value;

                return $item;
            });

        return response()
            ->json([
                'success' => true,
                'data'    => $data
            ]);
    }

    /**
     * Calculate balance
     */
    private function getBalance($transactions)
    {
        $income  = $transactions->where('is_income', TransactionConstant::INCOME)->first()?->total_price;
        $expense = $transactions->where('is_income', TransactionConstant::EXPENSE)->first()?->total_price;

        return $income - $expense;
    }

    /**
     * Check if user has access personal on income or expense menu
     */
    private function checkAccess()
    {
        return starmoozie_user()
            ->menu
            ->whereIn('name', ['Income', 'Expense'])
            ->where('permission', 'Personal')
            ->count();
    }
}
