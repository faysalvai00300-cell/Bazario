@extends('layouts.admin')
@php
    $statusLabel = request('status') ? ucfirst(request('status')) . ' Orders' : 'All Orders';
    $pageTitle = $statusLabel . ' (' . $orders->total() . ')';
@endphp
@section('title', $pageTitle)
@section('content')

<div x-data="{ 
    selected: [],
    selectAll: false,
    noteModal: { open: false, orderId: null, type: '', value: '' },
    courierModal: { 
        open: false, 
        type: '', 
        cities: [], 
        zones: [], 
        areas: [], 
        loading: false,
        selectedCity: '',
        selectedZone: '',
        selectedArea: ''
    },
    toggleAll() {
        if (this.selected.length > 0) {
            this.selected = [];
            this.selectAll = false;
        } else {
            this.selected = Array.from(document.querySelectorAll('.order-checkbox')).map(cb => cb.value);
            this.selectAll = true;
        }
    },
    openCourierModal(type) {
        if (this.selected.length === 0) {
            return window.Toast.fire({icon:'error', title:'Please select orders first'});
        }
        this.courierModal.type = type;
        this.courierModal.open = true;
        this.courierModal.loading = true;
        this.courierModal.areas = [];
        this.courierModal.zones = [];
        this.courierModal.cities = [];

        if (type === 'redx') {
            fetch('/admin/get-courier-locations?type=redx_areas')
                .then(res => res.json())
                .then(areas => {
                    if (areas.error) {
                        this.courierModal.open = false; // Close modal on error
                        window.Toast.fire({ icon: 'error', title: 'RedX API Error', text: areas.error });
                        this.courierModal.loading = false;
                        return;
                    }
                    if (!Array.isArray(areas) || areas.length === 0) {
                        this.courierModal.open = false; // Close modal on empty
                        window.Toast.fire({ icon: 'warning', title: 'Setup Required', text: 'RedX API Token not found. Please check Courier Settings.' });
                    }
                    this.courierModal.areas = Array.isArray(areas) ? areas : [];
                    this.courierModal.loading = false;
                })
                .catch(err => {
                    this.courierModal.open = false; // Close modal on connection failure
                    this.courierModal.loading = false;
                    window.Toast.fire({ icon: 'error', title: 'Connection Failed', text: 'Could not connect to RedX API.' });
                });
        } else if (type === 'pathao') {
            fetch('/admin/get-courier-locations?type=pathao_cities')
                .then(res => res.json())
                .then(cities => {
                    if (cities.error) {
                        this.courierModal.open = false; // Close modal on error
                        window.Toast.fire({ icon: 'error', title: 'Pathao API Error', text: cities.error });
                        this.courierModal.loading = false;
                        return;
                    }
                    if (!Array.isArray(cities) || cities.length === 0) {
                        this.courierModal.open = false; // Close modal on empty
                        window.Toast.fire({ icon: 'warning', title: 'Setup Required', text: 'Pathao credentials not found or empty. Please check Courier Settings.' });
                    }
                    this.courierModal.cities = Array.isArray(cities) ? cities : [];
                    this.courierModal.loading = false;
                })
                .catch(err => {
                    this.courierModal.open = false; // Close modal on connection failure
                    this.courierModal.loading = false;
                    window.Toast.fire({ icon: 'error', title: 'Connection Failed', text: 'Could not connect to Pathao API.' });
                });
        } else {
            this.courierModal.loading = false;
        }
    },
    fetchPathaoZones(cityId) {
        if (!cityId) return;
        this.courierModal.loading = true;
        fetch('{{ route('admin.orders.courier-locations') }}?type=pathao_zones&city_id=' + cityId)
            .then(res => res.json())
            .then(zones => {
                this.courierModal.zones = zones;
                this.courierModal.loading = false;
            });
    },
    fetchPathaoAreas(zoneId) {
        if (!zoneId) return;
        this.courierModal.loading = true;
        fetch('{{ route('admin.orders.courier-locations') }}?type=pathao_areas&zone_id=' + zoneId)
            .then(res => res.json())
            .then(areas => {
                this.courierModal.areas = areas;
                this.courierModal.loading = false;
            });
    },
    submitBulkCourierWithArea() {
        const type = this.courierModal.type;
        const form = document.getElementById('bulk-action-form');
        if (!form) return alert('Form not found!');
        
        form.action = '/admin/orders-bulk-courier';
        
        // Clear previous hidden inputs
        const oldInputs = form.querySelectorAll('input[type=\'hidden\']:not(#bulk-action-ids, input[name=\'_token\'])');
        oldInputs.forEach(i => i.remove());

        // Add selected IDs
        document.getElementById('bulk-action-ids').value = JSON.stringify(this.selected);
        
        // Add Courier Type
        const courierInput = document.createElement('input');
        courierInput.type = 'hidden'; courierInput.name = 'courier_type'; courierInput.value = type;
        form.appendChild(courierInput);

        if (type === 'steadfast') {
            form.submit();
            return;
        }

        if (type === 'redx' && !this.courierModal.selectedArea) {
            return window.Toast.fire({ icon: 'error', title: 'Missing Area', text: 'Please select a RedX area before confirming.' });
        }
        if (type === 'pathao' && (!this.courierModal.selectedCity || !this.courierModal.selectedZone || !this.courierModal.selectedArea)) {
            return window.Toast.fire({ icon: 'error', title: 'Missing Selection', text: 'Please select City, Zone and Area for Pathao.' });
        }

        // Add Area Data
        if (type === 'redx') {
            form.insertAdjacentHTML('beforeend', '<input type=\'hidden\' name=\'redx_area_id\' value=\'' + this.courierModal.selectedArea + '\'>');
        } else if (type === 'pathao') {
            form.insertAdjacentHTML('beforeend', '<input type=\'hidden\' name=\'pathao_city_id\' value=\'' + this.courierModal.selectedCity + '\'>');
            form.insertAdjacentHTML('beforeend', '<input type=\'hidden\' name=\'pathao_zone_id\' value=\'' + this.courierModal.selectedZone + '\'>');
            form.insertAdjacentHTML('beforeend', '<input type=\'hidden\' name=\'pathao_area_id\' value=\'' + this.courierModal.selectedArea + '\'>');
        }

        form.submit();
    },
    bulkDelete() {
        if (this.selected.length === 0) return alert('Please select orders first');
        if (confirm('Are you sure you want to delete ' + this.selected.length + ' orders?')) {
            document.getElementById('bulk-action-ids').value = JSON.stringify(this.selected);
            document.getElementById('bulk-action-form').action = '{{ route('admin.orders.bulk-delete') }}';
            document.getElementById('bulk-action-form').submit();
        }
    },
    bulkStatus(status) {
        if (this.selected.length === 0) return alert('Please select orders first');
        if (confirm('Update ' + this.selected.length + ' orders to ' + status + '?')) {
            document.getElementById('bulk-action-ids').value = JSON.stringify(this.selected);
            document.getElementById('bulk-action-status').value = status;
            document.getElementById('bulk-action-form').action = '{{ route('admin.orders.bulk-status') }}';
            document.getElementById('bulk-action-form').submit();
        }
    },
    openNote(id, type, val) {
        this.noteModal.orderId = id;
        this.noteModal.type = type;
        this.noteModal.value = val;
        this.noteModal.open = true;
    },
    bulkPrint() {
        if (this.selected.length === 0) {
            window.print();
            return;
        }
        // Add a temporary class to body to indicate selective printing
        document.body.classList.add('print-selected-only');
        window.print();
        // Remove the class after print dialog closes
        setTimeout(() => {
            document.body.classList.remove('print-selected-only');
        }, 500);
    },
    printInvoices() {
        if (this.selected.length === 0) {
            alert('Please select at least one order to print invoices.');
            return;
        }
        const ids = this.selected.join(',');
        window.open(`/admin/orders-bulk-invoices?ids=${ids}`, '_blank');
    }
}">
    <div></div>

    <div class="bg-white p-4 rounded shadow-sm border border-gray-100 mb-6 mx-2 no-print">
        <div class="flex flex-wrap items-center justify-between gap-6">
            <div class="flex flex-wrap items-center gap-5">
                <span class="text-[10px] text-gray-900 font-black uppercase tracking-widest hidden lg:block">Actions:</span>
                
                <button onclick="location.reload()" class="w-9 h-9 bg-indigo-50 text-indigo-600 rounded flex items-center justify-center hover:bg-indigo-600 hover:text-white transition shadow-sm border border-indigo-100" title="Reload Page">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                </button>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="px-5 py-1.5 bg-[#4f46e5] text-white rounded text-[12px] font-bold flex items-center gap-1.5 hover:bg-indigo-700 transition shadow-sm">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i> Status
                    </button>
                    <div x-show="open" x-cloak @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-white rounded capitalize font-medium z-[3000]">
                        @foreach(['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'returned'] as $status)
                            <button @click="bulkStatus('{{ $status }}'); open = false;" class="w-full text-left px-4 py-2.5 hover:bg-indigo-50 hover:text-indigo-600 transition-colors capitalize font-medium">{{ $status }}</button>
                        @endforeach
                    </div>
                </div>

                <button @click="bulkDelete()" class="px-5 py-1.5 bg-[#e11d48] text-white rounded text-[12px] font-bold flex items-center gap-1.5 hover:bg-rose-700 transition shadow-sm">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete
                </button>
                
                <button @click="bulkPrint()" class="px-5 py-1.5 bg-[#0ea5e9] text-white rounded text-[12px] font-bold flex items-center gap-1.5 hover:bg-sky-600 transition shadow-sm">
                    <i data-lucide="printer" class="w-3.5 h-3.5"></i> Print
                </button>

                <button @click="printInvoices()" class="px-5 py-1.5 bg-[#6366f1] text-white rounded text-[12px] font-bold flex items-center gap-1.5 hover:bg-indigo-600 transition shadow-sm">
                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i> Invoice
                </button>

                <button class="px-5 py-1.5 bg-[#64748b] text-white rounded text-[12px] font-bold flex items-center gap-1.5 hover:bg-slate-700 transition shadow-sm">
                    <i data-lucide="tag" class="w-3.5 h-3.5 text-white"></i> Label
                </button>
                
                <span class="text-[10px] text-gray-900 font-black uppercase tracking-widest hidden lg:block ml-16">Couriers:</span>
                <button @click="openCourierModal('steadfast')" 
                    class="px-4 py-1.5 bg-[#22d3ee] text-white rounded text-[12px] font-bold hover:bg-cyan-600 transition shadow-sm border-none cursor-pointer">Steadfast</button>
                <button @click="openCourierModal('pathao')" 
                    class="px-4 py-1.5 bg-[#f59e0b] text-white rounded text-[12px] font-bold hover:bg-amber-600 transition shadow-sm border-none cursor-pointer">Pathao</button>
                <button @click="openCourierModal('redx')" 
                    class="px-4 py-1.5 bg-[#ea580c] text-white rounded text-[12px] font-bold hover:bg-orange-600 transition shadow-sm border-none cursor-pointer">RedX</button>
            </div>

            <!-- Hidden Form for Bulk Actions -->
            <form id="bulk-action-form" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="ids" id="bulk-action-ids">
            </form>

            <div class="flex items-center gap-5">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="px-4 py-1.5 bg-white border border-gray-200 text-gray-700 rounded text-[12px] font-bold flex items-center gap-1.5 hover:bg-gray-50 transition shadow-sm">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i> Date Filter
                    </button>
                    <div x-show="open" x-cloak @click.away="open = false" class="absolute right-0 mt-2 w-56 bg-white rounded shadow-2xl border border-gray-100 z-[3000] py-2 text-[12px]">
                        <button class="w-full text-left px-4 py-2 hover:bg-gray-50 transition">Today</button>
                        <button class="w-full text-left px-4 py-2 hover:bg-gray-50 transition">Yesterday</button>
                        <button class="w-full text-left px-4 py-2 hover:bg-gray-50 transition">Last 7 Days</button>
                        <button class="w-full text-left px-4 py-2 hover:bg-gray-50 transition">This Month</button>
                        
                        <div class="px-4 py-3 border-t border-gray-100 mt-2">
                            <span class="block text-[10px] text-gray-400 uppercase font-black mb-2 tracking-widest">Custom Range:</span>
                            <form action="{{ route('admin.orders.index') }}" method="GET" class="space-y-2">
                                <div class="space-y-1">
                                    <label class="text-[9px] font-bold text-gray-500 uppercase">From:</label>
                                    <input type="date" name="start_date" class="w-full border border-gray-200 rounded px-2 py-1 text-[11px] focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[9px] font-bold text-gray-500 uppercase">To:</label>
                                    <input type="date" name="end_date" class="w-full border border-gray-200 rounded px-2 py-1 text-[11px] focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                </div>
                                <button type="submit" class="w-full bg-indigo-600 text-white rounded py-1.5 text-[11px] font-bold hover:bg-indigo-700 transition mt-1 shadow-sm shadow-indigo-200">
                                    Apply Filter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.pos.index') }}" class="bg-[#e11d48] text-white px-5 py-1.5 rounded flex items-center gap-2 text-[12px] font-bold hover:bg-rose-700 transition shadow-sm">
                    <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i> POS Create
                </a>
                <form action="{{ route('admin.orders.index') }}" method="GET">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            x-on:input.debounce.500ms="$el.form.submit()"
                            placeholder="Search Order..." 
                            class="border border-gray-200 rounded pl-4 pr-10 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 w-80 transition-all">
                        <i data-lucide="search" class="w-4 h-4 absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Hidden Bulk Action Form -->
    <form id="bulk-action-form" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="ids" id="bulk-action-ids">
        <input type="hidden" name="status" id="bulk-action-status">
    </form>

    <!-- Table Card -->
    <div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden mx-2">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[1500px]">
                <thead class="bg-gray-200">
                    <tr class="border-b border-gray-300">
                        <th class="px-4 py-4 text-center w-12 no-print">
                            <input type="checkbox" @click="toggleAll()" :checked="selectAll" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                        </th>
                        <th class="px-2 py-4 text-center text-[10px] font-black text-gray-900 uppercase tracking-widest border-b border-gray-100 no-print">ACTION</th>
                        <th class="px-2 py-4 text-left text-[10px] font-black text-gray-900 uppercase tracking-widest border-b border-gray-100">INVOICE</th>
                        <th class="px-4 py-4 text-left text-[10px] font-black text-gray-900 uppercase tracking-widest border-b border-gray-100">DATE</th>
                        <th class="px-4 py-4 text-left text-[10px] font-black text-gray-900 uppercase tracking-widest border-b border-gray-100">CUSTOMER NAME</th>
                        <th class="px-4 py-4 text-left text-[10px] font-black text-gray-900 uppercase tracking-widest border-b border-gray-100">IP ADDRESS</th>
                        <th class="px-4 py-4 text-left text-[10px] font-black text-gray-900 uppercase tracking-widest border-b border-gray-100">ORDER NOTE</th>
                        <th class="px-4 py-4 text-left text-[10px] font-black text-gray-900 uppercase tracking-widest border-b border-gray-100">ADMIN NOTE</th>
                        <th class="px-4 py-4 text-left text-[10px] font-black text-gray-900 uppercase tracking-widest border-b border-gray-100">AMOUNT</th>
                        <th class="px-4 py-4 text-left text-[10px] font-black text-gray-900 uppercase tracking-widest border-b border-gray-100">STATUS</th>
                        <th class="px-4 py-4 text-left text-[10px] font-black text-gray-900 uppercase tracking-widest border-b border-gray-100">COURIER</th>
                        <th class="px-4 py-4 text-left text-[10px] font-black text-gray-900 uppercase tracking-widest border-b border-gray-100 no-print">TRACK</th>
                        <th class="px-4 py-4 text-left text-[10px] font-black text-gray-900 uppercase tracking-widest border-b border-gray-100 no-print">FRAUD CHECK</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($orders as $index => $order)
                    @php 
                        $isFraud = $order->total > 10000 || $order->phone_history_count > 2; // Mock logic
                        $isNewCust = !$order->customer_id;
                    @endphp
                    <tr :class="selected.includes('{{ $order->id }}') ? 'selected-for-print' : ''" 
                        class="hover:bg-indigo-50/50 even:bg-gray-50 border-b-2 border-gray-200 transition-colors group">
                        <td class="px-4 py-8 text-center no-print">
                            <input type="checkbox" value="{{ $order->id }}" x-model="selected" class="order-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                        </td>
                        <td class="px-2 py-8 text-[12px] font-bold text-gray-400 no-print">{{ ($orders->currentPage() - 1) * $orders->perPage() + $index + 1 }}</td>
                        <td class="px-4 py-8 no-print">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.orders.show', $order) }}" class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition shadow-sm" title="View"><i data-lucide="eye" class="w-4 h-4"></i></a>
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="w-8 h-8 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition shadow-sm" title="Quick Status">
                                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-cloak class="absolute left-0 mt-2 w-40 bg-white rounded shadow-2xl border border-gray-100 z-[2000] py-2">
                                        @foreach(['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'returned'] as $st)
                                            <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="{{ $st }}">
                                                <button type="submit" class="w-full text-left px-4 py-2 text-[12px] font-bold hover:bg-indigo-50 hover:text-indigo-600 transition capitalize">{{ $st }}</button>
                                            </form>
                                        @endforeach
                                    </div>
                                </div>
                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Delete this order?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-600 hover:text-white transition shadow-sm" title="Delete"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </form>
                            </div>
                        </td>
                        <td class="px-4 py-8">
                            <div class="flex flex-col">
                                <span class="text-[13px] font-black text-indigo-600 group-hover:underline cursor-pointer">#{{ $order->order_number }}</span>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $order->payment_method ?? 'COD' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-8">
                            <div class="flex flex-col gap-0.5">
                                <div class="flex items-center gap-1.5 text-gray-900">
                                    <i data-lucide="calendar" class="w-3 h-3 text-indigo-500"></i>
                                    <span class="text-[12px] font-black tracking-tighter">{{ $order->created_at->format('d M, Y') }}</span>
                                </div>
                                <div class="flex items-center gap-1 text-gray-400 pl-4.5">
                                    <span class="text-[10px] font-bold">{{ $order->created_at->format('h:i A') }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-8">
                            <div class="flex flex-col">
                                <div class="text-[13px] font-black text-gray-900 flex items-center gap-1.5">
                                    {{ $order->name }}
                                    @if($isNewCust)
                                        <span class="w-2 h-2 rounded-full bg-green-500" title="New Customer"></span>
                                    @endif
                                </div>
                                <div class="text-[11px] text-gray-500 font-bold flex items-center gap-1">
                                    <i data-lucide="phone" class="w-3 h-3"></i> {{ $order->phone }}
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-8">
                            <div class="flex flex-col gap-1.5">
                                <span class="text-[11px] font-mono font-bold text-gray-400 bg-gray-50 px-1.5 py-0.5 rounded w-fit">{{ $order->ip_address ?? '127.0.0.1' }}</span>
                                @if($order->ip_address)
                                    <form action="{{ route('admin.ip-blocks.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="ip_address" value="{{ $order->ip_address }}">
                                        <button type="submit" class="bg-rose-500 text-white text-[9px] px-2 py-0.5 rounded flex items-center justify-center gap-1 w-fit hover:bg-rose-600 transition font-black uppercase tracking-tighter">
                                            <i data-lucide="shield-off" class="w-2.5 h-2.5"></i> Block IP
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-8 text-center">
                            <button @click="openNote('{{ $order->id }}', 'notes', {{ json_encode($order->notes) }})" 
                                    class="text-[10px] font-black px-3 py-1.5 rounded transition border shadow-sm
                                    {{ $order->notes ? 'bg-sky-50 text-sky-600 border-sky-200' : 'bg-white border-gray-200 text-gray-400 hover:bg-gray-50' }}">
                                <i data-lucide="{{ $order->notes ? 'file-text' : 'plus' }}" class="w-3 h-3 inline mr-1"></i>
                                {{ $order->notes ? 'View' : 'Add' }}
                            </button>
                        </td>
                        <td class="px-4 py-8 text-center">
                            <button @click="openNote('{{ $order->id }}', 'admin_note', {{ json_encode($order->admin_note) }})" 
                                    class="text-[10px] font-black px-3 py-1.5 rounded transition border shadow-sm
                                    {{ $order->admin_note ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-white border-gray-200 text-gray-400 hover:bg-gray-50' }}">
                                <i data-lucide="{{ $order->admin_note ? 'shield-check' : 'plus' }}" class="w-3 h-3 inline mr-1"></i>
                                {{ $order->admin_note ? 'View' : 'Add' }}
                            </button>
                        </td>
                        <td class="px-4 py-8 text-right pr-20">
                            <div class="text-[14px] font-black text-gray-900">৳{{ number_format($order->total) }}</div>
                            <div class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">{{ $order->order_items_count ?? 1 }} Items</div>
                        </td>
                        <td class="px-4 py-8 pl-56">
                            <div class="flex">
                                <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded tracking-tighter shadow-sm w-20 text-center inline-block
                                    {{ $order->status == 'pending' ? 'bg-orange-100 text-orange-600 border border-orange-200' : '' }}
                                    {{ $order->status == 'confirmed' ? 'bg-blue-100 text-blue-600 border border-blue-200' : '' }}
                                    {{ $order->status == 'processing' ? 'bg-yellow-100 text-yellow-600 border border-yellow-200' : '' }}
                                    {{ $order->status == 'shipped' ? 'bg-indigo-100 text-indigo-600 border border-indigo-200' : '' }}
                                    {{ $order->status == 'delivered' ? 'bg-green-100 text-green-600 border border-green-200' : '' }}
                                    {{ $order->status == 'cancelled' ? 'bg-rose-100 text-rose-600 border border-rose-200' : '' }}
                                    {{ $order->status == 'returned' ? 'bg-slate-100 text-slate-600 border border-slate-200' : '' }}">
                                    {{ $order->status }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-8">
                            <div class="flex flex-col">
                                <span class="text-[11px] text-gray-700 font-black">{{ $order->courier_name ?? 'Not Set' }}</span>
                                <span class="text-[10px] text-gray-400 font-bold">{{ $order->city ?? 'Dhaka' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-8 text-center pl-32">
                            <div class="flex">
                                @if($order->courier_tracking_id)
                                    @php
                                        $trackingUrl = '#';
                                        if (strtolower($order->courier_name) == 'steadfast') {
                                            $trackingUrl = "https://portal.steadfast.com.bd/tracking/" . $order->courier_tracking_id;
                                        } elseif (strtolower($order->courier_name) == 'redx') {
                                            $trackingUrl = "https://redx.com.bd/track-consignment?trackingId=" . $order->courier_tracking_id;
                                        } elseif (strtolower($order->courier_name) == 'pathao') {
                                            $trackingUrl = "https://merchant.pathao.com/tracking/" . $order->courier_tracking_id;
                                        }
                                    @endphp
                                    <a href="{{ $trackingUrl }}" target="_blank" class="bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-lg flex items-center justify-center gap-1.5 hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/20">
                                        <i data-lucide="map-pinned" class="w-3 h-3 text-white"></i> Track
                                    </a>
                                @else
                                    <button class="bg-gray-50 text-gray-300 text-[10px] font-black px-4 py-1.5 rounded-lg flex items-center justify-center gap-1.5 border border-gray-100 cursor-not-allowed">
                                        <i data-lucide="map-pin-off" class="w-3 h-3"></i> Track
                                    </button>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-8">
                            <div x-data="{ checking: false, checked: false, fraudData: null }">
                                <!-- Check Button -->
                                <button x-show="!checked" 
                                        @click="checking = true; 
                                                fetch(`{{ route('admin.orders.fraud-check', $order) }}`)
                                                .then(res => res.json())
                                                .then(data => {
                                                    checking = false; 
                                                    if(data.success) {
                                                        checked = true;
                                                        fraudData = data;
                                                    } else {
                                                        alert(data.message || 'API Error');
                                                    }
                                                })
                                                .catch(err => {
                                                    checking = false;
                                                    alert('Could not connect to server.');
                                                })" 
                                        :disabled="checking"
                                        class="bg-indigo-50 text-indigo-600 text-[10px] font-black px-3 py-1.5 rounded border border-indigo-100 hover:bg-indigo-600 hover:text-white transition flex items-center gap-1.5 shadow-sm disabled:opacity-50">
                                    <template x-if="checking">
                                        <i data-lucide="refresh-cw" class="w-3 h-3 animate-spin text-indigo-600 group-hover:text-white"></i>
                                    </template>
                                    <template x-if="!checking">
                                        <i data-lucide="shield-check" class="w-3 h-3"></i>
                                    </template>
                                    <span x-text="checking ? 'Checking...' : 'Check Fraud'"></span>
                                </button>
                                
                                <!-- Result Display (Compact) -->
                                <div x-show="checked" x-cloak class="flex items-center gap-1 animate-in fade-in zoom-in duration-300">
                                    <div class="flex items-center bg-emerald-50 border border-emerald-100 px-1.5 py-0.5 rounded shadow-sm">
                                        <span class="text-[9px] font-black text-emerald-600 uppercase" x-text="'S:' + fraudData?.rate + '%'"></span>
                                    </div>
                                    <div class="flex items-center bg-rose-50 border border-rose-100 px-1.5 py-0.5 rounded shadow-sm">
                                        <span class="text-[9px] font-black text-rose-600 uppercase" x-text="'R:' + fraudData?.return_count"></span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Note Modal -->
    <div x-show="noteModal.open" x-cloak class="fixed inset-0 z-[5000] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="noteModal.open = false"></div>
        <div class="bg-white rounded-2xl w-full max-w-md relative z-10 shadow-2xl border border-gray-100 overflow-hidden" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-800" x-text="noteModal.type === 'notes' ? 'Order Note' : 'Admin Note'"></h3>
                <button @click="noteModal.open = false" class="text-gray-400 hover:text-gray-600 transition-colors"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <form :action="'/admin/orders/' + noteModal.orderId + '/notes'" method="POST" class="p-6">
                @csrf
                <textarea :name="noteModal.type" x-model="noteModal.value" rows="5" class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm p-4 transition-shadow" placeholder="Type your note here..."></textarea>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="noteModal.open = false" class="px-5 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100 rounded transition">Cancel</button>
                    <button type="submit" class="px-8 py-2 text-sm font-bold bg-[#4f46e5] text-white rounded-lg shadow-lg shadow-indigo-600/20 hover:bg-indigo-700 transition">Save Note</button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-6 flex flex-col md:flex-row items-center justify-between gap-4 px-2">
        <div class="text-xs text-gray-400 font-medium">
            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
        </div>
        <div class="pagination-custom">
            {{ $orders->links() }}
        </div>
    </div>
    <!-- Courier Selection Modal -->
    <div x-show="courierModal.open" 
         class="fixed inset-0 z-[2000] flex items-center justify-center p-4 bg-black/60"
         x-cloak>
        <div class="bg-white w-full max-w-md rounded-none shadow-2xl overflow-hidden" @click.away="courierModal.open = false">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                <h3 class="text-base font-black text-gray-900 uppercase tracking-tight flex items-center gap-2">
                    <i data-lucide="truck" class="w-5 h-5 text-indigo-600"></i>
                    Send to <span x-text="courierModal.type.toUpperCase()"></span>
                </h3>
                <button @click="courierModal.open = false" class="text-gray-400 hover:text-gray-600 border-none bg-transparent cursor-pointer">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <div class="p-6 space-y-4">
                <template x-if="courierModal.type === 'steadfast'">
                    <p class="text-sm text-gray-600 font-medium">Sending <span class="font-bold text-indigo-600" x-text="selected.length"></span> orders to Steadfast. No additional location info required.</p>
                </template>

                <template x-if="courierModal.type === 'redx'">
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-gray-700 uppercase">Select Delivery Area</label>
                        <select x-model="courierModal.selectedArea" class="w-full border border-gray-200 p-2.5 text-sm rounded-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Choose Area...</option>
                            <template x-for="area in courierModal.areas" :key="area.id">
                                <option :value="area.id" x-text="area.name"></option>
                            </template>
                        </select>
                    </div>
                </template>

                <template x-if="courierModal.type === 'pathao'">
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase">Select City</label>
                            <select x-model="courierModal.selectedCity" @change="fetchPathaoZones($event.target.value)" class="w-full border border-gray-200 p-2.5 text-sm rounded-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Choose City...</option>
                                <template x-for="city in courierModal.cities" :key="city.city_id || city.id">
                                    <option :value="city.city_id || city.id" x-text="city.city_name || city.name"></option>
                                </template>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase">Select Zone</label>
                            <select x-model="courierModal.selectedZone" @change="fetchPathaoAreas($event.target.value)" class="w-full border border-gray-200 p-2.5 text-sm rounded-none focus:ring-2 focus:ring-indigo-500" :disabled="!courierModal.selectedCity">
                                <option value="">Choose Zone...</option>
                                <template x-for="zone in courierModal.zones" :key="zone.zone_id || zone.id">
                                    <option :value="zone.zone_id || zone.id" x-text="zone.zone_name || zone.name"></option>
                                </template>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase">Select Area</label>
                            <select x-model="courierModal.selectedArea" class="w-full border border-gray-200 p-2.5 text-sm rounded-none focus:ring-2 focus:ring-indigo-500" :disabled="!courierModal.selectedZone">
                                <option value="">Choose Area...</option>
                                <template x-for="area in courierModal.areas" :key="area.area_id || area.id">
                                    <option :value="area.area_id || area.id" x-text="area.area_name || area.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </template>

                <div x-show="courierModal.loading" class="text-center py-4">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-indigo-500 border-t-transparent"></div>
                    <p class="text-xs font-bold text-indigo-600 mt-2 uppercase tracking-widest">Loading Locations...</p>
                </div>
            </div>

            <div class="p-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button @click="courierModal.open = false" class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700 uppercase tracking-wider bg-transparent border-none cursor-pointer">Cancel</button>
                <button @click="submitBulkCourierWithArea()" 
                        class="px-6 py-2 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 border-none cursor-pointer">
                    Confirm & Dispatch
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .pagination-custom nav { display: flex; gap: 0.25rem; }
    @media print {
        header, .sidebar, .action-buttons-row, .search-row, .pagination-custom, .fixed-top { display: none !important; }
        .table-card { border: none !important; box-shadow: none !important; margin: 0 !important; width: 100% !important; }
        body { background: white !important; padding: 0 !important; }
    }
</style>

    <script>
        // Global functions removed as they are now Alpine methods
    </script>
@endsection
