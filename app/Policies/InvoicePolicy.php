<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InvoicePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // A user can view any invoices if they are logged in and have at least one shop.
        return $user->shops()->exists();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Invoice $invoice): bool
    {
        // A user can view a specific invoice if that invoice belongs to one of the shops managed by the user.
        return $user->shops->contains($invoice->shop);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // A user can create an invoice if they are logged in and have at least one shop.
        return $user->shops()->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Invoice $invoice): bool
    {
        // A user can update an invoice if that invoice belongs to one of the shops managed by the user.
        return $user->shops->contains($invoice->shop);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Invoice $invoice): bool
    {
        // A user can delete an invoice if that invoice belongs to one of the shops managed by the user.
        return $user->shops->contains($invoice->shop);
    }


    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Invoice $invoice): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Invoice $invoice): bool
    {
        //
    }
}
