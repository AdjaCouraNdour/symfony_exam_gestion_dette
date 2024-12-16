const API_URL = 'http://localhost:8000/api';

// Fonction pour charger les articles depuis le backend
async function listerArticles() {
    try {
        const response = await fetch('http://localhost:8000/api/articles', {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        });

        if (response.ok) {
            const articles = await response.json();
            displayArticles(articles); // Affiche les articles dans la liste
        } else {
            const error = await response.json();
            alert(`Erreur : ${error.message}`);
        }
    } catch (err) {
        console.error('Erreur lors de la récupération des articles :', err);
        alert("Erreur lors du chargement des articles.");
    }
}

let selectedArticles = []; 

// Fonction pour afficher la liste des articles disponibles
function displayArticles(articles) {
    const articlesList = document.getElementById('articles-list');
    articlesList.innerHTML = ''; // Efface la liste existante

    // Parcours des articles pour afficher les éléments de la liste
    articles.forEach(article => {
        const li = document.createElement('li');
        li.classList.add('flex', 'items-center', 'py-2', 'border-b', 'border-gray-200');
        
        // Vérification de la quantité maximale
        if (article.qte_stock > article.qte_stock) {
            article.qte_stock = article.qte_stock;
            alert(`Quantité maximale atteinte pour ${article.libelle}`);
        }
        
        // Désactivation des articles avec stock épuisé
        if (article.qte_stock <= 0) {
            li.classList.add('opacity-50', 'pointer-events-none');
            li.querySelector('input').disabled = true;
        }

        // Ajout du contenu à l'élément de la liste
        li.innerHTML = `
            <input type="checkbox" id="article-${article.id}" class="mr-2" onclick="toggleArticleSelection(${article.id})">
            <label for="article-${article.id}" class="text-sm text-gray-700">${article.libelle} - ${article.prix} € - ${article.qte_stock}</label>
        `;
        
        // Ajout de l'élément à la liste
        articlesList.appendChild(li);
    });

    // Stocke les articles pour les manipulations ultérieures
    window.articles = articles;
}


// Fonction pour ajouter/retirer un article de la liste des articles sélectionnés
function toggleArticleSelection(articleId) {
    const article = articles.find(a => a.id === articleId);
    const isSelected = document.getElementById(`article-${articleId}`).checked;

    if (isSelected) {
        selectedArticles.push({ ...article, quantity: 1 });
    } else {
        selectedArticles = selectedArticles.filter(a => a.id !== articleId);
    }

    displaySelectedArticles();
}

// Fonction pour afficher les articles sélectionnés dans le tableau
function displaySelectedArticles() {
    const selectedArticlesBody = document.getElementById('selected-articles-body');
    selectedArticlesBody.innerHTML = ''; // Efface la liste existante

    selectedArticles.forEach(article => {
        const tr = document.createElement('tr');
        tr.setAttribute('data-article-id', article.id); // Ajout de l'ID de l'article dans le data-attribute
        tr.innerHTML = `
            <td class="py-2 px-4">${article.libelle}</td>
            <td class="py-2 px-4">
                <button onclick="updateQuantity(${article.id}, -1)" class="px-3 py-1 bg-gray-300 text-black rounded-md hover:bg-gray-400">-</button>
                <input type="number" value="${article.qte_stock}" min="1" class="article-quantity mx-2 w-12" onchange="updateQuantityFromInput(${article.id}, this.value)" />
                <button onclick="updateQuantity(${article.id}, 1)" class="px-3 py-1 bg-gray-300 text-black rounded-md hover:bg-gray-400">+</button>
            </td>
        `;
        selectedArticlesBody.appendChild(tr);
    });
}

// Fonction pour mettre à jour la quantité d'un article
function updateQuantity(articleId, delta) {
    const article = selectedArticles.find(a => a.id === articleId);
    if (article) {
        article.qte_stock += delta;
        if (article.qte_stock < 1) article.qte_stock = 1; // Empêche la quantité d'être inférieure à 1
        displaySelectedArticles();
    }
}

// Fonction pour mettre à jour la quantité d'un article à partir de l'input
function updateQuantityFromInput(articleId, newQuantity) {
    const article = selectedArticles.find(a => a.id === articleId);
    if (article) {
        article.qte_stock = Math.max(1, parseInt(newQuantity, 10)); // La quantité ne doit pas être inférieure à 1
        displaySelectedArticles();
    }
}

async function createDette() {
    const clientnum = document.getElementById('client-num').value;
    const selectedArticles = getSelectedArticles(); // Fonction pour récupérer les articles sélectionnés

    if (!clientnum.trim()) {
        alert('Veuillez entrer le numéro du client.');
        return;
    }

    if (selectedArticles.length === 0) {
        alert('Veuillez sélectionner au moins un article pour créer une dette.');
        return;
    }

    try {
        const response = await fetch(`${API_URL}/dettes`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                clientnum,
                articles: selectedArticles.map(article => ({
                    id: article.id,
                    quantity: article.qte_stock // Utiliser qte_stock
                }))
            })
        });

        if (response.ok) {
            alert('Dette créée avec succès !');
            showPage('list-dette');

        } else {
            const error = await response.json();
            alert(`Erreur : ${error.message}`);
        }
    } catch (err) {
        console.error('Erreur lors de la création de la dette:', err);
    }
}

// Fonction pour récupérer les articles sélectionnés
function getSelectedArticles() {
    const rows = document.querySelectorAll('#selected-articles-body tr');
    const articles = [];

    rows.forEach(row => {
        const id = row.dataset.articleId;
        const quantityField = row.querySelector('.article-quantity');  // Recherche du champ de quantité

        if (id && quantityField) {
            const quantity = parseInt(quantityField.value, 10);
            if (quantity && quantity > 0) {
                articles.push({ id, qte_stock: quantity }); // Utiliser qte_stock au lieu de quantity
            } else {
                alert(`Quantité invalide pour l'article ${id}`);
            }
        }
    });

    return articles;
}

document.addEventListener('DOMContentLoaded', () => {
    listerArticles(); // Charge les articles depuis le backend
});
