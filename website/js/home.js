
addEventListener("mousemove", (event) => {
    console.log("OI");
    document.getElementById("imagem-vidro").style.transform = `translate(${event.clientX / 100}px, ${event.clientY / 100}px)`;
});