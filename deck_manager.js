var SALVA_TEXT = 'Salva';

mostra_carta = function(event) {
  card_id = event.data.id;
  $("#image_card > img").attr('src', 'http://mtgimage.com/multiverseid/' + card_id + '.jpg');
}

avvia_ricerca = function() {
  $.ajax({
    type: 'post',
    url: 'services/search_card.php',
    data: $("#ricerca").serialize(),
    success: function(data){
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
  id_deck = key;
}

carta_su_tavolo =function(urlcarta, qta) {
  
  fabric.Image.fromURL(urlcarta, function(oImg) {
    oImg.scaleToWidth(100);
    canvas.add(oImg);
  });

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
              var imgElement = document.getElementById("card");
              carta_su_tavolo(imgElement.src, qta);
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

$(document).ready(function() {
   canvas = new fabric.Canvas('tavolo');
});

//http://mtgimage.com/multiverseid/<multiverseid>.jpg