<style id="egate-kiosk-styles">
    html.egate-kiosk,
    html.egate-kiosk body {
        width: 100%;
        height: 100%;
        margin: 0;
        overflow: hidden;
        overscroll-behavior: none;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    html.egate-kiosk::-webkit-scrollbar,
    html.egate-kiosk body::-webkit-scrollbar {
        display: none;
        width: 0;
        height: 0;
    }

    html.egate-kiosk body {
        position: fixed;
        inset: 0;
        width: 100vw;
        height: 100vh;
        height: 100dvh;
        max-width: 100vw;
        max-height: 100dvh;
        touch-action: manipulation;
    }
</style>
