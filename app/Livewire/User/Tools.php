<?php

namespace App\Livewire\User;

use Livewire\Component;

class Tools extends Component
{
    /*
     |-------------------------------------------
     | 1) Budgeting
     |-------------------------------------------
     */
    public $monthlyIncome = '';
    public $rentMortgage = '';
    public $utilities = '';
    public $groceries = '';
    public $transportation = '';
    public $otherExpenses = '';
    public $desiredEmergencySavings = '';
    public $budgetingResult = null;
    public $budgetingMessage = null;

    public function computeBudgeting()
    {
        $this->validate([
            'monthlyIncome'         => 'required|numeric|min:0',
            'rentMortgage'          => 'required|numeric|min:0',
            'utilities'             => 'required|numeric|min:0',
            'groceries'             => 'required|numeric|min:0',
            'transportation'        => 'required|numeric|min:0',
            'otherExpenses'         => 'required|numeric|min:0',
            'desiredEmergencySavings' => 'required|numeric|min:0',
        ]);

        // Sum total expenses
        $totalExpenses = $this->rentMortgage + $this->utilities + $this->groceries + $this->transportation + $this->otherExpenses + $this->desiredEmergencySavings;
        $leftover = $this->monthlyIncome - $totalExpenses;

        $this->budgetingResult = $leftover;

        // Provide more details
        if ($leftover >= $this->desiredEmergencySavings) {
            $this->budgetingMessage = "Great! You can cover all expenses and still meet your emergency savings target.";
        } elseif ($leftover > 0) {
            $this->budgetingMessage = "You have some leftover, but it’s below your desired emergency savings. Consider adjusting expenses.";
        } else {
            $this->budgetingMessage = "Your expenses exceed your income. You may need to reduce spending or increase income.";
        }
    }

    /*
     |-------------------------------------------
     | 2) Retirement
     |-------------------------------------------
     */
    public $currentAge = '';
    public $retirementAge = '';
    public $currentSavings = '';
    public $monthlyContrib = '';
    public $annualInterest = '';
    public $expectedRetirementYears = '';
    public $desiredRetirementMonthly = '';
    public $retirementResult = null;
    public $retirementMessage = null;

    public function computeRetirement()
    {
        $this->validate([
            'currentAge'             => 'required|integer|min:0',
            'retirementAge'          => 'required|integer|gte:currentAge',
            'currentSavings'         => 'required|numeric|min:0',
            'monthlyContrib'         => 'required|numeric|min:0',
            'annualInterest'         => 'required|numeric|min:0',
            'expectedRetirementYears'=> 'required|integer|min:1',
            'desiredRetirementMonthly'=> 'required|numeric|min:0',
        ]);

        $yearsToGrow = $this->retirementAge - $this->currentAge;
        $monthsToGrow = $yearsToGrow * 12;
        $monthlyRate = ($this->annualInterest / 100) / 12;

        // Future value of current savings
        $fvCurrent = $this->currentSavings * pow((1 + $monthlyRate), $monthsToGrow);

        // Future value of monthly contributions (annuity)
        $fvContrib = 0;
        if ($monthlyRate > 0) {
            $fvContrib = $this->monthlyContrib *
                ((pow((1 + $monthlyRate), $monthsToGrow) - 1) / $monthlyRate);
        } else {
            $fvContrib = $this->monthlyContrib * $monthsToGrow;
        }

        $totalRetirementFund = $fvCurrent + $fvContrib;
        $this->retirementResult = $totalRetirementFund;

        // Compute if user can sustain the desired monthly withdrawal
        $requiredRetirementTotal = $this->desiredRetirementMonthly * 12 * $this->expectedRetirementYears;
        if ($totalRetirementFund >= $requiredRetirementTotal) {
            $this->retirementMessage = "Based on your plan, your fund should cover your desired retirement spending for {$this->expectedRetirementYears} years.";
        } else {
            $shortFall = $requiredRetirementTotal - $totalRetirementFund;
            $this->retirementMessage = "You may need approximately \$" . number_format($shortFall,2) . " more to sustain the desired monthly expense in retirement.";
        }
    }

    /*
     |-------------------------------------------
     | 3) Mortgage
     |-------------------------------------------
     */
    public $mortgagePrincipal = '';
    public $mortgageRate = '';
    public $mortgageTerm = '';
    public $propertyTaxRate = '';
    public $annualInsurance = '';
    public $mortgagePayment = null;
    public $mortgageMessage = null;

