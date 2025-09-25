function fireUnknownError(message) {
    Swal.fire({
        icon: 'error',
        position: 'center',
        text: message ,
        showConfirmButton: false,
        timer: 5000
    })
}
