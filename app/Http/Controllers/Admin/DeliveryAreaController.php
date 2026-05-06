<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryArea;
use Illuminate\Http\Request;

class DeliveryAreaController extends Controller
{
    public function index()
    {
        $areas = DeliveryArea::orderBy('sort_order', 'asc')->get();
        return view('admin.delivery_areas.index', compact('areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'charge' => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer',
        ]);

        DeliveryArea::create($request->all());

        return back()->with('success', 'Delivery area added successfully!');
    }

    public function update(Request $request, DeliveryArea $deliveryArea)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'charge' => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer',
        ]);

        $deliveryArea->update($request->all());

        return back()->with('success', 'Delivery area updated successfully!');
    }

    public function destroy(DeliveryArea $deliveryArea)
    {
        $deliveryArea->delete();
        return back()->with('success', 'Delivery area deleted successfully!');
    }
}
