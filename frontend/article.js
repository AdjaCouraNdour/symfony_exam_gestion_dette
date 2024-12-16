const API_URL = 'http://localhost:8000/api';

document.addEventListener('DOMContentLoaded', listerArticles);

async function listerArticles() {
    try {
        const response = await fetch(`${API_URL}/articles`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        });

        if (response.ok) {
            const articles = await response.json();
            const tbody = document.getElementById('articles-body');

            // Vider le tbody avant d'ajouter de nouvelles lignes
            tbody.innerHTML = '';

            // Ajouter les lignes pour chaque article
            articles.forEach(article => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="py-2 px-4 border">${article.reference}</td>
                    <td class="py-2 px-4 border">${article.libelle}</td>
                    <td class="py-2 px-4 border">${article.prix}</td>
                    <td class="py-2 px-4 border">${article.qte_stock}</td>
                `;
                tbody.appendChild(row);
            });
        } else {
            const error = await response.json();
            alert(`Erreur : ${error.message}`);
        }
    } catch (err) {
        console.error('Erreur lors de la récupération des articles :', err);
    }
}

async function createArticle() {
    const libelle = document.getElementById('libelle').value;
    const prix = document.getElementById('prix').value;
    const qte_stock = document.getElementById('qte_stock').value;

    try{
        const response = await fetch(`${API_URL}/articles`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({libelle,prix,qte_stock})
        });
        if(response.ok){
            alert('Article créé avec succès !');
            showPage('list-article');

        }else{
            const error = await response.json();
            alert(`Erreur : ${error.message}`);
        }
    }catch(err){
        console.error('Erreur lors de la création du article:', err);
    }
}

function showPage(page) {
    const pages = document.querySelectorAll('.page-content');
    pages.forEach(p => p.classList.add('hidden')); 

    const pageElement = document.getElementById(page);
    if (pageElement) {
        pageElement.classList.remove('hidden');
    } else {
        console.error(`L'élément avec l'ID ${page} n'a pas été trouvé dans le DOM.`);
    }

    if (page === 'list-article') {
        listerArticles();
    }
}
// showPage('list-article');
