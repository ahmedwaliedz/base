<div class="modal fade" id="emailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
        <div class="modal-content">
            <div class="modal-body p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="mb-4">
                    <h3 class="address-title mb-2">ارسال ايميل</h3>
                    <!-- <p class="text-muted address-subtitle">من فضلك ادخل رسالتك</p> -->
                </div>
                <form id="addNewAddressForm" class="row g-3" onsubmit="return false">

                    <div class="col-12 col-md-12">
                        <label class="form-label" for="modalAddressLastName">الرسالة بالعربية</label>
                        <textarea name="" class="form-control" id="" cols="30" rows="5"
                                  style="resize: none;"></textarea>
                    </div>

                    <div class="col-12 col-md-12">
                        <label class="form-label" for="modalAddressLastName">الرسالة بالانجليزية</label>
                        <textarea name="" class="form-control" id="" cols="30" rows="5"
                                  style="resize: none;"></textarea>
                    </div>

                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1"><i
                                class="fa fa-check-double me-1"></i>ارسال
                        </button>
                        <button type="reset" class="btn btn-label-danger me-sm-3 me-1" data-bs-dismiss="modal"
                                aria-label="Close">
                            <i class="fa fa-x me-1"> </i>الغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
