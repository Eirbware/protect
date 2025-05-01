// Some preprocessing is required
const defaultProtectOrigin = {protectOrigin: window.origin}

const defaultLoginOptions = {
    redirect: window.origin + location.pathname,
    ...defaultProtectOrigin,
};
const defaultDataOptions = defaultProtectOrigin;
const defaultLogoutOptions = {redirect: window.origin, ...defaultProtectOrigin};
const defaultRedirectOptions = defaultProtectOrigin;


// Protect uses url defined session ids, it is processed by the lib to abtract
// it from the end user and the dev
const urlParams = new URLSearchParams(window.location.search);
/* if getItem is null or "", then we take from url */
const PHPSESSID = (sessionStorage.getItem("PHPSESSID") ||
    urlParams.get("PHPSESSID")) ??
    "";
urlParams.delete("PHPSESSID");  // Stored in sessionStorage now
const new_url = window.origin + location.pathname +
    ((urlParams.size > 0) ? `?${urlParams.toString()}`: "");
history.replaceState(null, document.title, new_url);

/* getItem is null or "" */
if (!sessionStorage.getItem("PHPSESSID") && PHPSESSID !== "")
    sessionStorage.setItem("PHPSESSID", PHPSESSID);

const protectRedirect = urlParams.get("protectRedirect")
if (protectRedirect)
    window.location = protectRedirect


/**
 *
 * Generate a string like "PHPSESSID=..." the global PHPSESSID variable is set,
 * empty string otherwise. Prefix is mainly meant to add a query separator if
 * PHPSESSID is set, example : "&PHPSESSID=...." with prefix="&"
 *
 * @param {string} {prefix}?
 * @returns {string}
 */
function SID(prefix = "") {
    if (PHPSESSID === "")
        return "";

    return `${prefix}PHPSESSID=${PHPSESSID}`;
}


/**
 *
 * Resets the storage used by the lib
 *
 * @returns {void}
 */
function resetStorages() {
    sessionStorage.setItem("PHPSESSID", "");
    localStorage.setItem("data", "");
    localStorage.setItem("data_expires", "");
}


/**
 *
 * Returns true if the user is logged in, false otherwise
 *
 * @returns {void}
 */
function isLoggedIn() {
    const expires = localStorage.getItem("data_expires");
    return Date.now() < parseInt(expires)
}


/**
 *
 * Redirect the browser to login page, login if required. After login, the
 * browser redirects by default to the current URL.
 *
 * @param {{ redirect: string protectOrigin: string }} { redirect, protectOrigin }?
 * @returns {void}
 */
function login(options = defaultLoginOptions) {
    const { redirect, protectOrigin } = {...defaultLoginOptions, ...options};

    const url = `${protectOrigin}/protect/login.php?redirect=${redirect}${SID('&')}`;
    window.location = url;
}


/**
 *
 * Returns protected data and the user data from the user currently logged in
 *
 * @param {{ protectOrigin: string }} { protectOrigin }?
 * @returns {Promise<any>}
 */
async function getData(options = defaultDataOptions) {
    // First try to get data from cache (localStorage)
    const localData = localStorage.getItem("data");
    if (isLoggedIn() && localData)
        return JSON.parse(localData);
    else
        resetStorages();

    // Then, try to get data from protect API
    const { protectOrigin } = {...defaultDataOptions, ...options};
    const getDataURL = `${protectOrigin}/protect/getData.php${SID('?')}`
    const response = await fetch(getDataURL);

    switch (response.status) {
        case 200:
            const data = await response.json();

            localStorage.setItem("data", JSON.stringify(data));
            localStorage.setItem("data_expires", Date.now() + 24 * 3600);
            return data;
        case 401:
            throw new Error("Veuillez vous connecter dans un premier temps");
        default:
            throw new Error("Erreur inconnue, contactez un membre d'Eirbware")
    }
}


/**
 *
 * Redirect the browser to logout page. After logout, the browser redirects by
 * default to '/'.
 *
 * @param {{ redirect: string protectOrigin: string }} { redirect, protectOrigin }?
 * @returns {void}
 */
function logout(options = defaultLogoutOptions) {
    const { redirect, protectOrigin } = {...defaultLogoutOptions, ...options};
    const logoutURL = `${protectOrigin}/protect/logout.php?redirect=${redirect}${SID('&')}`;

    resetStorages();
    window.location = logoutURL;
}


/**
 *
 * Redirect the browser to the protected url identified with redirectId.
 *
 * @param {string} redirectId
 * @param {{ protectOrigin: string }} {protectOrigin}?
 * @returns {void}
 */
function redirect(redirectId, options = defaultRedirectOptions) {
    const { protectOrigin } = {...defaultDataOptions, ...options};
    const redirectURL = `${protectOrigin}/protect/link.php?name=${redirectId}&redirect=${window.origin + location.pathname}${SID('&')}`;

    window.location = redirectURL;
}


export default {
    login,
    getData,
    isLoggedIn,
    logout,
    redirect,
};
