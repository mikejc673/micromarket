<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MicroMarket - Gestion des produits</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        img { width: 64px; height: 64px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Gestion des produits</h1>
    <table>
        <thead>
            <tr>
                <th>Visuel</th>
                <th>Description</th>
                <th>Prix (€)</th>
                <th>Péremption</th>
                <th>Catégorie(s)</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="product-list"></tbody>
    </table>
    <button onclick="importData()">Importer</button>
    <button onclick="exportData()">Exporter</button>

    <script>
        async function fetchProducts() {
            const response = await fetch('api/produits');
            const products = await response.json();
            const tbody = document.getElementById('product-list');
            tbody.innerHTML = '';
            products.forEach(p => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><img src="${p.asset_path || 'assets/Visuel-non-disponible.jpg'}" onclick="showPopup(this.src)"></td>
                    <td>${p.description}</td>
                    <td>${(p.price / 100).toFixed(2)}</td>
                    <td>${p.expiration_date}</td>
                    <td>${p.category_id}</td>
                    <td>
                        <select onchange="updateStatus(${p.id_product}, this.value)">
                            <option value="1" ${p.statut_id == 1 ? 'selected' : ''}>En cours</option>
                            <option value="2" ${p.statut_id == 2 ? 'selected' : ''}>En stock</option>
                            <option value="3" ${p.statut_id == 3 ? 'selected' : ''}>Epuisé</option>
                            <option value="4" ${p.statut_id == 4 ? 'selected' : ''}>Retiré</option>
                        </select>
                    </td>
                    <td><button onclick="deleteProduct(${p.id_product})">Supprimer</button></td>
                `;
                tbody.appendChild(tr);
            });
        }

        async function updateStatus(id, status) {
            await fetch(`api/produits/${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `statut_id=${status}`
            });
            fetchProducts();
        }

        async function deleteProduct(id) {
            if (confirm('Confirmez-vous la suppression ?')) {
                await fetch(`api/produits/${id}`, { method: 'DELETE' });
                fetchProducts();
            }
        }

        function showPopup(src) {
            window.open(src, 'Visuel', 'width=400,height=400');
        }

        // Charger les produits au démarrage
        fetchProducts();
    </script>
</body>
</html>