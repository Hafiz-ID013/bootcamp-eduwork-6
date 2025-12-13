const rupiah = (n) =>
  new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR" }).format(n);

const products = [
  {
    id: "PRD-001",
    name: "Buku JavaScript",
    category: "Buku",
    price: 120000,
    desc: "Panduan dasar JavaScript untuk pemula, cocok buat latihan logika dan DOM.",
    image: "images/buku.jpg",
  },
  {
    id: "PRD-002",
    name: "Headphone Wireless",
    category: "Elektronik",
    price: 420000,
    desc: "Headphone nyaman untuk belajar dan fokus, suara clean dan bass halus.",
    image: "images/headphone.webp",
  },
  {
    id: "PRD-003",
    name: "Smartwatch",
    category: "Elektronik",
    price: 850000,
    desc: "Pantau aktivitas harian, notifikasi, dan gaya — semua di pergelangan.",
    image: "images/smartwatch.webp",
  },
  {
    id: "PRD-004",
    name: "Hoodie Premium",
    category: "Fashion",
    price: 250000,
    desc: "Hoodie hangat dan ringan, cocok buat daily outfit.",
    image: "images/hoodie.jpg",
  },
  {
    id: "PRD-005",
    name: "Sneaker Kasual",
    category: "Fashion",
    price: 360000,
    desc: "Sneaker fleksibel untuk jalan santai, nyaman dipakai seharian.",
    image: "images/sneaker.jpg",
  },
];

const elList = document.getElementById("productList");
const elCount = document.getElementById("countLabel");
const searchInput = document.getElementById("searchInput");
const categoryFilter = document.getElementById("categoryFilter");
const sortFilter = document.getElementById("sortFilter");
const resetBtn = document.getElementById("resetFilter");

const modalEl = document.getElementById("productModal");
const modal = new bootstrap.Modal(modalEl);

const modalTitle = document.getElementById("modalTitle");
const modalImage = document.getElementById("modalImage");
const modalCategory = document.getElementById("modalCategory");
const modalSku = document.getElementById("modalSku");
const modalDesc = document.getElementById("modalDesc");
const modalPrice = document.getElementById("modalPrice");
const modalBuyBtn = document.getElementById("modalBuyBtn");

function cardTemplate(p) {
  return `
    <div class="col-12 col-md-6 col-xl-4">
      <div class="product-card h-100">
        <div class="product-banner">
          <img src="${p.image}" alt="${p.name}">
        </div>

        <div class="product-body">
          <div class="product-cat">${p.category}</div>
          <div class="product-title">${p.name}</div>
          <div class="product-desc">${p.desc}</div>

          <div class="product-actions">
            <div class="product-price">${rupiah(p.price)}</div>
            <button class="buy-btn" data-id="${p.id}" type="button">Beli</button>
          </div>
        </div>
      </div>
    </div>
  `;
}

function applyFilters() {
  const q = (searchInput.value || "").toLowerCase().trim();
  const cat = categoryFilter.value;
  const sort = sortFilter.value;

  let result = products.filter((p) => {
    const matchName = p.name.toLowerCase().includes(q);
    const matchCat = cat === "all" ? true : p.category === cat;
    return matchName && matchCat;
  });

  if (sort === "asc") result.sort((a, b) => a.price - b.price);
  if (sort === "desc") result.sort((a, b) => b.price - a.price);

  return result;
}

function render() {
  const data = applyFilters();
  elList.innerHTML = data.map(cardTemplate).join("");
  elCount.textContent = data.length;

  // bind buttons
  elList.querySelectorAll(".buy-btn").forEach((btn) => {
    btn.addEventListener("click", () => openModal(btn.dataset.id));
  });
}

function openModal(id) {
  const p = products.find((x) => x.id === id);
  if (!p) return;

  modalTitle.textContent = p.name;
  modalImage.src = p.image;
  modalImage.alt = p.name;
  modalCategory.textContent = p.category;
  modalSku.textContent = `SKU: ${p.id}`;
  modalDesc.textContent = p.desc;
  modalPrice.textContent = rupiah(p.price);

  modalBuyBtn.onclick = () => {
    alert(`Berhasil memilih: ${p.name} (${rupiah(p.price)})`);
  };

  modal.show();
}

// events
searchInput.addEventListener("input", render);
categoryFilter.addEventListener("change", render);
sortFilter.addEventListener("change", render);
resetBtn.addEventListener("click", () => {
  searchInput.value = "";
  categoryFilter.value = "all";
  sortFilter.value = "default";
  render();
});

// first render
render();
