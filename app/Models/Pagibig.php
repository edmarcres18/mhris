<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Pagibig extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'employee_id',
        'employee_contribution',
        'employer_contribution',
        'total_contribution',
        'contribution_date',
    ];

    protected $casts = [
        'contribution_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function calculateContribution()
    {
        // Fixed contribution of 200 for both employee and employer
        $fixedContribution = 200;

        $this->employee_contribution = $fixedContribution;
        $this->employer_contribution = $fixedContribution;
        $this->total_contribution = $this->employee_contribution + $this->employer_contribution;

        return $this;
    }

    public static function createContribution(Employee $employee, $contributionDate)
    {
        $contribution = new self();
        $contribution->employee()->associate($employee);
        $contribution->contribution_date = $contributionDate;
        $contribution->calculateContribution();
        $contribution->save();

        // Create PagibigContribution entries
        self::createPagibigContributions($contribution);

        return $contribution;
    }

    public static function createPagibigContributions(Pagibig $pagibig)
    {
        $employee = Employee::find($pagibig->employee_id);

        if ($employee->department->name === 'BGPDI') {
            // For BGPDI employees - weekly contributions (1/4 of the total)
            $quarterContribution = $pagibig->employee_contribution / 4;
            $quarterEmployerContribution = $pagibig->employer_contribution / 4;
            $contributionDate = $pagibig->contribution_date;

            // Set weekly dates
            $weeklyDates = [
                $contributionDate->copy()->setDay(7),
                $contributionDate->copy()->setDay(14),
                $contributionDate->copy()->setDay(21),
                $contributionDate->copy()->setDay(28),
            ];

            foreach ($weeklyDates as $date) {
                PagibigContribution::create([
                    'employee_id' => $pagibig->employee_id,
                    'pagibig_contribution' => $quarterContribution,
                    'date' => $date,
                ]);

                Contribution::updateOrCreate(
                    [
                        'employee_id' => $pagibig->employee_id,
                        'date' => $date,
                    ],
                    [
                        'pagibig_contribution' => $quarterContribution,
                        'employer_pagibig_contribution' => $quarterEmployerContribution,
                    ]
                );
            }
        } else {
            // Original bi-monthly logic for other departments
            $employeeContribution = $pagibig->employee_contribution / 2;
            $employerContribution = $pagibig->employer_contribution / 2;
            $contributionDate = $pagibig->contribution_date;

            $firstDate = $contributionDate->copy()->setDay(10);
            $secondDate = $contributionDate->copy()->setDay(25);

            foreach ([$firstDate, $secondDate] as $date) {
                PagibigContribution::create([
                    'employee_id' => $pagibig->employee_id,
                    'pagibig_contribution' => $employeeContribution,
                    'date' => $date,
                ]);

                Contribution::updateOrCreate(
                    [
                        'employee_id' => $pagibig->employee_id,
                        'date' => $date,
                    ],
                    [
                        'pagibig_contribution' => $employeeContribution,
                        'employer_pagibig_contribution' => $employerContribution,
                    ]
                );
            }
        }
    }
}
