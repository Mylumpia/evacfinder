<style>
    .content-body {
        min-height: unset !important;
    }
</style>

<div class="container-fluid">
    <form class="announcement-form" method="POST" action="" autocomplete="nope">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">

                    <div class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                        <h5 class="card-title mb-sm-0 me-2">ANNOUNCEMENT</h5>
                        <input type="hidden" name="encodedby" id="encodedby" value="<?php echo $_SESSION['userid']; ?>">
                        <input type="hidden" name="trans_type" id="trans_type" value="New">
                        <input type="hidden" name="evacuee_id" id="evacuee_id" value="">
                    </div>
                   
                    <input type="hidden" name="trans_type" id="trans_type" value="New">
                    <input type="hidden" name="announcement_id" id="announcement_id" value="">

                    <div class="card-body">
                        <!-- Row 1: Type of Announcement -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-12">
                                <label class="form-label" for="ann_type">Type of Announcement <span class="text-danger">*</span></label>
                                <select id="ann_type" name="ann_type" class="form-select" required>
                                    <option value="" disabled selected>- select -</option>
                                    <option value="General">General Announcement</option>
                                    <option value="Event">Event</option>
                                    <option value="Advisory">Advisory</option>
                                    <option value="Memo">Memo</option>
                                    <option value="Notice">Notice</option>
                                </select>
                            </div>
                        </div>

                        <!-- Row 2: Announcement Description -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-12">
                                <label class="form-label" for="ann_desc">Description <span class="text-danger">*</span></label>
                                <textarea id="ann_desc" name="ann_desc" class="form-control" rows="5" placeholder="Enter announcement description here..." required></textarea>
                            </div>
                        </div>

                        
                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-center mt-2">
                            <button type="submit" name="btn_announcement_submit" class="btn btn-outline-success" id="btn-save">
                                <i class="ti tabler-device-floppy me-2"></i>Save Announcement
                            </button>                                   
                        </div>

                        <!-- Error container -->
                        <div id="announcementError" class="alert alert-danger mt-4" style="display:none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>