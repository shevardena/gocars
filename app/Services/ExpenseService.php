<?php

namespace App\Services;

use App\Interfaces\BackendUserRepositoryInterface;
use App\Interfaces\BalanceRepositoryInterface;
use App\Interfaces\CarRepositoryInterface;
use App\Interfaces\ExpenseRepositoryInterface;
use App\Models\BackendUser;
use App\Models\Balance;
use App\Models\Car;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExpenseService
{
    protected ExpenseRepositoryInterface $expenseRepository;
    protected BalanceHistoryService $balanceHistoryService;
    protected CarRepositoryInterface $carRepository;
    protected BackendUserRepositoryInterface $backendUserRepository;
    protected BalanceRepositoryInterface $balanceRepository;
    protected BalanceService $balanceService;


    public function __construct(
        ExpenseRepositoryInterface     $expenseRepository,
        BalanceHistoryService          $balanceHistoryService,
        CarRepositoryInterface         $carRepository,
        BackendUserRepositoryInterface $backendUserRepository,
        BalanceRepositoryInterface     $balanceRepository,
        BalanceService                 $balanceService,
    )
    {
        $this->expenseRepository = $expenseRepository;
        $this->balanceHistoryService = $balanceHistoryService;
        $this->carRepository = $carRepository;
        $this->backendUserRepository = $backendUserRepository;
        $this->balanceRepository = $balanceRepository;
        $this->balanceService = $balanceService;
    }

    /**
     * Returns total amount of expenses by given car
     * @param int|null $car_id
     * @return array
     */
    public function getTotalAmount(int $car_id = null): array
    {
        $data = [
            'with' => [
                'car',
                'backend_user'
            ],
            'car_id' => $car_id
        ];

        $expenses = $this->expenseRepository->filter($data);

        return [
            'usd' => round(floatval($expenses->sum('amount_usd')), 2),
            'gel' => round(floatval($expenses->sum('amount_gel')), 2)
        ];
    }

    /**
     * Store expense
     * @param array $data
     * @param Car|null $car
     * @throws Exception
     */
    public function create(array $data, Car $car = null): void
    {
        DB::beginTransaction();
        try {

            if (empty($data['car_id']) && !empty($car->id)) {
                $data['car_id'] = $car->id;
            }

            $usd_rate = $data['usd_rate'] ?? ExchangeRateService::getUsdRate();
            $amount_usd = $data['amount_usd'] ?? null;
            $amount_gel = $data['amount_gel'] ?? null;

            if ($amount_usd > $amount_gel) {
                $data['amount_gel'] = number_format($amount_usd * $usd_rate, 2, '.', '');
            }

            if ($amount_gel) {
                $data['amount_usd'] = number_format($amount_gel / $usd_rate, 2, '.', '');
            }

            $data['usd_rate'] = $usd_rate;
            $data['operation_id'] = Str::uuid();

            $this->expenseRepository->create($data);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
    }

    /**
     * @param array $data
     * @param string $operation_id
     * @throws Exception
     */
    public function processUpdate(array $data, string $operation_id): void
    {
        $user = auth()->user();
        $expense = $this->expenseRepository->findByOperation($operation_id);

        // For Super Admins
        if ($user->super_admin) {

            // If Super Admin is not the original author
            if ($user->id !== $expense->backend_user_id) {

                $data['backend_user_id'] = $expense->backend_user_id;  // Set the original author
                $this->deleteOperations($operation_id);
                $this->create($data);

            } else {
                // If Super Admin is the original author
                $this->update($expense->id, $data);
            }

        } else {
            // For Regular Users
            $this->deleteOperations($operation_id);
            $this->storeCalculatedAmounts($data);
        }
    }


    /**
     * Update expense
     * @param int $expense_id
     * @param array $data
     * @throws Exception
     */
    public function update(int $expense_id, array $data): void
    {
        DB::beginTransaction();
        try {
            $this->expenseRepository->update($expense_id, $data);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
    }

    /**
     * @param array $data
     * @param Car|null $car
     * @return void
     * @throws Exception
     */
    public function storeCalculatedAmounts(array $data, Car $car = null): void
    {
        $amount_gel = $data['amount_gel'];
        $title = $data['title'];

        // Generate an operation_id
        $operation_id = Str::uuid();

        if (!$car) {
            $car = $this->carRepository->find($data['car_id']);
        }

        DB::beginTransaction();

        try {

            // Get user's balances ordered by id (oldest first)
            $balances = $this->balanceRepository->getByUser();

            foreach ($balances as $balance) {
                $this->processExpenseOnBalance($balance, $amount_gel, $title, $car, $operation_id);

                if ($amount_gel <= 0) {
                    break;
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception($e);
        }
    }

    private function processExpenseOnBalance($balance, &$amount_gel, $title, $car, $operation_id): void
    {
        $usd_amount = min($balance->amount, $amount_gel) / $balance->usd_rate;

        $expense_data = [
            'title' => $title,
            'amount_gel' => min($balance->amount, $amount_gel),
            'amount_usd' => $usd_amount,
            'car_id' => $car->id,
            'balance_id' => $balance->id,
            'operation_id' => $operation_id,
            'usd_rate' => $balance->usd_rate,
        ];

        $this->expenseRepository->create($expense_data);
        $this->balanceHistoryService->create($expense_data, 'expense');

        if ($balance->amount >= $amount_gel) {
            $balance->amount -= $amount_gel;
            $amount_gel = 0;
        } else {
            $amount_gel -= $balance->amount;
            $balance->amount = 0;
        }

        $balance->save();
    }

    /**
     * @param Balance $balance
     * @param float $amount
     * @param BackendUser $user
     * @param string $operation_type
     * @return void
     * @throws Exception
     */
    private function createBalanceHistoryRecordForBalance(
        Balance     $balance,
        float       $amount,
        BackendUser $user,
        string      $operation_type
    ): void
    {
        $data = [
            'operation_type' => $operation_type,
            'amount_gel' => $amount,
            'amount_usd' => $amount / $balance->usd_rate,
            'usd_rate' => $balance->usd_rate,
            'author_id' => $user->id,
            'balance_id' => $balance->id,
        ];
        $this->balanceHistoryService->create($data, 'refund');
    }

    /**
     * @param string $operation_id
     * @return void
     * @throws Exception
     */
    public function deleteOperations(string $operation_id): void
    {
        $expenses = $this->expenseRepository->getByOperation($operation_id);

        DB::beginTransaction();

        try {

            foreach ($expenses as $expense) {

                $user = $this->backendUserRepository->find($expense->backend_user_id);
                if ($user->hasRole('Employee')) {
                    // Create BalanceHistory record for refund
                    $this->balanceService->refund($expense);
                    $this->balanceHistoryService->create($expense, 'refund');
                }

                // Delete the expense
                $expense->delete();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception($e);
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
    ): array
    {

        $currentPeriodSum = $this->expenseRepository->getFieldSumByPeriod($startOfCurrentPeriod, $endOfCurrentPeriod,
            $field);
        $previousPeriodSum = $this->expenseRepository->getFieldSumByPeriod($startOfPreviousPeriod, $endOfPreviousPeriod,
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


    /**
     * Dashboard chart
     * @return array
     */
    public function getChartData(): array
    {

        $startOfYear = Carbon::now()->startOfYear();
        $endOfCurrentMonth = Carbon::now()->endOfMonth();

        $monthly_expenses = [];
        for ($date = $startOfYear; $date->lte($endOfCurrentMonth); $date->addMonth()) {
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $expense = $this->expenseRepository->getFieldSumByPeriod($startOfMonth, $endOfMonth, 'amount_usd');

            $monthly_expenses['series'][] = round(floatval($expense), 2);
            $monthly_expenses['categories'][] = $date->format('F');
        }
        return [
            'type' => 'bar',
            'options' => [
                'chart' => [
                    'id' => 'vuechart-example',
                ],
                'xaxis' => [
                    'categories' => $monthly_expenses['categories'],
                ],
            ],
            'series' => [
                [
                    'name' => 'Expenses',
                    'data' => $monthly_expenses['series'],
                ],
            ],
        ];
    }
}
