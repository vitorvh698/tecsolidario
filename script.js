let cart = JSON.parse(localStorage.getItem('tecsolidario_cart') || '[]');

function money(value) {
  return Number(value).toLocaleString('pt-BR', {style:'currency', currency:'BRL'});
}
function saveCart() {
  localStorage.setItem('tecsolidario_cart', JSON.stringify(cart));
  renderCart();
}
function addToCart(product) {
  const existing = cart.find(i => i.id === product.id);
  if (existing) existing.qty++;
  else cart.push({...product, qty:1});
  saveCart();
  alert('Peça adicionada ao carrinho!');
}
function removeFromCart(id) {
  cart = cart.filter(i => i.id !== id);
  saveCart();
}
function renderCart() {
  document.getElementById('cartCount').textContent = cart.reduce((n,i)=>n+i.qty,0);
  const box = document.getElementById('cartItems');
  if (!cart.length) {
    box.innerHTML = '<p class="empty">Seu carrinho está vazio.</p>';
    document.getElementById('cartTotal').textContent = money(0);
    return;
  }
  box.innerHTML = cart.map(i => `
    <div class="cart-item">
      <span class="mini-icon">${escapeHtml(i.icon)}</span>
      <div><b>${escapeHtml(i.name)}</b><small>${i.qty} × ${money(i.price)}</small></div>
      <button onclick="removeFromCart(${i.id})">Remover</button>
    </div>`).join('');
  document.getElementById('cartTotal').textContent = money(cart.reduce((t,i)=>t+i.price*i.qty,0));
}
function openCart() {
  renderCart();
  document.getElementById('cartModal').classList.add('open');
}
function openDonation() {
  document.getElementById('donationModal').classList.add('open');
}
function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}
function checkout() {
  if (!cart.length) return alert('Seu carrinho está vazio.');
  alert('Demonstração: aqui você pode integrar Mercado Pago, Stripe ou outro gateway.');
}
function escapeHtml(s) {
  return String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c]));
}
function filterProducts() {
  const term = document.getElementById('searchInput').value.toLowerCase().trim();
  const cat = document.getElementById('categoryFilter').value;
  let visible = 0;
  document.querySelectorAll('.product-card').forEach(card => {
    const okTerm = !term || card.dataset.name.includes(term);
    const okCat = !cat || card.dataset.category === cat;
    card.style.display = okTerm && okCat ? '' : 'none';
    if (okTerm && okCat) visible++;
  });
  document.getElementById('resultInfo').textContent = `${visible} resultado(s)`;
}
function filterCategory(category) {
  document.getElementById('categoryFilter').value = category;
  filterProducts();
  document.getElementById('produtos').scrollIntoView({behavior:'smooth'});
}
document.getElementById('searchInput').addEventListener('input', filterProducts);
document.getElementById('searchBtn').addEventListener('click', filterProducts);
document.getElementById('categoryFilter').addEventListener('change', filterProducts);

document.getElementById('donationForm').addEventListener('submit', async e => {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(e.target));
  const res = await fetch('api.php?action=donate', {
    method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(data)
  });
  const json = await res.json();
  document.getElementById('donationMessage').textContent = json.message;
  if (json.ok) e.target.reset();
});

window.addEventListener('click', e => {
  if (e.target.classList.contains('modal')) e.target.classList.remove('open');
});
renderCart();
filterProducts();
