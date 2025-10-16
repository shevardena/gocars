<?php

namespace App\Filament\Resources\Cars\RelationManagers;

use App\Services\ExpenseService;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class ExpensesRelationManager extends RelationManager
{
    protected static string $relationship = 'expenses';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->label('Title')
                ->required(),

            TextInput::make('amount_gel')
                ->label('Amount GEL')
                ->numeric()
                ->visible(function ($record) {
                    $user = Auth::user();
                    if (!$record) {
                        return true;
                    }

                    return !$user->super_admin || ($user->super_admin && $record->backend_user_id !== $user->id);
                }),

            TextInput::make('amount_usd')
                ->label('Amount USD')
                ->numeric()
                ->visible(function ($record) {
                    $user = Auth::user();
                    if (!$record) {
                        return $user->super_admin;
                    }

                    return $user->super_admin && $record->backend_user_id === $user->id;
                }),

            TextInput::make('usd_rate')
                ->label('USD Rate')
                ->numeric()
                ->visible(function ($record) {
                    $user = Auth::user();
                    if (!$record) {
                        return $user->super_admin;
                    }

                    return $user->super_admin && $record->backend_user_id === $user->id;
                }),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $user = Auth::user();
                if (!$user->super_admin) {
                    $query->where('backend_user_id', $user->id);
                }
                $query->orderByDesc('created_at');
            })
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')->label('Title')->sortable(),
                TextColumn::make('amount_gel')
                    ->label('GEL')
                    ->formatStateUsing(fn($state) => number_format($state, 2) . ' ₾'),
                TextColumn::make('amount_usd')
                    ->label('USD')
                    ->formatStateUsing(fn($state) => number_format($state, 2) . ' $'),
                TextColumn::make('backend_user.first_name')
                    ->label('Author')
                    ->formatStateUsing(fn($state, $record) => trim(($record->backend_user?->first_name ?? '') . ' ' . ($record->backend_user?->last_name ?? ''))),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Expense')
                    ->visible(fn() => Auth::user()->can('expenses.create'))
                    ->action(function (array $data, RelationManager $livewire) {
                        $user = Auth::user();
                        $expenseService = app(ExpenseService::class);

                        $car = $livewire->ownerRecord;
                        $data['car_id'] = $car->id;

                        try {
                            if ($user->can('view all expenses')) {
                                $expenseService->create($data, $car);
                            } else {
                                $expenseService->storeCalculatedAmounts($data, $car);
                            }

                            Notification::make()
                                ->title('Expense added successfully')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Whoops!')
                                ->body('Something went wrong: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->recordActions([
                ViewAction::make(),

                EditAction::make()
                    ->visible(function ($record) {
                        $user = Auth::user();
                        if ($user->super_admin) {
                            return true;
                        }
                        return $record->backend_user_id === $user->id;
                    })
                    ->action(function (array $data, $record, RelationManager $livewire) {
                        $user = Auth::user();
                        $expenseService = app(ExpenseService::class);
                        $car = $livewire->ownerRecord;
                        $data['car_id'] = $car->id;

                        try {
                            if (
                                !$user->can('edit expense')
                                && $record->backend_user_id !== $user->id
                            ) {
                                throw new \Exception('Permission denied');
                            }

                            $expenseService->processUpdate($data, $record->operation_id);

                            Notification::make()
                                ->title('Expense updated successfully')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Whoops!')
                                ->body('Something went wrong: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                DeleteAction::make()
                    ->visible(function ($record) {
                        $user = Auth::user();
                        if ($user->super_admin) {
                            return true;
                        }
                        return $record->backend_user_id === $user->id;
                    })
                    ->action(function ($record) {
                        $user = Auth::user();
                        $expenseService = app(ExpenseService::class);

                        try {
                            if (
                                !$user->can('delete expense')
                                && $record->backend_user_id !== $user->id
                            ) {
                                throw new \Exception('Permission denied');
                            }

                            $expenseService->deleteOperations($record->operation_id);

                            Notification::make()
                                ->title('Expense deleted')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Whoops!')
                                ->body('Something went wrong: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ]);
    }
}
