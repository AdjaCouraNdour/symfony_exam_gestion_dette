const API_URL = 'http://localhost:8000/api';

document.addEventListener('DOMContentLoaded', () => {
    const clientInput = document.getElementById('client-number');
    const searchButton = document.getElementById('search-client');  // Mettre à jour ici
    const detteListContainer = document.getElementById('dette-list');
    const montantTotalElement = document.getElementById('montant-total');
    const submitButton = document.getElementById('submit-paiement');  // Mettre à jour ici

    let dettes = [];

    // Fonction pour récupérer les dettes d'un client
    async function fetchDettes(clientNumber) {
        try {
            const response = await fetch(`${API_URL}/clients/${clientNumber}/dettes`, {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' },
            });

            if (!response.ok) {
                throw new Error('Erreur lors de la récupération des dettes');
            }

            return await response.json();
        } catch (error) {
            console.error('Erreur :', error);
            alert(error.message);
            return [];
        }
    }

    // Fonction pour afficher les dettes
    function renderDettes(dettes) {
        detteListContainer.innerHTML = ''; // Nettoyer la liste précédente
        dettes.forEach((dette) => {
            const listItem = document.createElement('div');
            listItem.className = 'dette-item flex items-center py-2 border-b';

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'mr-2';
            checkbox.dataset.montant = dette.montant;

            const label = document.createElement('label');
            label.textContent = `Dette #${dette.id} - Montant : ${dette.montant.toFixed(2)} €`;

            listItem.appendChild(checkbox);
            listItem.appendChild(label);
            detteListContainer.appendChild(listItem);
        });
    }

    // Fonction pour calculer le montant total
    function calculateTotal() {
        const selectedCheckboxes = document.querySelectorAll('.dette-item input[type="checkbox"]:checked');
        let total = 0;
        selectedCheckboxes.forEach((checkbox) => {
            total += parseFloat(checkbox.dataset.montant);
        });
        montantTotalElement.textContent = `Montant total : ${total.toFixed(2)} €`;
    }

    // Gestion du clic sur le bouton de recherche
    searchButton.addEventListener('click', async () => {
        const clientNumber = clientInput.value.trim();
        if (!clientNumber) {
            alert('Veuillez entrer un numéro de client.');
            return;
        }

        dettes = await fetchDettes(clientNumber);
        if (dettes.length === 0) {
            detteListContainer.innerHTML = '<p>Aucune dette trouvée pour ce client.</p>';
        } else {
            renderDettes(dettes);
        }
    });

    // Mise à jour du montant total lors de la sélection des dettes
    detteListContainer.addEventListener('change', calculateTotal);

    // Gestion de l'envoi du paiement
    submitButton.addEventListener('click', async () => {
        const selectedCheckboxes = document.querySelectorAll('.dette-item input[type="checkbox"]:checked');
        const selectedDetteIds = Array.from(selectedCheckboxes).map((checkbox) => checkbox.dataset.montant);

        if (selectedDetteIds.length === 0) {
            alert('Veuillez sélectionner au moins une dette.');
            return;
        }

        try {
            const montantTotal = parseFloat(montantTotalElement.textContent.split(':')[1].trim().replace('€', ''));
            const response = await fetch(`${API_URL}/paiements`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    detteIds: selectedDetteIds,
                    montantTotal,
                }),
            });

            if (!response.ok) {
                throw new Error('Erreur lors de l\'envoi du paiement');
            }

            alert('Paiement effectué avec succès !');
            detteListContainer.innerHTML = '';
            montantTotalElement.textContent = 'Montant total : 0.00 €';
        } catch (error) {
            console.error('Erreur :', error);
            alert(error.message);
        }
    });
});
