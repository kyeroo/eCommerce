let currentPage = 1;
const itemsPerPage = 10;

function getCart(){ 
    return JSON.parse(localStorage.getItem('cart') || '[]'); 
}

function saveCart(cart){ 
    localStorage.setItem('cart', JSON.stringify(cart)); 
    updateCartCount(); 
}

function updateCartCount(){ 
    const el=document.getElementById('cartCount'); 

    if(el){ 
        el.textContent=getCart().reduce((a,i)=>a+i.qty,0); 
    } 
}

function addToCart(product){ 
     if(!isLoggedIn){

        Swal.fire({
            icon: "info",
            title: "Login Diperlukan",
            html: `
                Untuk menambahkan produk ke keranjang,
                silakan login terlebih dahulu.
            `,
            confirmButtonText: "Login Sekarang",
            showCancelButton: true,
            cancelButtonText: "Batal",
            confirmButtonColor:"#2563eb"
        }).then((result)=>{
            if(result.isConfirmed){
                window.location.href="auth/login.php";
            }
        });

        return;
    }


    const cart=getCart(); const idx=cart.findIndex(i=>i.id===product.id); 
    
    if(idx>=0){ 
        cart[idx].qty += 1; 
    } else { 
        cart.push({...product, qty:1}); 
    } 
    
    saveCart(cart); 
    Swal.fire({
        toast: true,
        position: "top-end",
        icon: "success",
        title: "Produk berhasil ditambahkan!",
        html: `
            <div style="font-size:15px;color:#6b7280">
                Produk telah masuk ke keranjang belanja.
            </div>
        `,
        showConfirmButton: false,
        timer: 2200,
        timerProgressBar: true,
        customClass:{
            popup:"cart-toast",
            title:"cart-toast-title"
        }
    });
}

function removeCart(id){ 
    saveCart(getCart().filter(i=>i.id!==id)); 
    renderCart(); 
}

function changeQty(id,qty){ 
    const cart=getCart().map(i=>i.id===id?{...i,qty:Math.max(1,parseInt(qty)||1)}:i); 
    saveCart(cart); 
    renderCart(); 
}

function rupiah(n){ 
    return 'Rp ' + Number(n).toLocaleString('id-ID'); 
}

function renderCart() {
    const body = document.getElementById("cartBody");
    const emptyCart = document.getElementById("emptyCart");
    const cartLayout = document.getElementById("cartLayout");
    const totalEl = document.getElementById('cartTotal');
    const input = document.getElementById('cartPayload');
    const summaryItems = document.getElementById("summaryItems");
    const summarySubtotal = document.getElementById("summarySubtotal");

    if (!body) return;

    const cart = getCart() || [];

    // Handling jika keranjang kosong
    if (cart.length === 0) {
        if (emptyCart) emptyCart.style.display = "flex";
        if (cartLayout) cartLayout.style.display = "none";
        return;
    }

    // Handling jika ada isi
    if (emptyCart) emptyCart.style.display = "none";
    if (cartLayout) cartLayout.style.display = "grid";

    body.innerHTML = '';
    if (summaryItems) summaryItems.innerHTML = '';

    let subtotal = 0;

    // Render ringkasan & hitung total
    cart.forEach(item => {
        const sub = item.price * item.qty;
        subtotal += sub;

        if (summaryItems) {
            summaryItems.innerHTML += `
                <div class="summary-item">
                    <span>${item.name}</span>
                    <span>${rupiah(sub)}</span>
                </div>
            `;
        }
    });

    // Update total harga ke DOM
    const formattedTotal = rupiah(subtotal);
    if (summarySubtotal) summarySubtotal.textContent = formattedTotal;
    if (totalEl) totalEl.textContent = formattedTotal;
    if (input) input.value = JSON.stringify(cart);

    // Pagination & Render item keranjang
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageItems = cart.slice(start, end);

    pageItems.forEach(item => {
        const sub = item.price * item.qty;
        
        // Memastikan ID dibungkus kutip jika berbentuk string
        const itemIdParam = typeof item.id === 'string' ? `'${item.id}'` : item.id;

        body.innerHTML += `
        <div class="cart-item">
            <img src="${item.image}" class="cart-image">
            <div class="cart-content">
                <h5>${item.name}</h5>
                <p>${rupiah(item.price)}</p>
                <div class="cart-footer">
                    <div class="qty-control">
                        <button onclick="changeQty(${itemIdParam}, ${item.qty - 1})">-</button>
                        <span>${item.qty}</span>
                        <button onclick="changeQty(${itemIdParam}, ${item.qty + 1})">+</button>
                    </div>
                    <div>
                        <h5>${rupiah(sub)}</h5>
                    </div>
                </div>
            </div>
            <button class="delete-btn" onclick="removeCart(${itemIdParam})">
                <i class="bi bi-trash"></i>
            </button>
        </div>`;
    });

    renderPagination(cart.length);
}

function renderPagination(totalItems) {
    const pagination = document.getElementById('cartPagination');
    if (!pagination) return;

    const totalPages = Math.ceil(totalItems / itemsPerPage);

    if (totalPages <= 1) {
        pagination.innerHTML = '';
        return;
    }

    let html = `<div class="pagination-modern">`;

    html += `
        <button
            class="pagination-arrow"
            ${currentPage === 1 ? 'disabled' : ''}
            onclick="changePage(${currentPage - 1})">
            <i class="bi bi-chevron-left"></i>
        </button>
    `;

    for (let i = 1; i <= totalPages; i++) {
        html += `
            <button
                class="pagination-btn ${currentPage === i ? 'active' : ''}"
                onclick="changePage(${i})">
                ${i}
            </button>
        `;
    }

    html += `
        <button
            class="pagination-arrow"
            ${currentPage === totalPages ? 'disabled' : ''}
            onclick="changePage(${currentPage + 1})">
            <i class="bi bi-chevron-right"></i>
        </button>
    `;

    html += `</div>`;

    pagination.innerHTML = html;
}

function changePage(page) {
    currentPage = page;
    renderCart();
}

document.addEventListener('DOMContentLoaded',()=>{ updateCartCount(); 
    renderCart(); });
