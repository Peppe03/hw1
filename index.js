/* Menù a tendina titoli*/
function showMenu(event) {
  const titolo = event.currentTarget;
  if (titolo.dataset.titolo === 'prodotti') {
    const menu = document.querySelector('.menu-prodotti');
    menu.classList.remove('hidden');

    menu.addEventListener('mouseleave', hideMenu);
  }
}

function hideMenu() {
  const menu = document.querySelector('.menu-prodotti');
  menu.classList.add('hidden');
}

const titoli = document.querySelectorAll('.titoli .bottom a');

for (const titolo of titoli) {
  titolo.addEventListener('mouseover', showMenu);
}

/* Scorrimento immagini section */

const linkImmaginiSezione = [
  'assets/sectionSlide/spicy-nuggets-evento--carousel-dsk.png',
  'assets/sectionSlide/le-ricche-fries--carousel-dsk_2.png',
  'assets/sectionSlide/gelati--carousel-dsk_5.png',
  'assets/sectionSlide/happy-meal-time--carousel-dsk.png',
  'assets/sectionSlide/waste-recycling--carousel-dsk.png',
  'assets/sectionSlide/eevm--carousel-dsk_3_0.png',
  'assets/sectionSlide/ordina-in-app--carousel-dsk.png',
  'assets/sectionSlide/ambiente--carousel-dsk_0.png',
  'assets/sectionSlide/hm-yugiho--carousel-dsk.png',
  'assets/sectionSlide/compleanno--carousel-dsk.png',
  'assets/sectionSlide/app-mobile--carousel-dsk_1.png',
  'assets/sectionSlide/best-practices--carousel-dsk_1.png'
];

const linkImmaginiSezioneRidimenzionate = [
  'assets/sectionSlideMobile/spicy-nuggets-evento--carousel-mob_2.png',
  'assets/sectionSlideMobile/le-ricche-fries--carousel-mob_2.png',
  'assets/sectionSlideMobile/gelati--carousel-mob_0.png',
  'assets/sectionSlideMobile/happy-meal-time--carousel-mob.png',
  'assets/sectionSlideMobile/waste-recycling--carousel-mob.png',
  'assets/sectionSlideMobile/eevm--carousel-mob_3.png',
  'assets/sectionSlideMobile/ordina-in-app--carousel-mob.png',
  'assets/sectionSlideMobile/ambiente--carousel-mob.png',
  'assets/sectionSlideMobile/hm-yugiho--carousel-mob.png',
  'assets/sectionSlideMobile/compleanno--carousel--mob.png',
  'assets/sectionSlideMobile/app-mobile--carousel-mob_1.png',
  'assets/sectionSlideMobile/best-practices--carousel-mob-1.png'
];

/* Aggiunta bottoni*/

document.addEventListener('DOMContentLoaded', avvioSlide);

let index = 0;
const sezione = document.querySelector('.container-section-slide');
const bottoni = document.querySelectorAll('button[data-index]');

function avvioSlide() {
  slideAutomatico();
  buttonClick();
}

function creaImmagine() {
  let immagine = sezione.querySelector('img');
  if (!immagine) {
    immagine = document.createElement('img');
    sezione.appendChild(immagine);
  }
  return immagine;
}

function slideAutomatico() {
  setInterval(aggiornaImmagine, 5000);
}

function aggiornaImmagine() {
  index = (index + 1) % linkImmaginiSezione.length;
  cambiaImmagine(index);
}

function cambiaImmagine(indice) {
  let immagine = creaImmagine();
  if (window.innerWidth < 500) {
    immagine.src = linkImmaginiSezioneRidimenzionate[indice];
    index = indice;
  } else {
    immagine.src = linkImmaginiSezione[indice];
    index = indice;
  }
}

function buttonClick() {
  for (let i = 0; i < bottoni.length; i++) {
    aggiungiGestoreClick(i);
  }
}

