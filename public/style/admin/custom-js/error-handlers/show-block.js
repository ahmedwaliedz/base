function fireBlockAction(message) {
    Swal.fire({
        icon: 'error',
        position: 'top-start',
        text: message,
        showConfirmButton: false,
        timer: 2000
    })
}
