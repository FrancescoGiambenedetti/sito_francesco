document.addEventListener('DOMContentLoaded', function () {
    const cookieBanner = document.getElementById('cookieConsentBanner');
    const acceptButton = document.getElementById('acceptCookies');
    const rejectButton = document.getElementById('rejectCookies');
    const revokeButton = document.getElementById('modificaCookies');
    const cookieConsent = getCookie('cookieConsent');
    if (!cookieConsent) {
        cookieBanner.style.display = 'block';
    } else {
        cookieBanner.style.display = 'none';
    }
    acceptButton.addEventListener('click', function () {
        setCookie('cookieConsent', 'accepted', 365);
        cookieBanner.style.display = 'none';
    });
    rejectButton.addEventListener('click', function () {
        setCookie('cookieConsent', 'rejected', 365);
        cookieBanner.style.display = 'none';
        deleteNonEssentialCookies();
    });
    revokeButton.addEventListener('click', function (e) {
        e.preventDefault();
        setCookie('cookieConsent', '', -1);
        deleteNonEssentialCookies();
        cookieBanner.style.display = 'block';
    });
});
function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    const expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
function getCookie(cname) {
    const name = cname + "=";
    const decodedCookie = decodeURIComponent(document.cookie);
    const ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
function deleteNonEssentialCookies() {
    document.cookie.split(";").forEach(function (c) {
        if (c.trim().startsWith('cookieNonEssenziale=')) {
            document.cookie = c + ";expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/";
        }
    });
}