function aggiungiGestoreClick(indiceBottone) {
  bottoni[indiceBottone].addEventListener('click', function () {
    gestisciClickBottone(indiceBottone);
  });
}


function gestisciClickBottone(indiceBottone) {
  index = parseInt(bottoni[indiceBottone].getAttribute('data-index')) - 1;
  cambiaImmagine(index);
}


/* Toggle menu*/

const toggleMenuButton = document.querySelector('#toggle-menu');

function toggleMenu() {
  const menuChiuso = toggleMenuButton.querySelector('.icona-menu-chiuso');
  const menuAperto = toggleMenuButton.querySelector('.icona-menu-aperto');
  const menu = document.querySelector('.toggle-menu-aperto');

  if (menuChiuso.classList.contains('hidden')) {
    menuChiuso.classList.remove('hidden');
    menuAperto.classList.add('hidden');
    menu.classList.add('hidden');
    document.body.classList.remove('no-scroll');
  } else {
    menuChiuso.classList.add('hidden');
    menuAperto.classList.remove('hidden');
    menu.classList.remove('hidden');
    document.body.classList.add('no-scroll');
  }
}

toggleMenuButton.addEventListener('click', toggleMenu);


/* Bottoni menu mobile*/

const bottoniMenuMobile = document.querySelectorAll('.menu-main button[data-nome]');

function controlloMenu(event) {
  const bottone = event.currentTarget;
  const nomeSezione = bottone.getAttribute('data-nome');
  const frecciaGiu = bottone.querySelector('.menu-chiuso');
  const frecciaSu = bottone.querySelector('.menu-aperto');

  if (nomeSezione === 'prodotti') {
    document.querySelector('.menu-main .prodotti').classList.toggle('hidden');
  } else if (nomeSezione === 'mondo-mc') {
    document.querySelector('.menu-main .mondo-mc').classList.toggle('hidden');
  } else if (nomeSezione === 'impegno') {
    document.querySelector('.menu-main .impegno').classList.toggle('hidden');
  }

  frecciaGiu.classList.toggle('hidden');
  frecciaSu.classList.toggle('hidden');
}

for (const bottone of bottoniMenuMobile) {
  bottone.addEventListener('click', controlloMenu);
}




/* Fetch prodotti */


let productQuantities = {};  // quantità dei prodotti

function updateQuantita(productId, newQuantity) {
  console.log("Quantità aggiornata");
  const productCards = document.querySelectorAll('.product-item');
  for (let card of productCards) {
    if (card.dataset.id === productId) {
      const quantitaElement = card.querySelector('.num-quantita');
      quantitaElement.textContent = newQuantity;
    }
  }
  productQuantities[productId] = newQuantity;
}

function decrementoQuantita(event) {
  console.log("Quantita decrementata");
  const bottone = event.currentTarget;
  const productId = bottone.dataset.id;
  let currentQuantity = productQuantities[productId] || 0;

  if (currentQuantity > 0) {
    currentQuantity--;
    updateQuantita(productId, currentQuantity);
  }
}

function incrementoQuantita(event) {
  console.log("Quantità aumentata");
  const bottone = event.currentTarget;
  const productId = bottone.dataset.id;
  let currentQuantity = productQuantities[productId] || 0;

  currentQuantity++;
  updateQuantita(productId, currentQuantity);
}

function fetchResponse(response) {
  if (!response.ok) { return null; }
  return response.json();
}

