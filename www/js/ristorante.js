function get_risto_data(reset = false) {
	if (typeof this.risto === "undefined" || reset) {
		this.risto = {};
	}
	return this.risto;
}

function load_servizi() {
	var risto_data = get_risto_data();
	$.getJSON('config/sincronizzazione/ristorante.php',{request: "servizi"},function(data) {
		risto_data.servizi = data;
		console.log(risto_data);
	});
}

function load_tavoli() {
	var risto_data = get_risto_data();
	$.getJSON('config/sincronizzazione/ristorante.php',{request: "tavoli"},function(data) {
		risto_data.tavoli = data;
		console.log(risto_data);
	});
}

function start_ristorante() {
	var risto_data = get_risto_data(true);

	load_servizi();
	load_tavoli();
}
