

function loadPage(page) {
    console.log(`Chargement de la page: ${page}`); // Vérifiez si la page est chargée correctement
    loadHTML(page);
    loadJS(page);
}

function loadHTML(page) {
    const pageContent = document.getElementById('page-content');
    pageContent.innerHTML = ''; // Vider le contenu précédent
    fetch(`${page}.html`)
        .then(response => response.text())
        .then(html => {
            pageContent.innerHTML = html; // Ajouter le nouveau contenu
            console.log(`${page} HTML chargé avec succès`);
        })
        .catch(error => console.log("Erreur lors du chargement du HTML:", error));
}

function loadJS(page) {
    const existingScript = document.getElementById('page-script');
    if (existingScript) {
        existingScript.remove();
    }

    const script = document.createElement('script');
    script.id = 'page-script';
    script.src = `${page}.js`; 
    script.onload = function() {
        console.log(`${page} script chargé avec succès`);
    };
    document.body.appendChild(script);
}
