function fireUnAuthorizedAction(message) {
    Swal.fire({
        icon: 'error',
        position: 'center',
        text: message ,
        showConfirmButton: false,
        timer: 2000
    })
}
