
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
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary py-0 modal-close" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary py-0 review-submit">Submit</button>
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
                <button type="button" class="btn modal-close profile-modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body p-2">
                <div class="confirmation-modal-container d-flex flex-column justify-content-center align-items-center">
                    <div class="confirmation-modal-text p-3">
                        <p class="h4"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary py-0 modal-close" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary confirmation-modal-btn py-0">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Create group modal -->
<div class="modal fade" id="createGroupModal" tabindex="-1" aria-labelledby="createGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title ms-3" id="createGroupModalLabel">Create group</h3>
                <button type="button" class="btn modal-close profile-modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="create-group-form-container">
                    <form action="" method="post" class="create-group-form d-flex flex-column w-100 p-3">
                        <!-- Group name -->
                        <div class="form-group pt-3 pb-1 has-counter w-100">
                            <label for="group-title" class="review-label fs-5 mb-1">Group name</label>
                            <input type="text" class="form-control input-alt" id="group-title" placeholder=" " name="group-title" maxlength="50" />
                            <div class="character-counter" id="group-title-counter">0/50</div>
                        </div>

                        <!-- Group description -->
                        <div class="form-group pt-3 pb-1 has-counter w-100">
                            <label for="group-description" class="review-label fs-5 mb-1">Group description</label>
                            <textarea type="text" class="form-control input-alt" id="group-description" placeholder=" " name="group-description" rows=5 maxlength="120"></textarea>
                            <div class="character-counter" id="group-description-counter">0/120</div>
                        </div>
                    </form>
                    <div class="modal-footer border-top-0 ">
                        <button type="button" class="btn btn-secondary py-0 modal-close" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary create-group-submit py-0">Create</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Show groups list modal -->
<div class="modal fade" id="showGroupsModal" tabindex="-1" aria-labelledby="showGroupsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title ms-3" id="showGroupsLabel">Groups</h3>
                <button type="button" class="btn modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body p-2 ">
                <ul class="list-group list-group-flush event-show-groups-box w-100 p-0">
                    
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Show profile settings modal -->
<div class="modal" id="profileSettingsModal" tabindex="-1" aria-labelledby="profileSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title ms-3" id="profileSettingsModalLabel">Settings</h3>
                <button type="button" class="btn modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body p-2 pb-0 d-flex justify-content-center">
                <div class="profile-settings-container d-flex flex-column">
                    <div class="profile-settings-form-container">
                        <form action="" method="post" class="profile-settings-form d-flex flex-column w-100 p-3">
                            <div class="form-group pt-3 pb-1 has-counter w-100">
                                <label for="profile-username" class="review-label fs-6 mb-1">Username</label>
                                <input type="text" class="form-control input-alt" id="profile-username" placeholder=" " name="profile-username" maxlength="50"/>
                            </div>
                            <!-- Profile email -->
                            <div class="form-group pt-3 pb-1 has-counter w-100">
                                <label for="profile-email" class="review-label fs-6 mb-1">Email</label>
                                <input type="email" class="form-control input-alt" id="profile-email" placeholder=" " name="profile-email" maxlength="50"/>
                            </div>
                            <!-- Profile theme (lightbulb icon) -->
                            
                            <div class="form-group pt-3 pb-1 has-counter w-100 d-flex justify-content-between">
                                <div id="profile-theme">
                                    <label for="profile-theme" class="review-label fs-5 mb-1"><i class="fas fa-lightbulb profile-settings-icon pe-2"></i>Theme</label>
                                </div>
                                <button type="button" class="btn btn-primary profile-theme-btn py-0"></button>
                            </div>
                            <!-- Profile delete -->
                            <div class="form-group pt-3 pb-1 has-counter w-100 d-flex justify-content-between">
                                <div id="profile-delete">
                                    <label for="profile-delete" class="review-label fs-5 mb-1"><i class="fas fa-trash-alt profile-settings-icon pe-2"></i>Delete account</label>
                                </div>
                                <button type="button" class="btn btn-primary profile-delete-btn py-0">DELETE</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary settings-submit py-0">Save Changes</button>
            </div>
        </div>
    </div>
</div>