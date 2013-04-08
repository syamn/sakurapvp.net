jQuery( function() {
	/* Buttons */
    jQuery( 'a', '.btn-link' ) . button();
    jQuery( 'input:submit', '.btn-send' ) . button();
    jQuery( 'button', '.btn-button' ) . button();
} );

function isValidName(str){
	if (!str.match(/^[A-Za-z0-9_]{2,16}$/)){
		return 1;
	}
	return 0;
}

function isValidMail(str){
	if (!str.match(/^[A-Za-z0-9\.]+[\w-]+@[\w\.-]+\.\w{2,}$/)){
		return 1;
	}
	return 0;
}

function isset( data ){
    return ( typeof( data ) != 'undefined' );
}