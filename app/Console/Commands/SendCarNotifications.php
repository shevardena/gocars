<?php

namespace App\Console\Commands;

use App\Mail\CarNotification;
use App\Models\Car;
use App\Services\SmsService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use JetBrains\PhpStorm\NoReturn;

class SendCarNotifications extends Command
{
    protected $signature = 'notifications:send';
    protected $description = 'Send notifications based on car arrival date';
    protected SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
        parent::__construct();
    }

    /**
     * @throws GuzzleException
     */
    public function handle(): void
    {
        // Get the days from the notification_parameters table
        $days = DB::table('notification_parameters')
            ->where('parameter', 'day')
            ->pluck('value')
            ->all();

        // For each day, find cars and send notifications
        foreach ($days as $day) {
            $targetDate = Carbon::now()->subDays($day);
            $cars = Car::whereDate('arrival_date', $targetDate)->get();

            if ($cars->count()) {
                $phones = DB::table('notification_parameters')
                    ->where('parameter', 'phone')
                    ->pluck('value')
                    ->all();

                $emails = DB::table('notification_parameters')
                    ->where('parameter', 'email')
                    ->pluck('value')
                    ->all();

                // Send one email with all cars to each email address
                foreach ($emails as $email) {
                    Mail::to($email)->send(new CarNotification($cars, $day));
                }

                // Send an SMS for each car to all phone numbers
                foreach ($cars as $car) {
                    $this->buildSms($car, $day, $phones);
                }
            }
        }
    }

    public function buildSms($car, $day, $phones): void
    {
        $text = 'ჩამოთვლილი მანქანების ჩამოყვანიდან გავიდა ' . $day . '-ე დღე ';

        $text .= isset($car->model->make->name) && isset($car->model->name)
            ? $car->model->make->name . ' ' . $car->model->name . ' ' . $car->year . ' ' . $car->vin . ' '
            : ((isset($car->model->make->name) && !isset($car->model->name))
                ? $car->model->make->name . ' ' . $car->year . ' ' . $car->vin . ' '
                : $car->year . ' ' . $car->vin . ' ');

        $text .= url('car/' . $car->id . '/' . $car->slug) . ' ';

        $phones = collect($phones);
        $phonesList = $phones->join(',');

        $this->smsService->send($phonesList, $text);
    }
}
