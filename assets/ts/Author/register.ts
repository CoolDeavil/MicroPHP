function main() {
    document.querySelector('.captcha_').addEventListener('click',(e)=>{
        e.preventDefault();
        captchaReset(`http://${window.location.hostname}:${window.location.port}/api/author/reset-captcha`).then((r:any)=>{
            (document.querySelector('.js_captcha') as HTMLImageElement).src = r.image;
        })
    }, false )

}
function captchaReset(url:string){
    return new Promise((resolve,reject)=>{

        let xhr = new XMLHttpRequest();
        xhr.open('GET', url);
        xhr.send();

        xhr.onload = function() {
            if (xhr.status != 200) { // analyze HTTP status of the response
                console.log(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
                reject(xhr.statusText);
            } else { // show the result
                console.log(`Done, got ${xhr.response.length} bytes`); // response is the server response
                resolve(JSON.parse(xhr.responseText));
            }
        };

        xhr.onprogress = function(event) {
            if (event.lengthComputable) {
                console.log(`Progress Received ${event.loaded} of ${event.total} bytes`);
            } else {
                console.log(`Received ${event.loaded} bytes`); // no Content-Length
            }

        };

        xhr.onerror = function() {
            console.log("Request failed");
            reject();
        };


    })
}


if (document.readyState === 'complete') {
    main()
} else {
    document.addEventListener('DOMContentLoaded', main);
}