    public function computeMortgage()
    {
        $this->validate([
            'mortgagePrincipal' => 'required|numeric|min:1',
            'mortgageRate'      => 'required|numeric|min:0',
            'mortgageTerm'      => 'required|numeric|min:1',
            'propertyTaxRate'   => 'required|numeric|min:0',
            'annualInsurance'   => 'required|numeric|min:0',
        ]);

        $principal = $this->mortgagePrincipal;
        $annualInterest = $this->mortgageRate / 100;
        $monthlyInterest = $annualInterest / 12;
        $months = $this->mortgageTerm * 12;

        // base monthly payment
        if ($monthlyInterest > 0) {
            $basePayment = $principal * ($monthlyInterest * pow((1 + $monthlyInterest), $months))
                / (pow((1 + $monthlyInterest), $months) - 1);
        } else {
            $basePayment = $principal / $months;
        }

        // property tax (approx monthly)
        $propertyTaxMonthly = ($this->propertyTaxRate / 100) * $principal / 12;

        // insurance monthly
        $insuranceMonthly = $this->annualInsurance / 12;

        $this->mortgagePayment = $basePayment + $propertyTaxMonthly + $insuranceMonthly;

        $this->mortgageMessage = "Monthly Payment includes principal, interest, approx. property tax, and insurance.";
    }

    /*
     |-------------------------------------------
     | 4) Loan Interest
     |-------------------------------------------
     */
    public $loanPrincipal = '';
    public $loanRate = '';
    public $loanTerm = '';
    public $loanDownPayment = '';
    public $loanPayment = null;
    public $loanMessage = null;

    public function computeLoan()
    {
        $this->validate([
            'loanPrincipal'   => 'required|numeric|min:1',
            'loanRate'        => 'required|numeric|min:0',
            'loanTerm'        => 'required|numeric|min:1',
            'loanDownPayment' => 'required|numeric|min:0',
        ]);

        $principal = $this->loanPrincipal - $this->loanDownPayment;
        if ($principal < 0) {
            $principal = 0; // if down payment bigger than loan, effectively zero loan
        }

        $annualInterest = $this->loanRate / 100;
        $monthlyInterest = $annualInterest / 12;
        $months = $this->loanTerm * 12;

        if ($monthlyInterest > 0) {
            $this->loanPayment = $principal * ($monthlyInterest * pow((1 + $monthlyInterest), $months))
                / (pow((1 + $monthlyInterest), $months) - 1);
        } else {
            $this->loanPayment = $principal / $months;
        }

        $this->loanMessage = "Based on a down payment of \$" . number_format($this->loanDownPayment,2) .
            ", your monthly payment is shown above.";
    }

    /*
     |-------------------------------------------
     | 5) Investment Strategies
     |-------------------------------------------
     */
    public $investmentInitial = '';
    public $investmentMonthly = '';
    public $investmentYears = '';
    public $investmentRate = '';
    public $strategyDescription = '';
    public $investmentResult = null;
    public $investmentMessage = null;

    public function computeInvestment()
    {
        $this->validate([
            'investmentInitial'   => 'required|numeric|min:0',
            'investmentMonthly'   => 'required|numeric|min:0',
            'investmentYears'     => 'required|numeric|min:1',
            'investmentRate'      => 'required|numeric|min:0',
            'strategyDescription' => 'nullable|string|max:1000',
        ]);

        $months = $this->investmentYears * 12;
        $mRate = ($this->investmentRate / 100) / 12;

        // naive future value for lump sum
        $fvInitial = $this->investmentInitial * pow((1 + $mRate), $months);

        // monthly contributions
        $fvContrib = 0;
        if ($mRate > 0) {
            $fvContrib = $this->investmentMonthly *
                ((pow(1 + $mRate, $months) - 1) / $mRate);
        } else {
            $fvContrib = $this->investmentMonthly * $months;
        }

        $this->investmentResult = $fvInitial + $fvContrib;

        $this->investmentMessage = "Strategy: " . ($this->strategyDescription ?: 'No specific strategy described.');
    }
    public function render()
    {
        return view('livewire.user.tools')->layout('layouts.app');
    }
}
