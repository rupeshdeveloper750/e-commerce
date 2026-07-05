<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportTicket::with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('subject', 'like', '%' . $request->search . '%')
                  ->orWhere('message', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', '%' . $request->search . '%'));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total'    => SupportTicket::count(),
            'open'     => SupportTicket::where('status', 'open')->count(),
            'pending'  => SupportTicket::where('status', 'pending')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
        ];

        return view('admin.support.index', compact('tickets', 'stats'));
    }

    public function show($id)
    {
        $ticket = SupportTicket::with('user')->findOrFail($id);
        return view('admin.support.show', compact('ticket'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'in:open,pending,resolved,closed'],
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $ticket->update([
            'status'      => $request->status,
            'admin_reply' => $request->admin_reply,
        ]);

        return back()->with('success', 'Ticket updated successfully.');
    }

    public function destroy($id)
    {
        SupportTicket::findOrFail($id)->delete();
        return back()->with('success', 'Ticket deleted.');
    }
}
