<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     * Update the user's shop settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'gstin' => 'nullable|string|max:15',
            'state_code' => 'nullable|string|max:2',
            'address' => 'nullable|string',
        ]);

        $user = Auth::user();
        
        // Get or create shop for the user
        $shop = $user->shop ?? $user->shop()->create([]);
        
        // Update shop details
        $shop->update($validated);

        return redirect()->route('settings')->with('success', 'Shop details updated successfully!');
    }
}
