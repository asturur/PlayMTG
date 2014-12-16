var SALVA_TEXT = 'Salva';
var LEFT_START = 5;
function src_card(card_id) {
  return 'http://mtgimage.com/multiverseid/' + card_id + '.jpg';
}

mostra_carta = function(event) {
  card_id = event.data.id;
  $("#image_card > img").attr('src', src_card(card_id));
}

avvia_ricerca = function() {
  $.ajax({
    type: 'post',
    url: 'services/search_card.php',
    data: $("#ricerca").serialize(),
    success: function(data){
      $('#search_result').empty();
      for (var i = 0; i < data.cards.length; i++) {
        var card = data.cards[i];
        var el = $("<div></div>");
        el.html(card.name);
        el.click(card, mostra_carta);
        $('#search_result').append(el);
      }
    },
    dataType: 'json'
  });  
};

crea_nuovo_mazzo = function() {
  var htmlCreaMazzo = 'Inserisci il nome del mazzo:<br /><input type="text" id="deckname" name="nome_mazzo" value="" />';

  function salva() {
    var nome = $('#deckname').val();
    $.ajax({
			type: 'post',
			url: 'services/new_deck.php',
			data: {
				deckname: nome
			},
			success: function(dati) {
  					if (dati.success == true) {
              crea_tasto_mazzo(dati.nome, dati.id);
              $('#dialog').dialog("close");
            } else {
              $('#dialog').html(dati.msg);
            }
					},
			error: function() { $("#dialog").html('<p>Errore riprova.</p>').dialog({modal: true }); }
		});
  }

  function crea_tasto_mazzo(nome,key) {
    $('#lista_mazzi').append('<div class="deck_button" onclick="modifica_mazzo(\''+ key +'\');" >' + nome + '</div>');
  }

  $("#dialog").html(htmlCreaMazzo).dialog({modal: true, title: 'Creazione nuovo mazzo', buttons: [{ text: SALVA_TEXT, click: salva }]});
}

modifica_mazzo = function(key) {
  // mostro la zona mazzo
  // creo un canvas
  // aggiungo tutte le carte al canvas
  $('#contenuto').empty();
  canvas.clear();
  left = LEFT_START;
  id_deck = key;
  $.ajax({
			type: 'post',
			url: 'services/load_deck.php',
			data: {
				id_deck: id_deck,
			},
			success: function(dati) {
  					if (dati.success == true) {
              deck.title = dati.title;
              deck.cards = dati.deck;
              $('#deck_mod_title').html(deck.title);
              for(var i=0; i < deck.cards.length; i++) {
                var card = deck.cards[i];
                carta_su_tavolo(card.id, 1, card.name, card.unique_id);
              }
            }
					},
			error: function() { $("#dialog").html('<p>Errore riprova.</p>').dialog({modal: true }); }
		});
}

carta_su_tavolo =function(id_card, qta, title, id) {
  var url_carta = src_card(id_card);
  fabric.Image.fromURL(url_carta, function(oImg) {
    oImg.scaleToWidth(100);
    oImg.unique_id = id;
    oImg.left = left;
    left += 30;
    oImg.top = 20;
    canvas.add(oImg);
  });
  $('#contenuto').append('<div class="lista_carte" onclick="" >' + title + '</div>');

}

inserisci_carta = function() {
  
      $.ajax({
			type: 'post',
			url: 'services/add_card_deck.php',
			data: {
				id_deck: id_deck,
        id_card: card_id
			},
			success: function(dati) {
  					if (dati.success == true) {
              var qta = 1;
              carta_su_tavolo(dati.id_card,qta,dati.name);
            } else {
              $('#dialog').html(dati.msg);
            }
					},
			error: function() { $("#dialog").html('<p>Errore riprova.</p>').dialog({modal: true }); }
		});
  
  
}
var canvas;
var id_deck;
var card_id;
var deck = { };
var left = 5;
var top = 5;
$(document).ready(function() {
   canvas = new fabric.Canvas('tavolo');
   canvas.setWidth(1000);
   canvas.setHeight(600);
   $('#search').keyup(avvia_ricerca);
});

//http://mtgimage.com/multiverseid/<multiverseid>.jpg