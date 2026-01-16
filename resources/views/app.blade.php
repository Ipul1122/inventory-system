<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Inventaris Toko</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .hidden { display: none !important; }
        .loading { opacity: 0.5; pointer-events: none; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark hidden" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">Inventory App</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#" onclick="showPage('inventory')">Inventaris</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link hidden" id="nav-users" href="#" onclick="showPage('users')">Manajemen User</a>
                    </li>
                </ul>
                <span class="navbar-text me-3" id="user-display-name"></span>
                <button class="btn btn-outline-danger btn-sm" onclick="logout()">Logout</button>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        
        <div id="alert-area"></div>

        <div id="login-page" class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Login Sistem</h4>
                    </div>
                    <div class="card-body">
                        <form onsubmit="handleLogin(event)">
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" id="email" class="form-control" required value="admin@toko.com">
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" id="password" class="form-control" required value="password">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="inventory-page" class="hidden">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>📦 Daftar Barang</h2>
                <button class="btn btn-success hidden" id="btn-add-product" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    + Tambah Barang
                </button>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="product-list">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="users-page" class="hidden">
            <h2 class="mb-4">👥 Manajemen User</h2>
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role Saat Ini</th>
                                <th>Ubah Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="user-list">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm">
                        <div class="mb-2"><input type="text" id="new-name" class="form-control" placeholder="Nama Barang" required></div>
                        <div class="mb-2"><input type="number" id="new-stock" class="form-control" placeholder="Stok Awal" required></div>
                        <div class="mb-2"><input type="number" id="new-price" class="form-control" placeholder="Harga" required></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="addProduct()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_URL = 'http://127.0.0.1:8000/api';
        let TOKEN = localStorage.getItem('token');
        let USER_ROLE = localStorage.getItem('role'); // 1:Admin, 2:Seller, 3:Pelanggan

        // --- 1. System Logic (Init) ---
        document.addEventListener("DOMContentLoaded", () => {
            if (TOKEN) {
                initApp();
            } else {
                showPage('login');
            }
        });

        function showPage(pageId) {
            // Sembunyikan semua halaman
            ['login', 'inventory', 'users'].forEach(p => {
                document.getElementById(p + '-page').classList.add('hidden');
            });
            document.getElementById('navbar').classList.add('hidden');

            if (pageId === 'login') {
                document.getElementById('login-page').classList.remove('hidden');
            } else {
                document.getElementById('navbar').classList.remove('hidden');
                document.getElementById(pageId + '-page').classList.remove('hidden');
                
                // Load data sesuai halaman
                if (pageId === 'inventory') loadProducts();
                if (pageId === 'users') loadUsers();
            }
        }

        function showAlert(message, type = 'danger') {
            const alertArea = document.getElementById('alert-area');
            alertArea.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            // Auto hide
            setTimeout(() => { alertArea.innerHTML = ''; }, 3000);
        }

        // --- 2. Auth Logic ---
        async function handleLogin(e) {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const res = await fetch(`${API_URL}/auth/login`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ email, password })
                });

                const data = await res.json();
                
                if (res.ok) {
                    localStorage.setItem('token', data.access_token);
                    localStorage.setItem('role', data.user.role_id); // Simpan Role ID
                    localStorage.setItem('name', data.user.name);
                    TOKEN = data.access_token;
                    USER_ROLE = data.user.role_id;
                    initApp();
                } else {
                    showAlert('Login Gagal: ' + (data.error || 'Cek Email/Password'));
                }
            } catch (err) {
                showAlert('Gagal menghubungi server.');
            }
        }

        function initApp() {
            document.getElementById('user-display-name').innerText = `Halo, ${localStorage.getItem('name')}`;
            
            // Atur Menu berdasarkan Role
            // Role 1 = Admin, 2 = Seller, 3 = Pelanggan
            if (USER_ROLE == 1) {
                document.getElementById('nav-users').classList.remove('hidden');
                document.getElementById('btn-add-product').classList.remove('hidden');
            } else {
                document.getElementById('nav-users').classList.add('hidden');
                document.getElementById('btn-add-product').classList.add('hidden');
            }

            showPage('inventory');
        }

        function logout() {
            localStorage.clear();
            location.reload();
        }

        // --- 3. Inventory Logic ---
        async function loadProducts() {
            const res = await fetch(`${API_URL}/products`, {
                headers: { 'Authorization': `Bearer ${TOKEN}`, 'Accept': 'application/json' }
            });
            const data = await res.json();
            const tbody = document.getElementById('product-list');
            tbody.innerHTML = '';

            data.data.forEach(item => {
                let actionBtn = '';
                
                // Tombol JUAL hanya untuk Admin (1) & Seller (2)
                if (USER_ROLE == 1 || USER_ROLE == 2) {
                    actionBtn = `<button class="btn btn-primary btn-sm" onclick="sellProduct(${item.id}, '${item.name}', ${item.stock})">Jual</button>`;
                } else {
                    actionBtn = '<span class="text-muted text-sm">View Only</span>';
                }

                tbody.innerHTML += `
                    <tr>
                        <td>${item.name}</td>
                        <td>Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</td>
                        <td>
                            <span class="badge ${item.stock > 0 ? 'bg-info' : 'bg-danger'}">
                                ${item.stock} Unit
                            </span>
                        </td>
                        <td class="text-end">${actionBtn}</td>
                    </tr>
                `;
            });
        }

        async function sellProduct(id, name, currentStock) {
            // Validasi Awal di Frontend
            if (currentStock <= 0) {
                showAlert(`Stok ${name} habis! Tidak bisa dijual.`, 'warning');
                return;
            }

            const qty = prompt(`Jual "${name}"\nStok tersedia: ${currentStock}\nMasukkan Jumlah:`);
            if (!qty) return;

            // Panggil API Sell yang sudah kita revisi
            const res = await fetch(`${API_URL}/products/${id}/sell`, {
                method: 'POST',
                headers: { 
                    'Authorization': `Bearer ${TOKEN}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: qty })
            });

            const data = await res.json();

            if (res.ok) {
                showAlert(`✅ Berhasil menjual ${qty} ${name}!`, 'success');
                loadProducts(); // Refresh tabel
            } else {
                // Tampilkan Error dari Backend (Misal: Stok tidak cukup)
                showAlert(`❌ Gagal: ${data.error || 'Terjadi kesalahan'}`, 'danger');
            }
        }

        async function addProduct() {
            const name = document.getElementById('new-name').value;
            const stock = document.getElementById('new-stock').value;
            const price = document.getElementById('new-price').value;

            const res = await fetch(`${API_URL}/products`, {
                method: 'POST',
                headers: { 
                    'Authorization': `Bearer ${TOKEN}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ name, stock, price })
            });

            if (res.ok) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
                modal.hide();
                document.getElementById('addProductForm').reset();
                showAlert('Produk berhasil ditambahkan', 'success');
                loadProducts();
            } else {
                showAlert('Gagal menambah produk', 'danger');
            }
        }

        // --- 4. User Management Logic ---
        async function loadUsers() {
            if (USER_ROLE != 1) return; // Guard clause

            const res = await fetch(`${API_URL}/users`, {
                headers: { 'Authorization': `Bearer ${TOKEN}`, 'Accept': 'application/json' }
            });
            const data = await res.json();
            const tbody = document.getElementById('user-list');
            tbody.innerHTML = '';

            data.forEach(user => {
                // Dropdown Role
                const roleSelect = `
                    <select id="role-select-${user.id}" class="form-select form-select-sm">
                        <option value="1" ${user.role_id == 1 ? 'selected' : ''}>Admin</option>
                        <option value="2" ${user.role_id == 2 ? 'selected' : ''}>Seller</option>
                        <option value="3" ${user.role_id == 3 ? 'selected' : ''}>Pelanggan</option>
                    </select>
                `;

                tbody.innerHTML += `
                    <tr>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td><span class="badge bg-secondary">${user.role ? user.role.name : '-'}</span></td>
                        <td>${roleSelect}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="saveRole(${user.id})">Simpan</button>
                        </td>
                    </tr>
                `;
            });
        }

        async function saveRole(userId) {
            const newRoleId = document.getElementById(`role-select-${userId}`).value;

            const res = await fetch(`${API_URL}/users/${userId}/change-role`, {
                method: 'PUT',
                headers: { 
                    'Authorization': `Bearer ${TOKEN}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ role_id: newRoleId })
            });

            if (res.ok) {
                showAlert('Role user berhasil diperbarui!', 'success');
                loadUsers(); // Refresh tabel
            } else {
                showAlert('Gagal mengubah role', 'danger');
            }
        }
    </script>
</body>
</html>