const products = [
    { name: "Smartphone", price: 999.99 },
    { name: "Laptop", price: 1499.99 },
    { name: "Headphones", price: 199.99 },
    { name: "Smartwatch", price: 299.99 },
];

const cart = [];
const productList = document.getElementById("product-list");
const cartItems = document.getElementById("cart-items");
const totalPriceEl = document.getElementById("total-price");

function displayProducts() {
    products.forEach((product, index) => {
        const productEl = document.createElement("div");
        productEl.classList.add("product");
        productEl.innerHTML = `
            <h3>${product.name}</h3>
            <p>R$ ${product.price.toFixed(2)}</p>
            <button onclick="addToCart(${index})">Agregar al carrito</button>
        `;
        productList.appendChild(productEl);
    });
}

function addToCart(index) {
    const product = products[index];
    cart.push(product);
    renderCart();
}

function renderCart() {
    cartItems.innerHTML = "";
    let total = 0;
    cart.forEach((item, index) => {
        const cartItem = document.createElement("li");
        cartItem.innerHTML = `${item.name} - R$ ${item.price.toFixed(2)} 
                              <button onclick="removeFromCart(${index})">X</button>`;
        cartItems.appendChild(cartItem);
        total += item.price;
    });
    totalPriceEl.textContent = `R$ ${total.toFixed(2)}`;
}

function removeFromCart(index) {
    cart.splice(index, 1);
    renderCart();
}

displayProducts();
