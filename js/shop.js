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
    const cart=getCart(); 
    const idx=cart.findIndex(i=>i.id===product.id); 
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

function renderCart(){ 
    const body=document.getElementById('cartBody'); 
    const totalEl=document.getElementById('cartTotal'); 
    const input=document.getElementById('cartPayload'); 
    
    if(!body) return; 
    
    const cart=getCart(); 
    let total=0; body.innerHTML=''; 
    cart.forEach(item=>{ 
        const sub=item.price*item.qty; 
        total+=sub; 
        body.innerHTML += `<tr><td><img src="${item.image}" width="55"> ${item.name}</td>
        <td>${rupiah(item.price)}</td>
        <td><input class="form-control" type="number" value="${item.qty}" min="1" onchange="changeQty(${item.id},this.value)"></td>
        <td>${rupiah(sub)}</td><td><button class="btn btn-sm btn-danger" onclick="removeCart(${item.id})">Hapus</button></td></tr>`; });
        if(cart.length===0) 
            body.innerHTML='<tr><td colspan="5" class="text-center">Keranjang masih kosong.</td></tr>'; 
        if(totalEl) totalEl.textContent=rupiah(total); 
        if(input) input.value=JSON.stringify(cart); 
}

document.addEventListener('DOMContentLoaded',()=>{ updateCartCount(); renderCart(); });
