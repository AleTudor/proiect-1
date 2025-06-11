document.addEventListener("DOMContentLoaded", function() {
    // Selectăm link-urile și submeniurile
    const produseLink = document.getElementById("produse-link");
    const submenu = document.querySelector(".submenu");
    const cartiLink = document.querySelector(".submenu li a"); // Ajustează selecția dacă este necesar
    const genuri = document.querySelector(".genuri"); // Asigură-te că ai un ID sau clasă corectă

    // Eveniment pentru a face toggle la submeniul "Produse"
    produseLink.addEventListener("click", function(e) {
        e.preventDefault();
        submenu.classList.toggle("show");
    });

    // Eveniment pentru a face toggle la submeniul "Cărți"
    cartiLink.addEventListener("click", function(e) {
        e.preventDefault();
        genuri.classList.toggle("show");
    });

    // Eveniment pentru a ascunde submeniurile dacă dai click în altă parte
    document.addEventListener("click", function(e) {
        // Ascunde submeniul "Produse"
        if (!produseLink.contains(e.target) && !submenu.contains(e.target)) {
            submenu.classList.remove("show");
        }

        // Ascunde submeniul "Cărți"
        if (!cartiLink.contains(e.target) && !genuri.contains(e.target)) {
            genuri.classList.remove("show");
        }
    });
});





let currentSlide = 0;
const slides = document.querySelectorAll(".slide");
const nextButton = document.querySelector(".next");
const prevButton = document.querySelector(".prev");
let autoSlideInterval;

// Funcția pentru a afisa slide-ul activ
function showSlide(index) {
    slides.forEach((slide, i) => {
        if (i === index) {
            slide.classList.add("active");
        } else {
            slide.classList.remove("active");
        }
    });
    currentSlide = index;
}

// Funcția pentru slide-ul următor
function nextSlide() {
    let nextIndex = (currentSlide + 1) % slides.length;
    showSlide(nextIndex);
}

// Funcția pentru slide-ul anterior
function prevSlide() {
    let prevIndex = (currentSlide - 1 + slides.length) % slides.length;
    showSlide(prevIndex);
}

// Setează intervalul pentru derularea automată a slide-urilor
function startAutoSlide() {
    autoSlideInterval = setInterval(nextSlide, 15000); // Schimbă slide-ul la fiecare 30 secunde
}

// Oprește derularea automată
function stopAutoSlide() {
    clearInterval(autoSlideInterval);
}

// Afișează primul slide la încărcarea paginii
showSlide(currentSlide);

// Pornește derularea automată la încărcarea paginii
startAutoSlide();

// Evenimente pentru butoanele de navigare
nextButton.addEventListener("click", () => {
    nextSlide();
    stopAutoSlide();  
    startAutoSlide();
});

prevButton.addEventListener("click", () => {
    prevSlide();
    stopAutoSlide();
    startAutoSlide();
});







// Funcția pentru a adăuga un produs în coș
function addToCart(button) {
    const productId = button.getAttribute('data-id');
    const productName = button.getAttribute('data-name');
    const productPrice = parseFloat(button.getAttribute('data-price'));
    const productImage = button.getAttribute('data-image');

    // Verificăm dacă datele produsului sunt complete
    if (!productId || !productName || isNaN(productPrice) || !productImage) {
        alert('Datele produsului nu sunt complete!');
        return;
    }

    // Preluăm coșul din localStorage sau inițializăm unul gol
    let cart = JSON.parse(localStorage.getItem("theCart")) || [];

    // Căutăm dacă produsul există deja în coș
    const existingProduct = cart.find(p => p.id === productId);
    if (existingProduct) {
        existingProduct.quantity += 1; // Creștem cantitatea
    } else {
        // Adăugăm produsul cu cantitate inițială 1
        cart.push({
            id: productId,
            name: productName,
            price: productPrice,
            image: productImage,
            quantity: 1
        });
    }

    localStorage.setItem("theCart", JSON.stringify(cart));
    updateCart();
    console.log("Produs adăugat în coș:", cart);
}

