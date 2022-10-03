
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
                <div class="event-review-form-container ">
                    <form action="" method="post" class="event-review-form d-flex flex-column align-items-center w-100">
                        <!-- Give a star rating -->
                        
                        <div class="form-group py-5 has-counter">
                            <label for="event-description" class="">How was your experience?</label>
                            <textarea type="text" class="form-control input-alt" id="event-description" placeholder=" " name="event-description" rows=3 maxlength="240"></textarea>
                            <div class="character-counter" id="description-counter">0/240</div>
                        </div>
                        
                        <div class="event-review-form-group">
                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                    </form>
                </div>
        </div>
    </div>