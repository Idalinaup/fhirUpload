function mudarCSS(caminhoCSS) { // Add this line
    const basePath = document.querySelector('meta[name="asset-path"]').getAttribute('content');
    const themeStyle = document.getElementById('themeStyle');
    console.log(themeStyle);

    if (themeStyle) {
        themeStyle.setAttribute('href', basePath + caminhoCSS);
    } else {
        console.error('Elemento com id "themeStyle" não encontrado.');
    }
}

window.mudarCSS = mudarCSS;