// Funcția pentru actualizarea interfeței coșului
function updateCart() {
    console.log("Actualizez coșul...");

    let cart = JSON.parse(localStorage.getItem("theCart")) || [];
    const cartItems = document.getElementById('cart-items');
    const cartCount = document.getElementById('cart-count');
    const cartTotal = document.getElementById('cart-total');

    // Dacă containerul de coș nu există (de exemplu, pe pagina de produse), nu mai facem nimic
    if (!cartItems || !cartCount || !cartTotal) return;

    // Curățăm lista anterioară de produse
    cartItems.innerHTML = '';

    if (cart.length === 0) {
        cartItems.innerHTML = '<li>Coșul este gol.</li>';
        cartCount.textContent = '0';
        cartTotal.textContent = "0.00";
        return;
    }

    let total = 0;

    cart.forEach(product => {
        // Verificăm dacă prețul este valid
        if (!product.price || isNaN(product.price)) {
            console.warn(`Produsul ${product.name} are un preț invalid:`, product);
            return;
        }

        // Asigurăm o cantitate minimă de 1
        if (!product.quantity || isNaN(product.quantity) || product.quantity < 1) {
            product.quantity = 1;
        }

        total += product.price * product.quantity;

        const li = document.createElement('li');
        li.className = 'cart-item';

        // Construim calea corectă pentru imagine:
        // Dacă, de exemplu, product.image este "poze/cat_timp_infloresc_lamaii.jpg" și
        // imaginea se află în directorul principal, iar pagina shop.html e în "ANTICARIAT",
        // folosim "../" pentru a reveni în directorul părinte.
        let imageName = product.image;
        if (imageName.startsWith('/')) {
            imageName = imageName.slice(1);
        }
        const imageSrc = imageName ? `../${imageName}` : 'path/to/default-image.jpg';

        li.innerHTML = `
            <img src="${imageSrc}" alt="${product.name}" class="product-image">
            <span class="cos-descriere">${product.name} <br>  <div class="cos-pret"> ${(product.price * product.quantity).toFixed(2)} RON </div></span>
            <div>
                <button class="decrease-qty" data-id="${product.id}">-</button>
                <input type="number" value="${product.quantity}" min="1" class="qty-input" data-id="${product.id}">
                <button class="increase-qty" data-id="${product.id}">+</button>
                <button class="remove-item" data-id="${product.id}">Șterge</button>
            </div>
           
        `;
        cartItems.appendChild(li);
    });

    // Calculăm cantitatea totală de produse
    const totalQuantity = cart.reduce((sum, product) => sum + product.quantity, 0);
    cartCount.textContent = totalQuantity;
    cartTotal.textContent = total.toFixed(2);

    // Atașăm event listener-ele pentru butoane și inputuri
    attachCartEventListeners();
}

    
// Funcție pentru a atașa event listener-ele pentru modificarea coșului
function attachCartEventListeners() {
    // Buton pentru eliminarea unui produs
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', (e) => {
            const id = e.target.getAttribute('data-id');
            removeFromCart(id);
        });
    });

    // Butoane pentru scăderea cantității
    document.querySelectorAll('.decrease-qty').forEach(button => {
        button.addEventListener('click', (e) => {
            const id = e.target.getAttribute('data-id');
            changeQuantity(id, -1);
        });
    });

    // Butoane pentru creșterea cantității
    document.querySelectorAll('.increase-qty').forEach(button => {
        button.addEventListener('click', (e) => {
            const id = e.target.getAttribute('data-id');
            changeQuantity(id, 1);
        });
    });

    // Input pentru modificarea directă a cantității
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', (e) => {
            const id = e.target.getAttribute('data-id');
            const newQuantity = parseInt(e.target.value);
            setQuantity(id, newQuantity);
        });
    });
}

// Funcția pentru a elimina un produs din coș
function removeFromCart(productId) {
    let cart = JSON.parse(localStorage.getItem("theCart")) || [];
    cart = cart.filter(product => product.id !== productId);
    localStorage.setItem("theCart", JSON.stringify(cart));
    updateCart();
}

