<?php

namespace App\Services;

use App\Classes\BalanceCalculator;
use App\Interfaces\BalanceRepositoryInterface;
use App\Models\Balance;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BalanceService
{

    protected BalanceRepositoryInterface $balanceRepository;
    protected BalanceHistoryService $balanceHistoryService;
    protected BalanceCalculator $balanceCalculator;

    public function __construct(
        BalanceRepositoryInterface $balanceRepository,
        BalanceHistoryService $balanceHistoryService,
        BalanceCalculator $balanceCalculator
    ) {
        $this->balanceRepository = $balanceRepository;
        $this->balanceHistoryService = $balanceHistoryService;
        $this->balanceCalculator = $balanceCalculator;
    }

    /**
     * Store balance
     * @param array $data
     * @return void
     * @throws \Exception
     */
    public function store(array $data): void
    {
        DB::beginTransaction();
        try {

            $amount_usd = $data['amount_usd'];
            $usd_rate = $data['usd_rate'];

            $amount = $this->balanceCalculator->calculateAmountGel($amount_usd, $usd_rate);
            $data['amount_gel'] = $data['amount'] = $amount;
            $data['author_id'] = auth()->id();

            $balance = $this->balanceRepository->create($data);
            $this->balanceHistoryService->create($balance, 'deposit');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
    }

    /**
     * @param Expense $expense
     * @param bool $return
     * @return Balance|void
     */
    public function refund(Expense $expense, bool $return = false)
    {
        // Add back the expense amount to the user's balance
        $balance = $this->balanceRepository->find($expense->balance_id);
        $balance->amount += $expense->amount_gel;
        $balance->save();

        if ($return) {
            return $balance;
        }
    }




    /**
     * Get sum of given field by given period
     * @param Carbon $startOfCurrentPeriod
     * @param Carbon $endOfCurrentPeriod
     * @param Carbon $startOfPreviousPeriod
     * @param Carbon $endOfPreviousPeriod
     * @param string $field
     * @return array
     */
    public function getFieldSumByPeriod(
        Carbon $startOfCurrentPeriod,
        Carbon $endOfCurrentPeriod,
        Carbon $startOfPreviousPeriod,
        Carbon $endOfPreviousPeriod,
        string $field
    ): array {

        $currentPeriodSum = $this->balanceRepository->getFieldSumByPeriod($startOfCurrentPeriod, $endOfCurrentPeriod,
            $field);
        $previousPeriodSum = $this->balanceRepository->getFieldSumByPeriod($startOfPreviousPeriod, $endOfPreviousPeriod,
            $field);

        // Calculate the percentage change
        $percentageChange = 0;
        if ($previousPeriodSum > 0) {
            $percentageChange = (($currentPeriodSum - $previousPeriodSum) / $previousPeriodSum) * 100;
        } elseif ($currentPeriodSum > 0) {
            // If the previous period was zero but the current period is not, then it's a 100% increase
            $percentageChange = 100;
        }

        // Determine if it's an increase or decrease
        $direction = $percentageChange >= 0 ? 'increased' : 'decreased';

        return [
            'sum' => round(floatval($currentPeriodSum), 2),
            'percentage' => [
                'value' => round(floatval(abs($percentageChange)), 2),
                'direction' => $direction,
            ],
        ];
    }
}
