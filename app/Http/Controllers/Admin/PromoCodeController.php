<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function index(Request $request)
    {
        $query = PromoCode::query();

        if ($request->filled('search')) {
            $query->where('code', 'like', "%{$request->search}%");
        }

        $promoCodes = $query->latest()->paginate(20);
        return view('admin.promo-codes.index', compact('promoCodes'));
    }

    public function create()
    {
        return view('admin.promo-codes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:promo_codes|max:50',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active');

        PromoCode::create($data);
        return redirect()->route('admin.promo-codes.index')->with('success', 'Promo code created successfully!');
    }

    public function edit(PromoCode $promoCode)
    {
        return view('admin.promo-codes.edit', compact('promoCode'));
    }

    public function update(Request $request, PromoCode $promoCode)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:promo_codes,code,' . $promoCode->id,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active');

        $promoCode->update($data);
        return redirect()->route('admin.promo-codes.index')->with('success', 'Promo code updated successfully!');
    }

    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();
        return redirect()->route('admin.promo-codes.index')->with('success', 'Promo code deleted!');
    }
}