// Funcția pentru a schimba cantitatea unui produs (creștere sau descreștere)
function changeQuantity(productId, delta) {
    let cart = JSON.parse(localStorage.getItem("theCart")) || [];
    const product = cart.find(p => p.id === productId);
    if (product) {
        product.quantity += delta;
        if (product.quantity < 1) {
            // Eliminăm produsul dacă cantitatea devine mai mică de 1
            cart = cart.filter(p => p.id !== productId);
        }
    }
    localStorage.setItem("theCart", JSON.stringify(cart));
    updateCart();
}

// Funcția pentru setarea unei cantități exacte
function setQuantity(productId, quantity) {
    let cart = JSON.parse(localStorage.getItem("theCart")) || [];
    const product = cart.find(p => p.id === productId);
    if (product) {
        product.quantity = quantity;
        if (product.quantity < 1) {
            cart = cart.filter(p => p.id !== productId);
        }
    }
    localStorage.setItem("theCart", JSON.stringify(cart));
    updateCart();
}
document.addEventListener('DOMContentLoaded', () => {
    // Actualizează coșul imediat ce pagina este încărcată
    updateCart(); // Apelează funcția care actualizează coșul


    // Butonul de checkout (dacă există)
    const checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', () => {
            let cart = JSON.parse(localStorage.getItem("theCart")) || [];
            if (cart.length > 0) {
                window.location.href = 'ANTICARIAT/shop.html';
            }
        });
    }

    

});

document.addEventListener('DOMContentLoaded', function() {
    const paymentButtons = document.querySelectorAll('.payment-method .method');
  
    paymentButtons.forEach(button => {
      button.addEventListener('click', function() {
        // Eliminăm clasa 'active' de la toate butoanele
        paymentButtons.forEach(btn => btn.classList.remove('active'));
        
        // Adăugăm clasa 'active' pe butonul pe care s-a făcut click
        this.classList.add('active');
      });
    });
  });
  



  document.getElementById("subscribeBtn").addEventListener("click", function() {
    let email = document.getElementById("emailInput").value.trim();
    let popup = document.getElementById("popupMessage");
    let popupText = document.getElementById("popupText");

    // Expresie regulată pentru validarea unui e-mail
    let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    // Verifică dacă e-mailul respectă formatul corect
    if (!emailPattern.test(email)) {
        popupText.textContent = "Te rugăm să introduci un e-mail valid!";
        popup.style.display = "flex";
    } else {
        popupText.textContent = `E-mailul ${email} a fost înregistrat cu succes! ✅`;
        popup.style.display = "flex";
        document.getElementById("emailInput").value = ""; // Curăță input-ul
    }
});

document.getElementById("closePopup").addEventListener("click", function() {
    document.getElementById("popupMessage").style.display = "none";
});









// Funcția pentru a adăuga un produs în Wishlist
function addToWishlist(button) {
    const productId = button.getAttribute('data-id');
    const productName = button.getAttribute('data-name');
    const productPrice = parseFloat(button.getAttribute('data-price'));
    const productImage = button.getAttribute('data-image');

    if (!productId || !productName || isNaN(productPrice) || !productImage) {
        alert('Datele produsului nu sunt complete!');
        return;
    }

    let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];

    const existingProduct = wishlist.find(p => p.id === productId);
    if (!existingProduct) {
        wishlist.push({
            id: productId,
            name: productName,
            price: productPrice,
            image: productImage
        });
        localStorage.setItem("wishlist", JSON.stringify(wishlist));
        alert("Produs adăugat în wishlist!");
    } else {
        alert("Produsul este deja în wishlist!");
    }
    console.log("Wishlist:", wishlist);
    updateWishlist();  // Dacă folosești această funcție pe pagina curentă

}

