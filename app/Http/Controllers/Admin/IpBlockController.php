<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IpBlock;
use Illuminate\Http\Request;

class IpBlockController extends Controller
{
    public function index()
    {
        $blockedIps = IpBlock::latest()->paginate(20);
        return view('admin.ip_blocks.index', compact('blockedIps'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip|unique:ip_blocks,ip_address',
            'reason' => 'nullable|string|max:255',
        ]);

        IpBlock::create($request->all());

        return back()->with('success', 'IP address blocked successfully.');
    }

    public function destroy(IpBlock $ipBlock)
    {
        $ipBlock->delete();
        return back()->with('success', 'IP address unblocked successfully.');
    }
}
