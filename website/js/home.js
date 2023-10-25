
addEventListener("mousemove", (event) => {
    document.getElementById("imagem-vidro").style.transform = `translate(${event.clientX / 100}px, ${event.clientY / 100}px)`;
});