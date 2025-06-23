<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Philhealth extends Model
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
        $salary = $this->employee->salary;
        $monthlyBasicSalary = min(max($salary, 10000), 80000);

        $premium = $monthlyBasicSalary * 0.05;
        $employeeShare = $premium / 2;
        $employerShare = $premium / 2;

        $this->employee_contribution = round($employeeShare, 2);
        $this->employer_contribution = round($employerShare, 2);
        $this->total_contribution = round($premium, 2);

        return $this;
    }

    public function storeWithContributions()
    {
        $this->save();
        
        $employee = Employee::find($this->employee_id);
        $contributionDate = Carbon::parse($this->contribution_date);

        if ($employee->department->name === 'BGPDI') {
            // For BGPDI employees - weekly contributions (1/4 of the total)
            $quarterContribution = $this->employee_contribution / 4;
            $quarterEmployerContribution = $this->employer_contribution / 4;
            $roundedQuarterContribution = round($quarterContribution, 2);
            $roundedQuarterEmployerContribution = round($quarterEmployerContribution, 2);

            // Set weekly dates
            $weeklyDates = [
                Carbon::create($contributionDate->year, $contributionDate->month, 7),
                Carbon::create($contributionDate->year, $contributionDate->month, 14),
                Carbon::create($contributionDate->year, $contributionDate->month, 21),
                Carbon::create($contributionDate->year, $contributionDate->month, 28),
            ];

            foreach ($weeklyDates as $date) {
                PhilhealthContribution::create([
                    'employee_id' => $this->employee_id,
                    'philhealth_contribution' => $roundedQuarterContribution,
                    'date' => $date,
                ]);

                Contribution::updateOrCreate(
                    [
                        'employee_id' => $this->employee_id,
                        'date' => $date,
                    ],
                    [
                        'philhealth_contribution' => $roundedQuarterContribution,
                        'employer_philhealth_contribution' => $roundedQuarterEmployerContribution,
                    ]
                );
            }
        } else {
            // Original bi-monthly logic for other departments
            $halfContribution = $this->employee_contribution / 2;
            $halfEmployerContribution = $this->employer_contribution / 2;
            $roundedHalfContribution = round($halfContribution, 2);
            $roundedHalfEmployerContribution = round($halfEmployerContribution, 2);

            $firstHalfDate = Carbon::create($contributionDate->year, $contributionDate->month, 10);
            $secondHalfDate = Carbon::create($contributionDate->year, $contributionDate->month, 25);

            foreach ([$firstHalfDate, $secondHalfDate] as $date) {
                PhilhealthContribution::create([
                    'employee_id' => $this->employee_id,
                    'philhealth_contribution' => $roundedHalfContribution,
                    'date' => $date,
                ]);

                Contribution::updateOrCreate(
                    [
                        'employee_id' => $this->employee_id,
                        'date' => $date,
                    ],
                    [
                        'philhealth_contribution' => $roundedHalfContribution,
                        'employer_philhealth_contribution' => $roundedHalfEmployerContribution,
                    ]
                );
            }
        }

        return $this;
    }
}