function fetchProdottiJson(json) {
  console.log("fetching...");
  console.log(json);

  const container = document.querySelector('.product-grid');
  container.innerHTML = '';

  for (let prodotto in json) {
    const card = document.createElement('div');
    card.dataset.id = json[prodotto].id;
    card.classList.add('product-item');

    const infoProdotti = document.createElement('div');
    infoProdotti.classList.add('info-prodotto');
    card.appendChild(infoProdotti);

    const img = document.createElement('img');
    img.src = json[prodotto].img;
    infoProdotti.appendChild(img);

    const nome = document.createElement('span');
    nome.classList.add('nome-prodotto');
    nome.innerHTML = json[prodotto].nome;
    infoProdotti.appendChild(nome);

    const prezzo = document.createElement('span');
    prezzo.classList.add('prezzo-prodotto');
    prezzo.innerHTML = json[prodotto].prezzo + "€";
    infoProdotti.appendChild(prezzo);

    const containerQuantita = document.createElement('div');
    containerQuantita.classList.add('container-quantita');
    infoProdotti.appendChild(containerQuantita);

    const bottoneDecremento = document.createElement('button');
    bottoneDecremento.classList.add('bottone-decremento');
    bottoneDecremento.dataset.id = json[prodotto].id;
    bottoneDecremento.textContent = '-';
    bottoneDecremento.addEventListener('click', decrementoQuantita);
    containerQuantita.appendChild(bottoneDecremento);

    const displayQuantita = document.createElement('div');
    displayQuantita.classList.add('display-quantita');
    const quantita = document.createElement('span');
    quantita.classList.add('num-quantita');
    quantita.innerHTML = 1;
    displayQuantita.appendChild(quantita);
    containerQuantita.appendChild(displayQuantita);

    const bottoneIncremento = document.createElement('button');
    bottoneIncremento.classList.add('bottone-incremento');
    bottoneIncremento.dataset.id = json[prodotto].id;
    bottoneIncremento.textContent = '+';
    bottoneIncremento.addEventListener('click', incrementoQuantita);
    containerQuantita.appendChild(bottoneIncremento);

    const bottoneAggiungi = document.createElement('button');
    bottoneAggiungi.classList.add('add-button');
    bottoneAggiungi.innerHTML = "aggiungi";
    infoProdotti.appendChild(bottoneAggiungi);
    bottoneAggiungi.dataset.id = json[prodotto].id;
    bottoneAggiungi.addEventListener('click', addToCart);


    const containerAddRemove = document.createElement('div');
    containerAddRemove.classList.add('container-add-remove');
    containerAddRemove.appendChild(bottoneAggiungi);
    infoProdotti.appendChild(containerAddRemove);

    container.appendChild(card);
  }


  const containerMobile = document.querySelector('.prodotti');
  const searchMobile = document.querySelector('#search-mobile');
  containerMobile.appendChild(searchMobile);

  for (let prodotto in json) {
    const card = document.createElement('div');
    card.dataset.id = json[prodotto].id;
    card.classList.add('product-item');

    const infoProdotti = document.createElement('div');
    infoProdotti.classList.add('info-prodotto');
    card.appendChild(infoProdotti);

    const img = document.createElement('img');
    img.src = json[prodotto].img;
    infoProdotti.appendChild(img);

    const nome = document.createElement('span');
    nome.classList.add('nome-prodotto');
    nome.innerHTML = json[prodotto].nome;
    infoProdotti.appendChild(nome);

    const prezzo = document.createElement('span');
    prezzo.classList.add('prezzo-prodotto');
    prezzo.innerHTML = json[prodotto].prezzo + "€";
    infoProdotti.appendChild(prezzo);

    const containerQuantita = document.createElement('div');
    containerQuantita.classList.add('container-quantita');
    infoProdotti.appendChild(containerQuantita);

    const bottoneDecremento = document.createElement('button');
    bottoneDecremento.classList.add('bottone-decremento');
    bottoneDecremento.dataset.id = json[prodotto].id;
    bottoneDecremento.textContent = '-';
    bottoneDecremento.addEventListener('click', decrementoQuantita);
    containerQuantita.appendChild(bottoneDecremento);

    const displayQuantita = document.createElement('div');
    displayQuantita.classList.add('display-quantita');
    const quantita = document.createElement('span');
    quantita.classList.add('num-quantita');
    quantita.innerHTML = 1;
    displayQuantita.appendChild(quantita);
    containerQuantita.appendChild(displayQuantita);

    const bottoneIncremento = document.createElement('button');
    bottoneIncremento.classList.add('bottone-incremento');
    bottoneIncremento.dataset.id = json[prodotto].id;
    bottoneIncremento.textContent = '+';
    bottoneIncremento.addEventListener('click', incrementoQuantita);
    containerQuantita.appendChild(bottoneIncremento);

    const bottoneAggiungi = document.createElement('button');
    bottoneAggiungi.classList.add('add-button');
    bottoneAggiungi.innerHTML = "aggiungi";
    infoProdotti.appendChild(bottoneAggiungi);
    bottoneAggiungi.dataset.id = json[prodotto].id;
    bottoneAggiungi.addEventListener('click', addToCart);


    const containerAddRemove = document.createElement('div');
    containerAddRemove.classList.add('container-add-remove');
    containerAddRemove.appendChild(bottoneAggiungi);
    infoProdotti.appendChild(containerAddRemove);

    containerMobile.appendChild(card);
  }

  // Inizializza le quantità a 1 per tutti i prodotti
  for (let prodotto in json) {
    productQuantities[json[prodotto].id] = 1;
  }

}

