let productQuantities = {};

function dataBaseResponse(json) {
    if (!json.ok) {
        dispatchError();
        return null;
    }
    fetch("from_cart.php").then(fetchResponse).then(fetchCarrello);
}


function dispatchResponse(response) {
    console.log(response);
    return response.json().then(dataBaseResponse);
}

function dispatchError(error) {
    console.log("Errore", error);
}

function removeFromCart(event) {
    console.log("Rimuovo dal carrello");
    const button = event.currentTarget;
    const productId = button.dataset.id;
    const quantity = productQuantities[productId] || 0;

    const formData = new FormData();
    formData.append('id', productId);
    formData.append('quantita', quantity);

    fetch("remove_from_cart.php", { method: 'post', body: formData }).then(dispatchResponse, dispatchError);
    event.stopPropagation();
}

function fetchResponse(response) {
    if (!response.ok) { return null; }
    return response.json();
}

function fetchCarrello(json) {
    console.log(json);
    const carrello = document.querySelector('.griglia-carrello');
    carrello.innerHTML = "";

    for (let prodotto in json.prodotti) {
        const card = document.createElement('div');
        card.dataset.id = json.prodotti[prodotto].id;
        card.classList.add('product-item');

        const infoProdotti = document.createElement('div');
        infoProdotti.classList.add('info-prodotto');
        card.appendChild(infoProdotti);

        const img = document.createElement('img');
        img.src = json.prodotti[prodotto].img;
        infoProdotti.appendChild(img);

        const nome = document.createElement('span');
        nome.classList.add('nome-prodotto');
        nome.innerHTML = json.prodotti[prodotto].nome;
        infoProdotti.appendChild(nome);

        const containerQuantita = document.createElement('div');
        containerQuantita.classList.add('container-quantita');
        infoProdotti.appendChild(containerQuantita);

        const displayQuantita = document.createElement('div');
        displayQuantita.classList.add('display-quantita');
        const quantita = document.createElement('span');
        quantita.classList.add('num-quantita');
        quantita.innerHTML = "x" + json.prodotti[prodotto].quantita;
        displayQuantita.appendChild(quantita);
        containerQuantita.appendChild(displayQuantita);

        const bottoneRimuovi = document.createElement('button');
        bottoneRimuovi.classList.add('remove-button');
        bottoneRimuovi.innerHTML = 'rimuovi';
        infoProdotti.appendChild(bottoneRimuovi);
        bottoneRimuovi.dataset.id = json.prodotti[prodotto].id;
        bottoneRimuovi.addEventListener('click', removeFromCart);

        const containerAddRemove = document.createElement('div');
        containerAddRemove.classList.add('container-add-remove');
        containerAddRemove.appendChild(bottoneRimuovi);
        infoProdotti.appendChild(containerAddRemove);

        carrello.appendChild(card);
    }
    const containerRecap = document.querySelector('.container-recap');
    const recap = document.querySelector('.recap');
    recap.innerHTML = "";

    let totaleProdotti = document.createElement('div');
    totaleProdotti.classList.add('totale-prodotti');
    let titoloTotaleProdotti = document.createElement('h3');
    titoloTotaleProdotti.textContent = "Tot. Prodotti";
    totaleProdotti.appendChild(titoloTotaleProdotti);

    let numProdotti = document.createElement('span');
    numProdotti.textContent = json.numero_totale_prodotti;
    totaleProdotti.appendChild(numProdotti);

    recap.appendChild(totaleProdotti);

    let prezzoTotale = document.createElement('div');
    prezzoTotale.classList.add('totale');
    let titoloPrezzo = document.createElement('h3');
    titoloPrezzo.textContent = "Tot:";
    prezzoTotale.appendChild(titoloPrezzo);

    let prezzo = document.createElement('span');
    prezzo.textContent = "€" + json.totale;
    prezzoTotale.appendChild(prezzo);

    recap.appendChild(prezzoTotale);

    const checkOut = document.querySelector('.compra');
    checkOut.textContent = "Acquista";

    containerRecap.appendChild(recap);
    containerRecap.appendChild(checkOut);
}

fetch("from_cart.php").then(fetchResponse).then(fetchCarrello);
