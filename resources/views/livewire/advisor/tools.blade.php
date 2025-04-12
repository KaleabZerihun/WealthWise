<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Financial Tools & Calculators') }}
        </h2>
    </div>
</x-slot>

<!-- Page Container -->
<div class="py-6 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- *** 1) BUDGETING *** -->
        <section class="bg-white rounded-xl shadow-lg p-8">
            <h3 class="text-2xl font-bold text-indigo-700 mb-4">1. Budgeting & Monthly Expenses</h3>
            <form wire:submit.prevent="computeBudgeting"
                  class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Monthly Income (USD)</label>
                    <input type="number" wire:model="monthlyIncome" step="0.01"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('monthlyIncome')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Rent / Mortgage</label>
                    <input type="number" wire:model="rentMortgage" step="0.01"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('rentMortgage')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Utilities (Electric, Water, etc.)</label>
                    <input type="number" wire:model="utilities" step="0.01"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('utilities')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Groceries</label>
                    <input type="number" wire:model="groceries" step="0.01"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('groceries')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Transportation</label>
                    <input type="number" wire:model="transportation" step="0.01"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('transportation')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Other Expenses</label>
                    <input type="number" wire:model="otherExpenses" step="0.01"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('otherExpenses')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Desired Monthly Emergency Savings</label>
                    <input type="number" wire:model="desiredEmergencySavings" step="0.01"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('desiredEmergencySavings')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="md:col-span-2 flex justify-end mt-4">
                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Calculate Budget
                    </button>
                </div>
            </form>

            @if(!is_null($budgetingResult))
                <div class="mt-4">
                    <p class="text-gray-800">
                        Monthly Leftover:
                        <span class="{{ $budgetingResult >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ${{ number_format($budgetingResult, 2) }}
                        </span>
                    </p>
                    @if($budgetingMessage)
                        <div class="mt-1 text-sm text-gray-700">
                            {{ $budgetingMessage }}
                        </div>
                    @endif
                </div>
            @endif
        </section>

        <!-- *** 2) RETIREMENT *** -->
        <section class="bg-white rounded-xl shadow-lg p-8">
            <h3 class="text-2xl font-bold text-indigo-700 mb-4">2. Retirement Planning</h3>
            <p class="text-sm text-gray-600 mb-3">
                Estimate if you'll reach your desired retirement savings.
                We assume monthly compounding and continuous contributions.
            </p>

            <form wire:submit.prevent="computeRetirement" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Current Age</label>
                    <input type="number" wire:model="currentAge"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('currentAge')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Retirement Age</label>
                    <input type="number" wire:model="retirementAge"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('retirementAge')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Current Savings ($)</label>
                    <input type="number" wire:model="currentSavings" step="0.01"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('currentSavings')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Monthly Contribution ($)</label>
                    <input type="number" wire:model="monthlyContrib" step="0.01"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('monthlyContrib')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Annual Interest Rate (%)</label>
                    <input type="number" wire:model="annualInterest" step="0.01"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('annualInterest')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Retirement Duration (Years)</label>
                    <input type="number" wire:model="expectedRetirementYears"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('expectedRetirementYears')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Desired Monthly Expense (In Retirement)</label>
                    <input type="number" wire:model="desiredRetirementMonthly" step="0.01"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('desiredRetirementMonthly')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="md:col-span-3 flex justify-end mt-4">
                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Calculate Retirement
                    </button>
                </div>
            </form>

            @if(!is_null($retirementResult))
                <div class="mt-4 text-gray-800">
                    <p>
                        Estimated Fund at Retirement:
                        <span class="text-green-600 font-semibold">
                            ${{ number_format($retirementResult, 2) }}
                        </span>
                    </p>
                    @if($retirementMessage)
                        <p class="mt-1 text-sm text-gray-700">
                            {{ $retirementMessage }}
                        </p>
                    @endif
                </div>
            @endif
        </section>

        <!-- *** 3) MORTGAGE *** -->
        <section class="bg-white rounded-xl shadow-lg p-8">
            <h3 class="text-2xl font-bold text-indigo-700 mb-4">3. Mortgage Calculator</h3>
            <p class="text-sm text-gray-600 mb-3">
                This estimate includes principal, interest, approximate property tax, and insurance.
                Actual amounts may vary depending on location and specific lender policies.
            </p>

            <form wire:submit.prevent="computeMortgage" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Mortgage Principal ($)</label>
                    <input type="number" wire:model="mortgagePrincipal" step="0.01"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('mortgagePrincipal')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Annual Interest Rate (%)</label>
                    <input type="number" wire:model="mortgageRate" step="0.01"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('mortgageRate')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Term (years)</label>
                    <input type="number" wire:model="mortgageTerm"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('mortgageTerm')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Property Tax Rate (%)</label>
                    <input type="number" wire:model="propertyTaxRate" step="0.01"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('propertyTaxRate')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Annual Insurance ($)</label>
                    <input type="number" wire:model="annualInsurance" step="0.01"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('annualInsurance')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="md:col-span-3 flex justify-end mt-4">
                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Calculate Mortgage
                    </button>
                </div>
            </form>

            @if(!is_null($mortgagePayment))
                <p class="mt-4 text-gray-800">
                    Estimated Monthly Payment:
                    <span class="font-semibold text-blue-600">
                        ${{ number_format($mortgagePayment, 2) }}
                    </span>
                </p>
                @if($mortgageMessage)
                    <p class="mt-1 text-sm text-gray-700">
                        {{ $mortgageMessage }}
                    </p>
                @endif
            @endif
        </section>

        <!-- *** 4) LOAN INTEREST *** -->
        <section class="bg-white rounded-xl shadow-lg p-8">
            <h3 class="text-2xl font-bold text-indigo-700 mb-4">4. Loan Interest Calculator</h3>
            <form wire:submit.prevent="computeLoan" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Loan Principal ($)</label>
                    <input type="number" wire:model="loanPrincipal" step="0.01"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('loanPrincipal')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Annual Interest Rate (%)</label>
                    <input type="number" wire:model="loanRate" step="0.01"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('loanRate')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Term (years)</label>
                    <input type="number" wire:model="loanTerm"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('loanTerm')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Down Payment ($)</label>
                    <input type="number" wire:model="loanDownPayment" step="0.01"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('loanDownPayment')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="md:col-span-3 flex justify-end mt-4">
                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Calculate Loan
                    </button>
                </div>
            </form>

            @if(!is_null($loanPayment))
                <p class="mt-4 text-gray-800">
                    Estimated Monthly Payment:
                    <span class="font-semibold text-purple-600">
                        ${{ number_format($loanPayment, 2) }}
                    </span>
                </p>
                @if($loanMessage)
                    <p class="mt-1 text-sm text-gray-700">
                        {{ $loanMessage }}
                    </p>
                @endif
            @endif
        </section>

        <!-- *** 5) INVESTMENT STRATEGIES *** -->
        <section class="bg-white rounded-xl shadow-lg p-8">
            <h3 class="text-2xl font-bold text-indigo-700 mb-4">5. Investment Strategies</h3>
            <p class="text-sm text-gray-600 mb-3">
                Approximate the future value of an initial investment plus monthly contributions.
                Optionally describe your strategy below.
            </p>

            <form wire:submit.prevent="computeInvestment" class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Initial Investment ($)</label>
                    <input type="number" wire:model="investmentInitial" step="0.01"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('investmentInitial')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Monthly Contribution ($)</label>
                    <input type="number" wire:model="investmentMonthly" step="0.01"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('investmentMonthly')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Years</label>
                    <input type="number" wire:model="investmentYears"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('investmentYears')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Annual Interest Rate (%)</label>
                    <input type="number" wire:model="investmentRate" step="0.01"
                           class="w-full border border-gray-300 p-2 rounded mt-1" />
                    @error('investmentRate')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Strategy Description (optional)
                    </label>
                    <textarea wire:model="strategyDescription"
                              rows="3"
                              class="w-full border border-gray-300 p-2 rounded mt-1"></textarea>
                    @error('strategyDescription')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="md:col-span-2 flex justify-end mt-4">
                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Compute Investment
                    </button>
                </div>
            </form>

            @if(!is_null($investmentResult))
                <p class="mt-4 text-gray-800">
                    Projected Future Value:
                    <span class="font-semibold text-pink-600">
                        ${{ number_format($investmentResult, 2) }}
                    </span>
                </p>
                @if($investmentMessage)
                    <p class="mt-1 text-sm text-gray-700">
                        {{ $investmentMessage }}
                    </p>
                @endif
            @endif
        </section>

    </div>
</div>
