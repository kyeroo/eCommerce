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
    alert('Produk masuk ke keranjang.'); 
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
    const body = document.getElementById('cartBody');
    const totalEl = document.getElementById('cartTotal');
    const input = document.getElementById('cartPayload');
    const summaryItems = document.getElementById("summaryItems");

    summaryItems.innerHTML = "";
    let subtotal = 0;

    if (!body) return;

    const cart = getCart();
    let total = 0;

    body.innerHTML = '';

    // Hitung total semua item
    cart.forEach(item => {
        total += item.price * item.qty;
    });

    cart.forEach(item=>{

    const sub = item.price * item.qty;

    subtotal += sub;

    summaryItems.innerHTML += `

    <div class="summary-item">

        <span class="name">

            ${item.name} × ${item.qty}

        </span>

        <span class="price">

            ${rupiah(sub)}

        </span>

    </div>

    `;

});

document.getElementById("summarySubtotal").textContent = rupiah(subtotal);

document.getElementById("cartTotal").textContent = rupiah(subtotal);

    // Pagination
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageItems = cart.slice(start, end);

    pageItems.forEach(item => {
        const sub = item.price * item.qty;

        body.innerHTML += `
        <div class="cart-item">

            <img src="${item.image}" class="cart-image">

            <div class="cart-content">

                <h5>${item.name}</h5>

                <p>${rupiah(item.price)}</p>

                <div class="cart-footer">

                    <div class="qty-control">

                        <button onclick="changeQty(${item.id},${item.qty-1})">

                            -

                        </button>

                        <span>${item.qty}</span>

                        <button onclick="changeQty(${item.id},${item.qty+1})">

                            +

                        </button>

                    </div>

                    <div>

                        <h5>${rupiah(sub)}</h5>
                    </div>

                </div>

            </div>

            <button class="delete-btn"
                onclick="removeCart(${item.id})">

                <i class="bi bi-trash"></i>

            </button>

        </div>`;
    });

    if (cart.length === 0) {
        body.innerHTML = `
        <tr>
            <td colspan="5" class="text-center">
                Keranjang masih kosong.
            </td>
        </tr>`;
    }

    if (totalEl) totalEl.textContent = rupiah(total);

    if (input) input.value = JSON.stringify(cart);

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
