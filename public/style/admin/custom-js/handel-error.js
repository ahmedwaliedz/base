function handelErrorByStatus(xhr , form) {
    if (xhr.status === 422) {
        addValidationError(xhr.responseJSON.errors ,form)
    }else if(xhr.status === 423){
        fireBlockAction(xhr.responseJSON.message)
    }else if(xhr.status === 400){
        fireUnAuthorizedAction(xhr.responseJSON.message)
    }
}

