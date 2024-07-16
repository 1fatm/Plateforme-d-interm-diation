document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formPPM');
    const intitule = document.getElementById('intitule');
    const reference = document.getElementById('reference');
    const dateLancement = document.getElementById('date_lancement');
    const dateAttribution = document.getElementById('date_attribution');
    const description = document.getElementById('description');
    const norme = document.getElementById('norme');
    const typeActivites = document.getElementById('typeActivites');
    const activites = document.getElementById('activites');
    const tableBody = document.getElementById('tablebody');

    const errorMessage = {
        intitule: document.getElementById('erreur_intitule'),
        reference: document.getElementById('erreur_ref'),
        dateLancement: document.getElementById('erreur_date_lancement'),
        dateAttribution: document.getElementById('erreurdatattri'),
        description: document.getElementById('erreur_description'),
        norme: document.getElementById('erreur_norme'),
        typeActivites: document.getElementById('erreurtypeact'),
        activites: document.getElementById('erreur_activite'),
    };

    const services = ["Soudure", "Hébergement", "Visites médicales", "Levés géophysiques au sol", "Levés pour la cartographie géologique", "Sondage minier", "Forage de contrôle de teneur", "Forage de dynamitage", "Chargement du minerai", "Extraction du minerai", "Forage hydraulique", "Analyses d'échantillon", "Construction de bâtiment, retenues d'eau, ouvrage d'affranchissement pistes et routes", "Câblages ou extension des réseaux informatiques", "Construction de parcs résidus (travaux de terrassements)"];

    const travaux = ["Levés géophysiques au sol", "Levés pour la cartographie géologique", "Sondage minier", "Forage de contrôle de teneur", "Forage de dynamitage", "Chargement du minerai", "Extraction du minerai", "Forage hydraulique", "Analyses d'échantillons", "Construction de bâtiment, retenues d'eau, ouvrage d'affranchissement pistes et routes", "Câblages ou extension des réseaux informatiques", "Construction de parcs à résidus (travaux de terrassements)"];

    const fournitures = ["Carburants et lubrifiants", "Pièces de rechanges << véhicules légers >>, << engins lourds >>, << équipements fixes >>", "Pneumatique << véhicules légers >> et << engins lourds >>", "Matériel de bureau", "Produits alimentaires", "Equipements de production d'énergie thermique", "Equipements de production d'énergie solaire", "Equipement et protection individuelle courants", "Cyanure", "Borax", "Chaux", "Substances explosives", "Boulets", "Autres produits chimiques utilisés dans le traitement du minerai", "Confection de tenues", "Literie / linge de maison"];

    function setMinDate(today) {
        dateLancement.setAttribute("min", today);
        dateAttribution.setAttribute("min", today);
    }

    function validateField(field, errorMessage) {
        if (field.value.trim() === '') {
            field.classList.add('error');
            errorMessage.style.display = 'block';
            return false;
        } else {
            field.classList.remove('error');
            errorMessage.style.display = 'none';
            return true;
        }
    }

    form.addEventListener('submit', function(event) {
        let isValid = true;

        isValid &= validateField(intitule, errorMessage.intitule);
        isValid &= validateField(reference, errorMessage.reference);
        isValid &= validateField(dateLancement, errorMessage.dateLancement);
        isValid &= validateField(dateAttribution, errorMessage.dateAttribution);
        isValid &= validateField(description, errorMessage.description);
        isValid &= validateField(norme, errorMessage.norme);
        isValid &= validateField(typeActivites, errorMessage.typeActivites);
        isValid &= validateField(activites, errorMessage.activites);

        if (!isValid) {
            event.preventDefault();
        }
    });

    let today = new Date().toISOString().split('T')[0];
    setMinDate(today);

    typeActivites.addEventListener('change', function() {
        activites.innerHTML = ''; 
        let activitiesArray;
        if (typeActivites.value === '1') {
            activitiesArray = travaux;
        } else if (typeActivites.value === '2') {
            activitiesArray = services;
        } else if (typeActivites.value === '3') {
            activitiesArray = fournitures;
        }
        activitiesArray.forEach(function(activite) {
            const option = document.createElement('option');
            option.textContent = activite;
            activites.appendChild(option);
        });
    });
    
});
