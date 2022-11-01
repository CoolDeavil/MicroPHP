function main() {

    document.querySelector('.profileForm').addEventListener('submit',(e)=>{
        e.preventDefault();
        if (confirm("Confirm Changes to Profile?") === true) {
            (document.querySelector('.profileForm') as HTMLFormElement).submit();
        } else {
            console.log('Canceled');
        }
    }, false )

}

if (document.readyState === 'complete') {
    main()
} else {
    document.addEventListener('DOMContentLoaded', main);
}
