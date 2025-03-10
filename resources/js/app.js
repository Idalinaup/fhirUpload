import './bootstrap';

import toastr from 'toastr';
toastr.success('Arquivo enviado com sucesso!');

function mudarCSS(caminhoCSS) {
    const basePath = document.querySelector('meta[name="asset-path"]').getAttribute('content'); // Fetch asset path
    const themeStyle = document.getElementById('themeStyle'); // Get the stylesheet link element

    if (themeStyle) {
        themeStyle.setAttribute('href', basePath + caminhoCSS); // Update the CSS path dynamically
    } else {
        console.error('Elemento com id "themeStyle" não encontrado.');
    }
}

// Attach to the global window object
window.mudarCSS = mudarCSS;



