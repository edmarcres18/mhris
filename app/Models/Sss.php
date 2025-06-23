<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sss extends Model
{
    use HasFactory, Loggable;

    protected $table = 'ssses';

    protected $fillable = [
        'employee_id',
        'monthly_salary_credit',
        'employee_contribution',
        'employer_contribution',
        'total_contribution',
        'ec_contribution',
        'contribution_date',
    ];

    protected $casts = [
        'contribution_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public static function calculateContribution($salary)
    {
        $contributionTable = [
            ['range' => [0, 5250], 'credit' => 5000, 'ee' => 250, 'er' => 510],
            ['range' => [5250, 5749.99], 'credit' => 5500, 'ee' => 275, 'er' => 550],
            ['range' => [5750, 6249.99], 'credit' => 6000, 'ee' => 300, 'er' => 600],
            ['range' => [6250, 6749.99], 'credit' => 6500, 'ee' => 325, 'er' => 650],
            ['range' => [6750, 7249.99], 'credit' => 7000, 'ee' => 350, 'er' => 700],
            ['range' => [7250, 7749.99], 'credit' => 7500, 'ee' => 375, 'er' => 750],
            ['range' => [7750, 8249.99], 'credit' => 8000, 'ee' => 400, 'er' => 800],
            ['range' => [8250, 8749.99], 'credit' => 8500, 'ee' => 425, 'er' => 850],
            ['range' => [8750, 9249.99], 'credit' => 9000, 'ee' => 450, 'er' => 900],
            ['range' => [9250, 9749.99], 'credit' => 9500, 'ee' => 475, 'er' => 950],
            ['range' => [9750, 10249.99], 'credit' => 10000, 'ee' => 500, 'er' => 1000],
            ['range' => [10250, 10749.99], 'credit' => 10500, 'ee' => 525, 'er' => 1050],
            ['range' => [10750, 11249.99], 'credit' => 11000, 'ee' => 550, 'er' => 1100],
            ['range' => [11250, 11749.99], 'credit' => 11500, 'ee' => 575, 'er' => 1150],
            ['range' => [11750, 12249.99], 'credit' => 12000, 'ee' => 600, 'er' => 1200],
            ['range' => [12250, 12749.99], 'credit' => 12500, 'ee' => 625, 'er' => 1250],
            ['range' => [12750, 13249.99], 'credit' => 13000, 'ee' => 650, 'er' => 1300],
            ['range' => [13250, 13749.99], 'credit' => 13500, 'ee' => 675, 'er' => 1350],
            ['range' => [13750, 14249.99], 'credit' => 14000, 'ee' => 700, 'er' => 1400],
            ['range' => [14250, 14749.99], 'credit' => 14500, 'ee' => 725, 'er' => 1450],
            ['range' => [14750, 15249.99], 'credit' => 15000, 'ee' => 750, 'er' => 1500],
            ['range' => [15250, 15749.99], 'credit' => 15500, 'ee' => 775, 'er' => 1550],
            ['range' => [15750, 16249.99], 'credit' => 16000, 'ee' => 800, 'er' => 1600],
            ['range' => [16250, 16749.99], 'credit' => 16500, 'ee' => 825, 'er' => 1650],
            ['range' => [16750, 17249.99], 'credit' => 17000, 'ee' => 850, 'er' => 1700],
            ['range' => [17250, 17749.99], 'credit' => 17500, 'ee' => 875, 'er' => 1750],
            ['range' => [17750, 18249.99], 'credit' => 18000, 'ee' => 900, 'er' => 1800],
            ['range' => [18250, 18749.99], 'credit' => 18500, 'ee' => 925, 'er' => 1850],
            ['range' => [18750, 19249.99], 'credit' => 19000, 'ee' => 950, 'er' => 1900],
            ['range' => [19250, 19749.99], 'credit' => 19500, 'ee' => 975, 'er' => 1950],
            ['range' => [19750, 20249.99], 'credit' => 20000, 'ee' => 1000, 'er' => 2000],
            ['range' => [20250, 20749.99], 'credit' => 20500, 'ee' => 1025, 'er' => 2050],
            ['range' => [20750, 21249.99], 'credit' => 21000, 'ee' => 1050, 'er' => 2100],
            ['range' => [21250, 21749.99], 'credit' => 21500, 'ee' => 1075, 'er' => 2150],
            ['range' => [21750, 22249.99], 'credit' => 22000, 'ee' => 1100, 'er' => 2200],
            ['range' => [22250, 22749.99], 'credit' => 22500, 'ee' => 1125, 'er' => 2250],
            ['range' => [22750, 23249.99], 'credit' => 23000, 'ee' => 1150, 'er' => 2300],
            ['range' => [23250, 23749.99], 'credit' => 23500, 'ee' => 1175, 'er' => 2350],
            ['range' => [23750, 24249.99], 'credit' => 24000, 'ee' => 1200, 'er' => 2400],
            ['range' => [24250, 24749.99], 'credit' => 24500, 'ee' => 1225, 'er' => 2450],
            ['range' => [24750, 25249.99], 'credit' => 25000, 'ee' => 1250, 'er' => 2500],
            ['range' => [25250, 25749.99], 'credit' => 25500, 'ee' => 1275, 'er' => 2550],
            ['range' => [25750, 26249.99], 'credit' => 26000, 'ee' => 1300, 'er' => 2600],
            ['range' => [26250, 26749.99], 'credit' => 26500, 'ee' => 1325, 'er' => 2650],
            ['range' => [26750, 27249.99], 'credit' => 27000, 'ee' => 1350, 'er' => 2700],
            ['range' => [27250, 27749.99], 'credit' => 27500, 'ee' => 1375, 'er' => 2750],
            ['range' => [27750, 28249.99], 'credit' => 28000, 'ee' => 1400, 'er' => 2800],
            ['range' => [28250, 28749.99], 'credit' => 28500, 'ee' => 1425, 'er' => 2850],
            ['range' => [28750, 29249.99], 'credit' => 29000, 'ee' => 1450, 'er' => 2900],
            ['range' => [29250, 29749.99], 'credit' => 29500, 'ee' => 1475, 'er' => 2950],
            ['range' => [29750, 30249.99], 'credit' => 30000, 'ee' => 1500, 'er' => 3000],
            ['range' => [30250, 30749.99], 'credit' => 30500, 'ee' => 1525, 'er' => 3050],
            ['range' => [30750, 31249.99], 'credit' => 31000, 'ee' => 1550, 'er' => 3100],
            ['range' => [31250, 31749.99], 'credit' => 31500, 'ee' => 1575, 'er' => 3150],
            ['range' => [31750, 32249.99], 'credit' => 32000, 'ee' => 1600, 'er' => 3200],
            ['range' => [32250, 32749.99], 'credit' => 32500, 'ee' => 1625, 'er' => 3250],
            ['range' => [32750, 33249.99], 'credit' => 33000, 'ee' => 1650, 'er' => 3300],
            ['range' => [33250, 33749.99], 'credit' => 33500, 'ee' => 1675, 'er' => 3350],
            ['range' => [33750, 34249.99], 'credit' => 34000, 'ee' => 1700, 'er' => 3400],
            ['range' => [34250, 34749.99], 'credit' => 34500, 'ee' => 1725, 'er' => 3450],
            ['range' => [34750, PHP_INT_MAX], 'credit' => 35000, 'ee' => 1750, 'er' => 3500],
        ];

        foreach ($contributionTable as $tier) {
            if ($salary >= $tier['range'][0] && $salary <= $tier['range'][1]) {
                return [
                    'monthly_salary_credit' => $tier['credit'],
                    'employee_contribution' => $tier['ee'],
                    'employer_contribution' => $tier['er'],
                    'total_contribution' => $tier['ee'] + $tier['er'],
                    'ec_contribution' => $tier['credit'] < 15000 ? 10.00 : 30.00,
                ];
            }
        }

        // If salary is higher than the maximum tier, use the last tier
        $lastTier = end($contributionTable);
        return [
            'monthly_salary_credit' => $lastTier['credit'],
            'employee_contribution' => $lastTier['ee'],
            'employer_contribution' => $lastTier['er'],
            'total_contribution' => $lastTier['ee'] + $lastTier['er'],
            'ec_contribution' => 30.00, // Last tier will always be above 15000
        ];
    }

    public static function createContribution(Employee $employee, $contributionDate)
    {
        // Check if a contribution already exists for the employee on the given date
        $existingContribution = self::where('employee_id', $employee->id)
            ->where('contribution_date', $contributionDate)
            ->first();

        if ($existingContribution) {
            // Optionally, update the existing contribution or return a message
            return $existingContribution; // or handle as needed
        }

        $salary = $employee->salary;
        $contribution = self::calculateContribution($salary);

        $newContribution = self::create([
            'employee_id' => $employee->id,
            'monthly_salary_credit' => $contribution['monthly_salary_credit'],
            'employee_contribution' => $contribution['employee_contribution'],
            'employer_contribution' => $contribution['employer_contribution'],
            'total_contribution' => $contribution['total_contribution'],
            'ec_contribution' => $contribution['ec_contribution'],
            'contribution_date' => $contributionDate,
        ]);

        // Create SssContribution entries
        self::createSssContributionEntries($employee->id, $contribution['employee_contribution'], $contributionDate);

        return $newContribution;
    }

    private static function createSssContributionEntries($employeeId, $employeeContribution, $contributionDate)
    {
        $employee = Employee::find($employeeId);
        $contribution = self::calculateContribution($employee->salary);
        $employerContribution = $contribution['employer_contribution'];
        
        if ($employee->department->name === 'BGPDI') {
            // For BGPDI employees - weekly contributions (1/4 of the total)
            $quarterContribution = $employeeContribution / 4;
            $quarterEmployerContribution = $employerContribution / 4;
            $contributionMonth = Carbon::parse($contributionDate);
            
            // Set weekly dates (7th, 14th, 21st, 28th of the month)
            $weeklyDates = [
                $contributionMonth->copy()->setDay(7),
                $contributionMonth->copy()->setDay(14),
                $contributionMonth->copy()->setDay(21),
                $contributionMonth->copy()->setDay(28),
            ];

            foreach ($weeklyDates as $date) {
                SssContribution::create([
                    'employee_id' => $employeeId,
                    'date' => $date,
                    'sss_contribution' => $quarterContribution,
                ]);

                Contribution::updateOrCreate(
                    [
                        'employee_id' => $employeeId,
                        'date' => $date,
                    ],
                    [
                        'sss_contribution' => $quarterContribution,
                        'employer_sss_contribution' => $quarterEmployerContribution,
                    ]
                );
            }
        } else {
            // For other departments - bi-monthly contributions (1/2 of the total)
            $halfContribution = $employeeContribution / 2;
            $halfEmployerContribution = $employerContribution / 2;
            $contributionMonth = Carbon::parse($contributionDate);

            $firstDate = $contributionMonth->copy()->setDay(10);
            $lastDate = $contributionMonth->copy()->setDay(25);

            foreach ([$firstDate, $lastDate] as $date) {
                SssContribution::create([
                    'employee_id' => $employeeId,
                    'date' => $date,
                    'sss_contribution' => $halfContribution,
                ]);

                Contribution::updateOrCreate(
                    [
                        'employee_id' => $employeeId,
                        'date' => $date,
                    ],
                    [
                        'sss_contribution' => $halfContribution,
                        'employer_sss_contribution' => $halfEmployerContribution,
                    ]
                );
            }
        }
    }
}