function fetchProdotti() {
  fetch("fetch_prodotti.php").then(fetchResponse).then(fetchProdottiJson);
}

fetchProdotti();

function dispatchError(error) {
  console.log("Errore", error);
}

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

function addToCart(event) {
  console.log("Aggiungo al carrello");
  const button = event.currentTarget;
  const productId = button.dataset.id;
  const quantity = productQuantities[productId] || 0;

  const formData = new FormData();
  formData.append('id', productId);
  formData.append('quantita', quantity);

  fetch("add_to_cart.php", { method: 'post', body: formData }).then(dispatchResponse, dispatchError);
  event.stopPropagation();
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





/* Carrello laterale*/

function fetchCarrello(json) {
  console.log(json);
  const carrello = document.querySelector('.carrello');
  const chiudiCarrello = document.querySelector('.chiudi-carrello');
  carrello.innerHTML = "";
  carrello.appendChild(chiudiCarrello);

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
  const recap = document.createElement('div');
  recap.classList.add('recap');

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

  const checkOut = document.createElement('a');
  checkOut.classList.add('checkout');
  checkOut.href = "cart.php";
  checkOut.textContent = "check out";

  carrello.appendChild(recap);
  carrello.appendChild(checkOut);
}


function mostraCarrello() {
  const carrello = document.querySelector('.carrello');
  const overlay = document.querySelector('.overlay');
  const body = document.body;
  const login = document.createElement('a');
  login.href = 'login.php';
  login.classList.add('login-carrello');
  login.textContent = "Login";

  if (carrello.classList.contains('hidden')) {
    carrello.classList.remove('hidden');
    carrello.classList.add('visible');
    overlay.classList.remove('hidden');
    overlay.classList.add('visible');
    body.classList.add('modal-open');
  } else {
    carrello.classList.add('hidden');
    carrello.classList.remove('visible');
    overlay.classList.add('hidden');
    overlay.classList.remove('visible');
    body.classList.remove('modal-open');
  }
  carrello.appendChild(login);


}

function chiudiCarrello() {
  const carrello = document.querySelector('.carrello');
  const overlay = document.querySelector('.overlay');
  const body = document.body;
  const login = document.querySelector('.login-carrello');
  carrello.removeChild(login);

  carrello.classList.add('hidden');
  carrello.classList.remove('visible');
  overlay.classList.add('hidden');
  overlay.classList.remove('visible');
  body.classList.remove('modal-open');
}

document.querySelector('.bottone-carrello').addEventListener('click', mostraCarrello);
document.querySelector('.chiudi-carrello').addEventListener('click', chiudiCarrello);
document.querySelector('.overlay').addEventListener('click', chiudiCarrello);







