/*
 * Redirect the browser to login page, login if required. After login, the
 * browser redirects by default to the current URL.
 */
function login(redirect = window.location.origin + window.location.pathname) {
    window.location = `${window.location.origin}/protect/login.php?redirect=${redirect}`;
}


/*
 * Returns protected data and the user data from the user currently logged in
 * @throws Error if the user is not currently logged in or if data fetching failed
 * @returns {Promise<object>} json containing protected data and user data
 * @example
 * // returns {user: {uid: "ndacremont", etc: "..."}, protected: {video: "https://www.youtube.com/watch?v=dQw4w9WgXcQ"}}
 */
async function getData() {
    const response = await fetch("/protect/getData.php");

    switch (response.status) {
        case 200:
            return response.json();
        case 401:
            throw new Error("Veuillez vous connecter dans un premier temps");
        default:
            throw new Error("Erreur inconnue, contactez un membre d'Eirbware")
    }
}


/*
 * Redirect the browser to logout page. After logout, the browser redirects by
 * default to '/'.
 */
function logout(redirect = window.location.origin) {
    window.location = `/protect/logout.php?redirect=${redirect}`;
}


/*
 * Redirect the browser to logout page. After logout, the browser redirects by
 * default to '/'.
 */
function redirect(redirectId) {
    window.location = `/protect/link.php?name=${redirectId}`;
}


export default {
    login,
    getData,
    logout,
    redirect,
};
