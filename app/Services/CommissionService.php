<?php

namespace App\Services;

use App\Models\{Commission, Payment, User};

class CommissionService
{
    /**
     * Calculate and record commissions for a payment.
     * Commission is based on the sum of all payments for the same application.
     * Uses the user's individual commission_percentage from the users table.
     *
     * @param Payment $payment
     * @return void
     */
    public function calculateCommissions(Payment $payment)
    {
        if ($payment->payment_status !== 'completed') {
            return;
        }

        $application = $payment->application;
        if (!$application) {
            return;
        }

        $student = $application->student;
        if (!$student) {
            return;
        }

        // Calculate total amount for all completed payments in this application
        $totalAmount = Payment::where('application_id', $application->id)
            ->where('payment_status', 'completed')
            ->sum('amount');

        // Get the first payment of the application as reference
        $firstPayment = Payment::where('application_id', $application->id)
            ->where('payment_status', 'completed')
            ->first();

        // Marketing Commission - uses individual user's commission_percentage
        if ($student->assigned_marketing_id && $firstPayment) {
            $marketingUser = User::find($student->assigned_marketing_id);
            if ($marketingUser && $marketingUser->commission_percentage !== null) {
                $this->createApplicationCommission(
                    $firstPayment,
                    $marketingUser->id,
                    'marketing',
                    $totalAmount,
                    $marketingUser->commission_percentage
                );
            }
        }
    }

    /**
     * Create a commission record based on application total (sum of all payments).
     */
    protected function createApplicationCommission($payment, $userId, $role, $totalAmount, $percentage)
    {
        $percentage = (float) $percentage;
        if ($percentage <= 0) {
            return;
        }

        $commissionAmount = ($totalAmount * $percentage) / 100;

        Commission::updateOrCreate(
            [
                'user_id' => $userId,
                'role' => $role,
                'payment_id' => $payment->id,
            ],
            [
                'amount' => $commissionAmount,
                'percentage' => $percentage,
                'status' => 'pending',
            ]
        );
    }
}
