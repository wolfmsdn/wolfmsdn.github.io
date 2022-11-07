loading_open();
App_Main = $('.App_Main');
loadModHtml(App_Main, './js/mod/Favorites.html', {}, loadCallback);

function loadCallback() {
	loadProperties(isEng);
	loading_close();
}