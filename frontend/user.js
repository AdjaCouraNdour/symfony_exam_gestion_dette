const API_URL = 'http://localhost:8000/api';

document.addEventListener('DOMContentLoaded', listerUsers);

async function listerUsers() {
    try {
        const response = await fetch(`${API_URL}/users`);
        if (!response.ok) {
            const error = await response.json();
            alert(`Erreur : ${error.message}`);
            return;
        }
        
        const users = await response.json();
        const usersBody = document.getElementById('users-body');
        usersBody.innerHTML = ''; // Réinitialiser le tableau
        
        users.forEach(user => {
            const row = `
                <tr>
                    <td class="py-2 px-4 border">${user.prenom}</td>
                    <td class="py-2 px-4 border">${user.nom}</td>
                    <td class="py-2 px-4 border">${user.login}</td>
                    <td class="py-2 px-4 border">${user.role}</td>
                </tr>
            `;
            usersBody.innerHTML += row;
        });
    } catch (err) {
        console.error('Erreur lors de la récupération des utilisateurs:', err);
        alert('Impossible de lister les utilisateurs.');
    }
}

async function createUser() {
    const login = document.getElementById('login').value;
    const nom = document.getElementById('nom').value;
    const prenom = document.getElementById('prenom').value;
    const password = document.getElementById('password').value;
    const role = document.getElementById('role').value;
    
    try {
        const response = await fetch(`${API_URL}/users`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({login, nom, prenom, password, role})
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Error response:', errorText);
            const error = JSON.parse(errorText);  // Make sure it's valid JSON
            alert(`Erreur : ${error.message}`);
        } else {
            alert('Utilisateur créé avec succès !');
        }
    } catch (err) {
        console.error('Erreur lors de la création de utilisateur:', err);
    }
}

async function searchUser() {
    const phone = document.getElementById('phone').value;

    try {
        const response = await fetch(`${API_URL}/users/search?phone=${phone}`, {
            method: 'GET',
            headers: {'Content-Type': 'application/json'}
        });

        if (response.ok) {
            const user = await response.json();
            alert(`Utilisateur trouvé : ${user.nom} ${user.prenom}`);
        } else {
            const error = await response.json();
            alert(`Erreur : ${error.message}`);
        }
    } catch (err) {
        console.error('Erreur lors de la recherche de l\'utilisateur:', err);
    }
}

async function editUser(id) {
    const login = document.getElementById('edit-login').value;
    const nom = document.getElementById('edit-nom').value;
    const prenom = document.getElementById('edit-prenom').value;

    try {
        const response = await fetch(`${API_URL}/users/edit/${id}`, {
            method: 'PATCH',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({login, nom, prenom})
        });

        if (response.ok) {
            alert('Utilisateur mis à jour avec succès !');
            listerUsers();
        } else {
            const error = await response.json();
            alert(`Erreur : ${error.message}`);
        }
    } catch (err) {
        console.error('Erreur lors de la mise à jour de l\'utilisateur:', err);
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

    if (page === 'list-user') {
        listerUsers();
    }
}
// showPage('create-user');
