
/**
 * Usage: ajax.get(<url>, <callbackSuccess>, <callbackError>);
 * 
 * Example:
 * 
 * ajax.get("/Blog/Test/123", response => {
 *    console.log(response);
 * });
 */
const ajax = {
    get: (url, success, error) => {
        const xhr = new XMLHttpRequest();
        xhr.responseType = "json";
        xhr.addEventListener("load", ev => {
            success(ev.currentTarget.response);
        });
        xhr.addEventListener("error", ev => {
            error(ev.currentTarget.response);
        });
        xhr.open("GET", url);
        xhr.send();
    }
};

