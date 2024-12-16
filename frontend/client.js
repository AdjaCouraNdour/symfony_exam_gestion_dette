const API_URL = 'http://localhost:8000/api';

document.addEventListener('DOMContentLoaded', listerClients);

async function listerClients() {
    try {
        const response = await fetch(`${API_URL}/clients`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        });

        if (response.ok) {
            const clients = await response.json();
            const tbody = document.getElementById('clients-body');

            // Vider le tbody avant d'ajouter de nouvelles lignes
            tbody.innerHTML = '';

            // Ajouter les lignes pour chaque client
            clients.forEach(client => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="py-2 px-4 border">${client.surname}</td>
                    <td class="py-2 px-4 border">${client.telephone}</td>
                    <td class="py-2 px-4 border">${client.adresse}</td>
                `;
                tbody.appendChild(row);
            });
        } else {
            const error = await response.json();
            alert(`Erreur : ${error.message}`);
        }
    } catch (err) {
        console.error('Erreur lors de la récupération des clients :', err);
    }
}

async function createClient() {
    const surname = document.getElementById('surname').value;
    const telephone = document.getElementById('telephone').value;
    const adresse = document.getElementById('adresse').value;

    try{
        const response = await fetch(`${API_URL}/clients`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body:JSON.stringify({surname,telephone,adresse})
        });
        if(response.ok){
            alert('Client créé avec succès !');
        }else{
            const error = await response.json();
            alert(`Erreur : ${error.message}`);
        }
    }catch(err){
        console.error('Erreur lors de la création du client:', err);
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

    if (page === 'list-client') {
        listerClients();
    }
}

// showPage('list-client');
