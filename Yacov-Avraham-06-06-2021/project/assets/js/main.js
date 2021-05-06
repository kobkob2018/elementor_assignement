let popup_show = false;

const toggle_popup = (orientation = null) => {
    if(orientation){
        show_popup();
    }
    else{
       hide_popup();
    }
}
const hide_popup = () => {
    $("#popup_box").addClass("hide");
	$("#popup_box").removeClass("show");
	$("#popup_box").find(".controlls").addClass("hidden");
    popup_show = false;
};

const show_popup = () => {
    $("#popup_box").removeClass("hide");
	$("#popup_box").addClass("show");
    popup_show = true;
}

const init_popup_with_loader = () => {
    $("#popup_box").find("#popup_place_holder").html('<div class="loader">Loading...</div>');
	show_popup();
}

const show_popup_purchase_controlls = () => {
	$("#popup_box").find(".controlls").addClass("hidden");
	$("#popup_box").find("#purchase_controlls").removeClass("hidden");
}

const show_popup_error_controlls = () => {
	$("#popup_box").find(".controlls").addClass("hidden");
	$("#popup_box").find("#error_controlls").removeClass("hidden");
}

const show_popup_with_error = (message) => {
	$("#popup_box").find("#popup_place_holder").html('<div class="error">'+ message +'</div>');
	show_popup_error_controlls();
}

const show_popup_with_product = (product) => {
	$("#popup_box").find("#popup_place_holder").html('<div class="description">'+ product.description +'</div>');
	if($("#product_"+product.id).length !== 0){
		const description = $("#popup_box").find(".description");
		$("#product_"+product.id).find("h3").clone().prependTo(description);
	}
	show_popup_purchase_controlls();
}

const purchase_product = (product_id) => {
    init_popup_with_loader();
	
	const avoidCacheGetParam = Date.now() + Math.random();
	const apiUrl = "api/products.php?product=" + product_id + "&rnd=" + avoidCacheGetParam;
    
    $.ajax({
		dataType: "json",
        url: apiUrl,
      }).done(function(result) {
		  console.log(result);
        if(!result || result.success !== true ){
			let errorMessage = "Connection problem";
			if(result && result.error){
				errorMessage = result.error;
			}
			show_popup_with_error(errorMessage);
		}
		else{
			show_popup_with_product(result.product);
		}
      }).fail(function (jqXHR, textStatus){
		  show_popup_with_error("Connection problems...");
	  });
}

const add_to_cart = () => {
	hide_popup();
	init_popup_with_loader();
	setTimeout(
		()=>{
			show_popup_with_error("We currently are working on the add to cart fuctionality...");
		},1000
	)
	 
}