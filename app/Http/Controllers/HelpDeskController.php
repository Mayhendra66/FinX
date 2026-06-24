<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HelpdeskController extends Controller
{
    /**
     * Menampilkan halaman helpdesk yang langsung berisi form pengajuan.
     */
    public function index()
    {
        return view('helpdesk.index');
    }

    /**
     * Menyimpan data tiket dari form langsung ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject'  => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high',
        ]);

        Ticket::create([
            'ticket_id' => 'TKT-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
            'user_id'   => auth()->id(),
            'subject'   => $request->subject,
            'category'  => $request->category,
            'priority'  => $request->priority,
            'status'    => 'open',
        ]);

        // Redirect kembali ke halaman form dengan notifikasi sukses
        return redirect()->route('helpdesk.index')->with('success', 'Tiket bantuan berhasil dikirim.');
    }
}