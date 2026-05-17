<div x-data x-show="$store.auth.open" 
     @keydown.escape.window="$store.auth.open = false"
     x-cloak
     class="fixed inset-0 flex items-center justify-center p-4 transition-all duration-300"
     style="z-index: 99999999 !important;">
    
    <!-- Backdrop - Increased Blur for Heavier Effect -->
    <div x-show="$store.auth.open" 
         x-transition:enter="transition opacity duration-300"
         @click="$store.auth.open = false" 
         class="fixed inset-0 bg-black/40"
         style="z-index: 99999998 !important; 
                position: fixed; 
                top:0; left:0; width:100%; height:100%;
                backdrop-filter: blur(15px) !important;
                -webkit-backdrop-filter: blur(15px) !important;"></div>

    <!-- Modal Box -->
    <div x-show="$store.auth.open" 
         class="auth-modal-box bg-white shadow-2xl text-center"
         style="z-index: 99999999 !important; 
                position: relative; 
                width: 100% !important; 
                margin: 0 auto !important; 
                padding: 45px 30px !important; 
                border-radius: 9px !important;
                box-sizing: border-box !important;
                transform: translateY(-80px);
                border: 1px solid #efefef;">
        
        <!-- Close (X) -->
        <button @click="$store.auth.open = false" 
                style="position: absolute !important; 
                       top: 15px !important; 
                       right: 15px !important; 
                       background: none !important; 
                       border: none !important; 
                       cursor: pointer !important; 
                       color: #999 !important; 
                       padding: 5px !important;
                       display: block !important;">
            <svg style="width: 20px !important; height: 20px !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div style="margin-bottom: 25px !important; display: flex !important; justify-content: center !important;">
            <div style="width: 60px !important; height: 60px !important; background: #FEF2F2 !important; border-radius: 50% !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                <svg style="width: 32px !important; height: 32px !important; color: #EF4444 !important;" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.5 3c1.557 0 3.046.727 4 2.015Q12.454 3 14.5 3c2.786 0 5.25 2.322 5.25 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                </svg>
            </div>
        </div>

        <h3 style="font-size: 24px !important; font-weight: 900 !important; color: #111 !important; margin-bottom: 10px !important; font-family: sans-serif !important; letter-spacing: -0.5px;">Login Required</h3>
        <p style="font-size: 14px !important; color: #666 !important; margin-bottom: 35px !important; line-height: 1.5 !important; font-family: sans-serif !important; padding: 0 10px;">Login to save your favorite products directly to your wishlist.</p>

        <a href="{{ route('login') }}" 
           style="background-color: #1e293b !important; 
                  color: #fff !important; 
                  padding: 16px !important; 
                  border-radius: 9px !important; 
                  display: block !important; 
                  width: 100% !important; 
                  font-weight: 700 !important; 
                  text-decoration: none !important; 
                  text-transform: uppercase !important; 
                  font-size: 15px !important; 
                  box-sizing: border-box !important;
                  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
                  letter-spacing: 1px !important;
                  font-family: sans-serif !important;">
            Login Now
        </a>

        <div style="margin-top: 35px !important; padding-top: 20px !important; border-top: 1px solid #f2f2f2 !important; font-size: 13px !important; color: #333 !important; font-weight: 700 !important; text-transform: none !important; font-family: sans-serif !important;">
            Bazario Wishlist
        </div>
    </div>
</div>

<style>
.auth-modal-box {
    max-width: 320px !important;
}
@media (min-width: 768px) {
    .auth-modal-box {
        max-width: 500px !important;
    }
}
[x-cloak] { display: none !important; }
</style>
