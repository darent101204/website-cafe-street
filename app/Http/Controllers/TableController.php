<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Handle access to a table via QR token.
     *
     * @param string $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function access($token)
    {
        $table = Table::where('qr_token', $token)->firstOrFail();

        session([
            'table_id' => $table->id,
            'table_number' => $table->table_number,
            'order_type' => 'dine_in'
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Welcome to Table ' . $table->table_number);
    }
}
