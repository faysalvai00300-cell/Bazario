@extends('layouts.admin')
@section('title', 'Point of Sale')
@section('content')
<style>
#pos-app { display:flex; flex-direction:column; margin:-1.5rem -1rem -1rem; overflow:hidden; background:#f5f5f6; }
#pos-topbar { display:flex; align-items:center; justify-content:space-between; padding:10px 20px; background:#fff; border-bottom:2px solid #f0f0f0; flex-shrink:0; }
#pos-body { display:flex; flex:1; overflow:hidden; }
#pos-catalog { flex:1; display:flex; flex-direction:column; overflow:hidden; }
#pos-search-bar { display:flex; gap:10px; padding:12px 16px; background:#fff; border-bottom:1px solid #efefef; flex-shrink:0; }
#pos-search-bar input, #pos-search-bar select { border:1.5px solid #e5e7eb; border-radius:8px; padding:8px 12px; font-size:13px; outline:none; background:#fafafa; }
#pos-search-bar input { flex:1; }
#pos-search-bar input:focus, #pos-search-bar select:focus { border-color:#ff7a1a; background:#fff; }
#pos-grid-wrap { flex:1; overflow-y:auto; padding:16px; }
#pos-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(155px,1fr)); gap:14px; }
.pos-card { background:#fff; border:2px solid #f0f0f0; border-radius:14px; overflow:hidden; cursor:pointer; transition:all 0.18s; position:relative; display:flex; flex-direction:column; }
.pos-card:hover { border-color:#ff7a1a; box-shadow:0 6px 20px rgba(255,122,26,0.18); transform:translateY(-3px); }
.pos-card.added { border-color:#22c55e; }
.pos-card .card-img { position:relative; }
.pos-card .card-img img { width:100%; aspect-ratio:1; object-fit:cover; display:block; }
.pos-card .badge-stock { position:absolute; top:7px; left:7px; font-size:9px; font-weight:800; padding:2px 7px; border-radius:20px; }
.pos-card .badge-added { position:absolute; top:7px; right:7px; width:22px; height:22px; background:#22c55e; border-radius:50%; display:flex; align-items:center; justify-content:center; }
.pos-card .card-body { padding:9px 11px 11px; flex:1; }
.pos-card .card-name { font-size:11.5px; font-weight:700; color:#1f2937; line-height:1.3; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.pos-card .card-sku { font-size:9px; color:#aaa; margin:2px 0 5px; font-weight:600; }
.pos-card .card-price { font-size:14px; font-weight:900; color:#ff7a1a; }
.pos-card .card-old { font-size:10px; color:#d1d5db; text-decoration:line-through; }
.pos-card .add-overlay { position:absolute; inset:0; background:rgba(255,122,26,0.92); display:flex; align-items:center; justify-content:center; opacity:0; transition:0.2s; border-radius:12px; }
.pos-card:hover .add-overlay { opacity:1; }
.pos-card .add-overlay span { color:#fff; font-size:11px; font-weight:900; letter-spacing:.08em; text-transform:uppercase; }
/* Cart Panel */
#pos-cart-panel { width:370px; flex-shrink:0; display:flex; flex-direction:column; background:#fff; border-left:2px solid #f0f0f0; overflow:hidden; }
#cart-header { display:flex; align-items:center; justify-content:space-between; padding:12px 16px; border-bottom:1px solid #f0f0f0; flex-shrink:0; }
#cart-customer { padding:10px 14px; border-bottom:1px solid #f0f0f0; flex-shrink:0; display:flex; flex-direction:column; gap:7px; }
#cart-customer input { width:100%; border:1.5px solid #e5e7eb; border-radius:8px; padding:8px 11px; font-size:12px; outline:none; box-sizing:border-box; transition:0.15s; }
#cart-customer input:focus { border-color:#ff7a1a; }
#cart-items { flex:1; overflow-y:auto; }
.cart-row { display:flex; align-items:center; gap:8px; padding:9px 14px; border-bottom:1px solid #f8f8f8; }
.qty-wrap { display:flex; align-items:center; background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; padding:2px; gap:1px; }
.qty-btn { width:25px; height:25px; border:1px solid #e5e7eb; border-radius:6px; background:#fff; font-size:15px; font-weight:900; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#374151; transition:.1s; }
.qty-btn:hover { background:#ff7a1a; color:#fff; border-color:#ff7a1a; }
#cart-summary { padding:14px 16px; border-top:2px solid #f0f0f0; background:#fafafa; flex-shrink:0; }
.sum-row { display:flex; justify-content:space-between; align-items:center; font-size:13px; padding:3px 0; }
.sum-row input { width:68px; text-align:right; border:none; border-bottom:1.5px solid #e5e7eb; background:transparent; font-size:13px; font-weight:700; outline:none; }
.grand-row { display:flex; justify-content:space-between; align-items:center; border-top:2px solid #efefef; padding-top:10px; margin-top:8px; }
.pay-methods { display:flex; gap:7px; margin:12px 0; }
.pay-btn { flex:1; padding:8px 0; border:2px solid #e5e7eb; border-radius:8px; font-size:11px; font-weight:800; text-transform:uppercase; background:#fff; cursor:pointer; color:#6b7280; transition:.15s; }
.pay-btn.active { background:#ff7a1a; border-color:#ff7a1a; color:#fff; }
.pay-btn:not(.active):hover { border-color:#ff7a1a; color:#ff7a1a; }
.complete-btn { width:100%; padding:13px; background:linear-gradient(135deg,#ff7a1a,#f97316); color:#fff; font-weight:900; font-size:14px; text-transform:uppercase; letter-spacing:.08em; border:none; border-radius:10px; cursor:pointer; transition:.2s; }
.complete-btn:hover:not(:disabled) { box-shadow:0 6px 20px rgba(255,122,26,.4); transform:translateY(-1px); }
.complete-btn:disabled { opacity:.45; cursor:not-allowed; }
/* Modal */
.modal-bg { position:fixed; inset:0; background:rgba(0,0,0,.55); backdrop-filter:blur(5px); z-index:9999; display:flex; align-items:center; justify-content:center; }
.modal-card { background:#fff; border-radius:20px; padding:32px 28px; max-width:340px; width:90%; text-align:center; box-shadow:0 20px 60px rgba(0,0,0,.2); }
</style>

<div id="pos-app" x-data="pos()" style="height:0;">

    {{-- Top Bar --}}
    <div id="pos-topbar">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:38px;height:38px;background:linear-gradient(135deg,#ff7a1a,#f97316);border-radius:11px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(255,122,26,.3)">
                <svg style="width:18px;height:18px;" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <div style="font-weight:900;font-size:14px;color:#111;">Point of Sale</div>
                <div style="font-size:10px;color:#9ca3af;font-weight:600;">Bazario POS Terminal</div>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:6px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:20px;padding:5px 12px;">
            <div style="width:7px;height:7px;background:#22c55e;border-radius:50%;"></div>
            <span style="font-size:11px;font-weight:700;color:#16a34a;">Live Session</span>
        </div>
    </div>

    <div id="pos-body">

        {{-- ---- LEFT: Products ---- --}}
        <div id="pos-catalog">
            <div id="pos-search-bar">
                <div style="position:relative;flex:1;">
                    <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:#9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/></svg>
                    <input type="text" x-model="search" @input.debounce.300ms="load()" placeholder="Search by name or SKU..." style="padding-left:32px;">
                </div>
                <select x-model="catId" @change="load()">
                    <option value="">All Categories</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                <div style="display:flex;align-items:center;background:#fff7ed;border:1px solid #fed7aa;border-radius:8px;padding:6px 12px;font-size:11px;font-weight:700;color:#ff7a1a;white-space:nowrap;">
                    <span x-text="products.length"></span>&nbsp;items
                </div>
            </div>

            <div id="pos-grid-wrap">
                <div id="pos-grid">
                    <template x-for="p in products" :key="p.id">
                        <div class="pos-card" :class="inCart(p.id)?'added':''" @click="add(p)">
                            <div class="card-img">
                                <img :src="p.thumbnail_url||'/placeholder.png'" :alt="p.name">
                                <span class="badge-stock"
                                    :style="p.stock>10?'background:#dcfce7;color:#16a34a':p.stock>0?'background:#fff7ed;color:#ea580c':'background:#fee2e2;color:#dc2626'"
                                    x-text="'Stk:'+p.stock"></span>
                                <div class="badge-added" x-show="inCart(p.id)">
                                    <svg style="width:11px;height:11px;" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="card-name" x-text="p.name"></div>
                                <div class="card-sku" x-text="'SKU: '+p.sku"></div>
                                <div style="display:flex;align-items:center;justify-content:space-between;">
                                    <div class="card-price" x-text="'৳'+p.effective_price.toLocaleString()"></div>
                                    <template x-if="p.sale_price>0&&p.sale_price<p.price">
                                        <div class="card-old" x-text="'৳'+p.price.toLocaleString()"></div>
                                    </template>
                                </div>
                            </div>
                            <div class="add-overlay"><span>＋ Add</span></div>
                        </div>
                    </template>
                    <template x-if="products.length===0">
                        <div style="grid-column:1/-1;text-align:center;padding:60px 0;color:#d1d5db;">
                            <svg style="width:44px;height:44px;margin:0 auto 10px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p style="font-weight:700;font-size:12px;text-transform:uppercase;letter-spacing:.1em;">No Products Found</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- ---- RIGHT: Cart ---- --}}
        <div id="pos-cart-panel">
            <div id="cart-header">
                <div style="display:flex;align-items:center;gap:8px;">
                    <svg style="width:16px;height:16px;color:#ff7a1a;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span style="font-weight:900;font-size:14px;color:#111;">Current Order</span>
                    <span x-show="cart.length>0" x-text="cart.length"
                        style="background:#ff7a1a;color:#fff;font-size:10px;font-weight:900;min-width:20px;height:20px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;padding:0 4px;"></span>
                </div>
                <button x-show="cart.length>0" @click="clear()" style="font-size:11px;font-weight:700;color:#ef4444;background:none;border:none;cursor:pointer;text-transform:uppercase;">✕ Clear</button>
            </div>

            <div id="cart-customer">
                <input type="text" x-model="cust.name" placeholder="Customer Name *">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">
                    <input type="text" x-model="cust.phone" placeholder="Phone *">
                    <input type="text" x-model="cust.address" placeholder="Address">
                </div>
            </div>

            <div id="cart-items">
                <template x-if="cart.length===0">
                    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:140px;color:#d1d5db;text-align:center;">
                        <svg style="width:40px;height:40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <p style="font-size:11px;font-weight:700;text-transform:uppercase;margin-top:8px;letter-spacing:.08em;">Cart is Empty</p>
                    </div>
                </template>
                <template x-for="(it,i) in cart" :key="it.id">
                    <div class="cart-row">
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:12px;font-weight:700;color:#111;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" x-text="it.name"></div>
                            <div style="font-size:11px;font-weight:900;color:#ff7a1a;" x-text="'৳'+it.price.toLocaleString()"></div>
                        </div>
                        <div class="qty-wrap">
                            <button class="qty-btn" @click="qty(i,-1)">−</button>
                            <span style="font-size:12px;font-weight:900;color:#111;padding:0 7px;" x-text="it.qty"></span>
                            <button class="qty-btn" @click="qty(i,1)">+</button>
                        </div>
                        <span style="font-size:12px;font-weight:900;color:#111;min-width:54px;text-align:right;" x-text="'৳'+(it.price*it.qty).toLocaleString()"></span>
                        <button @click="remove(i)" style="background:none;border:none;cursor:pointer;color:#d1d5db;padding:2px;" title="Remove">
                            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
            </div>

            <div id="cart-summary">
                <div class="sum-row"><span style="color:#6b7280;">Subtotal</span><span style="font-weight:700;" x-text="'৳'+sub().toLocaleString()"></span></div>
                <div class="sum-row"><span style="color:#6b7280;">Shipping</span><input type="number" x-model.number="shipping"></div>
                <div class="sum-row"><span style="color:#6b7280;">Discount</span><input type="number" x-model.number="discount" style="color:#ef4444;"></div>
                <div class="grand-row">
                    <span style="font-weight:900;font-size:15px;">Grand Total</span>
                    <span style="font-weight:900;font-size:20px;color:#ff7a1a;" x-text="'৳'+total().toLocaleString()"></span>
                </div>
                <div class="pay-methods">
                    <template x-for="m in ['cash','card','mobile']" :key="m">
                        <button class="pay-btn" :class="pay===m?'active':''" @click="pay=m" x-text="m"></button>
                    </template>
                </div>
                <button class="complete-btn" @click="done()" :disabled="cart.length===0||busy">
                    <span x-show="!busy">✓ Complete Sale</span>
                    <span x-show="busy">Processing...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Success Modal --}}
    <div class="modal-bg" x-show="ok" x-cloak x-transition style="display:none;">
        <div class="modal-card">
            <div style="width:60px;height:60px;background:linear-gradient(135deg,#22c55e,#16a34a);border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;box-shadow:0 6px 20px rgba(34,197,94,.35);">
                <svg style="width:30px;height:30px;" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h3 style="font-size:20px;font-weight:900;color:#111;margin:0 0 4px;">Sale Complete!</h3>
            <p style="font-size:13px;color:#6b7280;margin:0 0 4px;">Order placed successfully</p>
            <p style="font-size:18px;font-weight:900;color:#ff7a1a;margin:0 0 20px;" x-text="lastId"></p>
            <div style="display:flex;gap:10px;">
                <button @click="reset()" style="flex:1;padding:11px;background:#f3f4f6;border:none;border-radius:9px;font-weight:700;font-size:13px;cursor:pointer;">New Sale</button>
                <button @click="ok=false" style="flex:1;padding:11px;background:linear-gradient(135deg,#ff7a1a,#f97316);color:#fff;border:none;border-radius:9px;font-weight:700;font-size:13px;cursor:pointer;">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Fix height dynamically
document.addEventListener('DOMContentLoaded', function() {
    var el = document.getElementById('pos-app');
    if (el) {
        function setH() { el.style.height = (window.innerHeight - el.getBoundingClientRect().top) + 'px'; }
        setH();
        window.addEventListener('resize', setH);
    }
});

function pos() {
    return {
        products:[], cart:[], search:'', catId:'',
        shipping:0, discount:0, pay:'cash', busy:false, ok:false, lastId:'',
        cust:{name:'',phone:'',address:''},
        init(){ this.load(); },
        async load(){
            const r = await fetch(`{{ route('admin.pos.products') }}?search=${encodeURIComponent(this.search)}&category=${this.catId}`);
            this.products = await r.json();
        },
        inCart(id){ return this.cart.some(i=>i.id===id); },
        add(p){
            const i=this.cart.findIndex(x=>x.id===p.id);
            if(i>-1) this.cart[i].qty++;
            else this.cart.push({id:p.id,name:p.name,sku:p.sku,price:p.effective_price,qty:1});
        },
        remove(i){ this.cart.splice(i,1); },
        qty(i,d){ const q=this.cart[i].qty+d; if(q>0) this.cart[i].qty=q; else this.remove(i); },
        sub(){ return this.cart.reduce((s,i)=>s+i.price*i.qty,0); },
        total(){ return Math.max(0,this.sub()+this.shipping-this.discount); },
        clear(){ if(confirm('Clear order?')){ this.cart=[]; this.cust={name:'',phone:'',address:''}; this.shipping=0; this.discount=0; } },
        reset(){ this.cart=[]; this.cust={name:'',phone:'',address:''}; this.shipping=0; this.discount=0; this.ok=false; },
        async done(){
            if(!this.cust.name||!this.cust.phone){ alert('Please enter customer name and phone.'); return; }
            if(!this.cart.length) return;
            this.busy=true;
            try{
                const r=await fetch(`{{ route('admin.pos.store') }}`,{
                    method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                    body:JSON.stringify({customer_name:this.cust.name,customer_phone:this.cust.phone,customer_address:this.cust.address,items:this.cart.map(i=>({id:i.id,quantity:i.qty})),shipping_charge:this.shipping,discount:this.discount})
                });
                const d=await r.json();
                if(d.success){ this.lastId='#'+(d.order_id||'N/A'); this.ok=true; }
                else alert('Error: '+d.message);
            }catch(e){ alert('An error occurred.'); }
            finally{ this.busy=false; }
        }
    }
}
</script>
@endpush
@endsection