// Funcția pentru actualizarea interfeței wishlist-ului
function updateWishlist() {
    console.log("Actualizez wishlist-ul...");

    let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
    const wishlistItems = document.getElementById('wishlist-items');
    
    if (!wishlistItems) return;

    wishlistItems.innerHTML = '';

    if (wishlist.length === 0) {
        wishlistItems.innerHTML = '<li>Wishlist-ul este gol!</li>';
        return;
    }

    wishlist.forEach(product => {
        const li = document.createElement('li');
        li.className = 'wishlist-item';

        li.innerHTML = `
            <img src="${product.image}" alt="${product.name}" class="product-image">
            <span class="wishlist-descriere">${product.name} <br> <div class="wishlist-pret"> ${product.price.toFixed(2)} RON </div></span>
            <button class="remove-wishlist" data-id="${product.id}">Șterge</button>
        `;
        wishlistItems.appendChild(li);
    });

    // Adaug evenimentele pentru butoanele de ștergere
    document.querySelectorAll('.remove-wishlist').forEach(button => {
        button.addEventListener('click', function() {
            removeFromWishlist(this.getAttribute('data-id'));
        });
    });
}

// Funcția pentru a elimina un produs din Wishlist
function removeFromWishlist(productId) {
    let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
    wishlist = wishlist.filter(p => p.id !== productId);
    localStorage.setItem("wishlist", JSON.stringify(wishlist));
   // Apelăm ambele funcții de actualizare pentru a acoperi ambele pagini
    if (typeof updateWishlist === 'function') {
        updateWishlist();
    }
    
    if (typeof afiseazaWishlist === 'function') {
        afiseazaWishlist();
    }
}

// Apel pentru actualizarea interfeței la încărcarea paginii
document.addEventListener("DOMContentLoaded", updateWishlist);








document.addEventListener("DOMContentLoaded", function () {
    let videoSlide = document.querySelector(".image7 video"); // Selectează videoclipul
    let observer = new IntersectionObserver(
        function (entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    videoSlide.play(); // Rulează videoclipul când slide-ul devine vizibil
                } else {
                    videoSlide.pause(); // Pune pauză când slide-ul nu mai este vizibil
                    videoSlide.currentTime = 0; // Resetează videoclipul
                }
            });
        },
        { threshold: 0.5 } // Declanșează efectul când cel puțin 50% din slide este vizibil
    );

    observer.observe(videoSlide); // Monitorizează videoclipul
});







document.addEventListener("DOMContentLoaded", function () {
    let scrollToTopBtn = document.getElementById("scrollToTop");

    window.addEventListener("scroll", function () {
        if (window.scrollY > 200) {
            scrollToTopBtn.style.display = "block";
        } else {
            scrollToTopBtn.style.display = "none";
        }
    });

    scrollToTopBtn.addEventListener("click", function () {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
});





document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(".contact-form");
  
    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Previne trimiterea formularului prin default
  
        const nume = document.getElementById("nume").value;
        const email = document.getElementById("email").value;
        const subject = document.getElementById("subiect").value;
        const mesaj = document.querySelector("textarea[name='mesaj']").value;
  
        // Verifică dacă toate câmpurile sunt completate
        if (!nume || !email || !subiect || !mesaj) {
            alert("Te rog completează toate câmpurile!");
            return;
        }
  
        // Creează obiectul FormData pentru a trimite datele
        const formData = new FormData();
        formData.append("nume", nume);
        formData.append("email", email);
        formData.append("subiect", subiect);
        formData.append("mesaj", mesaj);
  
        // Trimite datele către server folosind fetch
        fetch("mail.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data); // Afișează mesajul de succes sau eroare
            form.reset(); // Resetează formularul
        })
        .catch(error => {
            alert("A apărut o eroare. Te rog încearcă din nou.");
        });
    });
  });




  document.addEventListener("DOMContentLoaded", function() {
    const wishlistBadge = document.getElementById("wishlist-count");

    // Obține wishlist-ul din localStorage sau creează unul gol dacă nu există
    let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
    
    // Setează contorul inițial cu numărul de produse deja adăugate în wishlist
    let wishlistCount = wishlist.length;

    // Actualizează textul badge-ului pentru a reflecta numărul de produse din wishlist
    wishlistBadge.textContent = `${wishlistCount}`;
    if (wishlistCount > 0) {
        wishlistBadge.style.display = "inline-block"; // Afișează badge-ul dacă există produse
    } else {
        wishlistBadge.style.display = "none"; // Ascunde badge-ul dacă nu sunt produse
    }

    document.querySelectorAll(".add-to-wishlist").forEach(button => {
        button.addEventListener("click", function() {
            const productId = button.getAttribute('data-id');
            const productName = button.getAttribute('data-name');
            const productPrice = button.getAttribute('data-price');
            const productImage = button.getAttribute('data-image');

            // Verifică dacă produsul există deja în wishlist
            const productExists = wishlist.some(product => product.id === productId);

            if (productExists) {
                // Dacă produsul există deja, afișăm un mesaj de avertizare
                alert(`Produsul "${productName}" este deja în wishlist.`);
            } else {
                // Dacă produsul nu există, îl adăugăm
                wishlist.push({ id: productId, name: productName, price: productPrice, image: productImage });
                localStorage.setItem("wishlist", JSON.stringify(wishlist));

                // Incrementăm contorul pentru produse adăugate
                wishlistCount++;

                // Actualizează textul badge-ului pentru a arăta câte produse au fost adăugate
                wishlistBadge.textContent = `${wishlistCount}`;  // Afișează câte produse au fost adăugate
                wishlistBadge.style.display = "inline-block"; // Afișează badge-ul
            }
        });
    });
});




