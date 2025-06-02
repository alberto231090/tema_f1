document.addEventListener("scroll", function lazyAdsenseInit() {
    if (window.adsbygoogleLoaded) return;
    window.adsbygoogleLoaded = true;

    const script = document.createElement("script");
    script.src = "https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js";
    script.async = true;
    script.setAttribute("data-ad-client", "ca-pub-1892473664508324"); // <-- inserisci il tuo ID qui
    document.head.appendChild(script);

    document.removeEventListener("scroll", lazyAdsenseInit);
});
