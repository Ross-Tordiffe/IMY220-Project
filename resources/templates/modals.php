
<div class="modal fade" id="friendsModal" tabindex="-1" aria-labelledby="friendsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title ms-3" id="friendsModalLabel">Friends</h3>
                <button type="button" class="btn modal-close profile-modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush profile-friends w-100">
                    
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Event review modal -->
<div class="modal fade" id="eventReviewModal" tabindex="-1" aria-labelledby="eventReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title ms-3" id="eventReviewModalLabel">Create review</h3>
                <button type="button" class="btn modal-close profile-modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="event-review-form-container">
                    <form action="" method="post" class="event-review-form d-flex flex-column w-100 p-3">
                        <!-- Give a star rating -->
                        <div class="event-rating d-flex flex-column justify-content-center position-relative">
                            <div class="h5 pt-3">Give a rating</div>
                            <div class="star-box"></div>
                        </div>
                       
                        <!-- Review text -->
                        <div class="form-group pt-3 pb-1 has-counter w-100">
                            <label for="event-review-message" class="review-label fs-5 mb-1">Leave a comment<small class=" ps-1">(optional)</small></label>
                            <textarea type="text" class="form-control input-alt" id="event-review-message" placeholder=" " name="event-description" rows=5 maxlength="240"></textarea>
                            <div class="character-counter" id="review-counter">0/240</div>
                        </div>

                        <!-- Upload image -->
                        <label for="event-review-file" class="review-label fs-5 mb-1 mt-2">Upload an image<small class=" ps-1">(optional)</small></label>
                        <div class="form-group event-review-image-box d-flex justify-content-center w-100">
                            <span class="event-review-image-btn">
                                <i class="event-review-image-i fas fa-image fs-1"></i>
                            </span>
                        </div>
                        <input type="file" id="event-review-file" class="file-input" name="event-review-file" data-height="500" accept=".png, .jpg, .jpeg" />
                    </form>
                </div>
                <div class="event-review-form-group w-100 d-flex justify-content-center pt-3">
                    <button type="submit" class="btn btn-primary review-submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Confirmation modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title ms-1 h3 d-flex" id="confirmationModalLabel">
                    <div class="confirmation-modal-icon me-2">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>Confirmation</span>
                    </div>
                </div>
            </div>
            <div class="modal-body p-2">
                <div class="confirmation-modal-container d-flex flex-column justify-content-center align-items-center">
                    <div class="confirmation-modal-text p-3">
                        <p class="h4"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary py-0 modal-close" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary confirmation-modal-btn py-0">Confirm</button>
            </div>
        </div>
    </div>
</div>