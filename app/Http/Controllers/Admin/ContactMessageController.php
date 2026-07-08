<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index()
    {
        $total  = ContactMessage::count();
        $read   = ContactMessage::where('is_read', true)->count();
        $unread = $total - $read;
        $today  = ContactMessage::whereDate('created_at', today())->count();

        $messages = ContactMessage::latest()->paginate(10);

        return view('admin.messages.index', compact('messages', 'total', 'read', 'unread', 'today'));
    }

    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);

        if (! $message->is_read) {
            $message->update(['is_read' => true]);
            $message->refresh();
        }

        return view('admin.messages.show', compact('message'));
    }

    public function toggleRead($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['is_read' => ! $message->is_read]);

        return response()->json([
            'ok'      => true,
            'is_read' => (bool) $message->is_read,
        ]);
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'integer',
            'action' => 'required|in:read,delete',
        ]);

        $ids = $request->input('ids');

        if ($request->input('action') === 'delete') {
            $count = ContactMessage::whereIn('id', $ids)->delete();
            $msg   = $count . ' message' . ($count === 1 ? '' : 's') . ' deleted.';
        } else {
            $count = ContactMessage::whereIn('id', $ids)->update(['is_read' => true]);
            $msg   = $count . ' message' . ($count === 1 ? '' : 's') . ' marked as read.';
        }

        return response()->json([
            'ok'   => true,
            'done' => $count,
            'msg'  => $msg,
        ]);
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);

        $message->delete();

        return redirect()
            ->route('admin.messages')
            ->with('success', 'Message deleted successfully.');
    }
}