document.addEventListener("DOMContentLoaded", function() {
    const cartBadge = document.getElementById("cart-count");

    // Inițial, se preia coșul din localStorage folosind cheia "theCart"
    let cart = JSON.parse(localStorage.getItem("theCart")) || [];

    // Funcție pentru calcularea totalului de produse (suma cantităților)
    function getTotalQuantity() {
        return cart.reduce((sum, product) => sum + (product.quantity || 1), 0);
    }

    // Funcție pentru actualizarea badge-ului din icon
    function updateBadge() {
        let totalQuantity = getTotalQuantity();
        cartBadge.textContent = `${totalQuantity}`;
        cartBadge.style.display = totalQuantity > 0 ? "inline-block" : "none";
    }

    // Actualizează badge-ul la încărcarea paginii
    updateBadge();

    // Adaugă eveniment pentru butoanele "add-to-cart"
    document.querySelectorAll(".add-to-cart").forEach(button => {
        button.addEventListener("click", function() {
            // Reîncarcă coșul din localStorage la începutul evenimentului
            cart = JSON.parse(localStorage.getItem("theCart")) || [];

            const productId = button.getAttribute('data-id');
            const productName = button.getAttribute('data-name');
            const productPrice = button.getAttribute('data-price');
            const productImage = button.getAttribute('data-image');

            

            // Actualizează badge-ul după modificări
            updateBadge();
        });
    });
});







    setTimeout(function() {
        let msg = document.querySelector(".mail-mesaj");
        if (msg) {
            msg.style.transition = "opacity 0.5s ease-out";
            msg.style.opacity = "0";
            setTimeout(() => msg.remove(), 500); // Șterge elementul după dispariție
        }
    }, 5000); // 5 secunde


    


document.addEventListener('DOMContentLoaded', function() {
    // Obține rolul din localStorage
    var role = localStorage.getItem('role');
    // Link-ul implicit pentru user
    var accountLink = "http://localhost/anticariat/contulMeu.php";
    // Dacă rolul este admin, modifică link-ul
    if (role === 'admin') {
        accountLink = "http://localhost/anticariat/admin_dashboard.php";
    }
    
    // Selectează toate elementele cu clasa "account-link"
    var accountLinks = document.querySelectorAll('.account-link');
    accountLinks.forEach(function(link) {
        link.setAttribute('href', accountLink);
    });
});

