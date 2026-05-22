<style>
    .content-body {
        min-height: unset !important;
    }
</style>

<div class="container-fluid">
    <form class="registration-lgu-form" method="POST" action="" autocomplete="nope">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <!-- Blue Header Banner -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="bg-primary text-white p-4 rounded shadow-sm" style="background: linear-gradient(135deg, #1e3c72 0%, #2b4c7c 100%);">
                                <h2 class="mb-1 fw-bold" style="color: #ffffff;">
                                    <i class="ti tabler-building-community me-2"></i>LGU REGISTRATION
                                </h2>                                
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="trans_type" id="trans_type" value="New">
                    <input type="hidden" name="lgu_id" id="lgu_id" value="">
                    <input type="hidden" name="registrationStep" id="registrationStep" value="step1">

                    <div class="card-body">
                        <!-- Row 1: LGU Office Name (full width) -->
                        <div class="row g-4 mb-4">
                            <div class="col-12">
                                <label class="form-label" for="lguOfficeName">LGU Office Name <span class="text-danger">*</span></label>
                                <input type="text" id="lguOfficeName" name="lguOfficeName" class="form-control" placeholder="Enter LGU office name" required />
                            </div>
                        </div>

                        <!-- Row 2: Office Email, Office Number -->
                        <div class="row g-4 mb-4">                            
                            <div class="col-md-6">
                                <label class="form-label" for="lguOfficeEmail">Office Email Address</label>
                                <input type="email" id="lguOfficeEmail" name="lguOfficeEmail" class="form-control" placeholder="Enter office email address" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="lguPhone">Office Number <span class="text-danger">*</span></label>
                                <input type="tel" id="lguPhone" name="lguPhone" class="form-control" placeholder="Enter phone number" required />
                            </div>
                        </div>

                        <!-- Row 3: Office Type, Department / Office -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="lguOfficeType" class="form-label">Office Type <span class="text-danger">*</span></label>
                                <br> <select id="lguOfficeType" name="lguOfficeType" class="select2 form-select" required>
                                    <option value="">- select office type -</option>
                                    <option value="municipal">Municipal Office</option>
                                    <option value="city">City Office</option>
                                    <option value="provincial">Provincial Office</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="lguDepartment" class="form-label">Department / Office <span class="text-danger">*</span></label>
                                <br> <select id="lguDepartment" name="lguDepartment" class="select2 form-select" required>
                                    <option value="">- select department / office -</option>
                                    <option value="disaster-management">Disaster Management Office</option>
                                    <option value="public-safety">Public Safety Office</option>
                                    <option value="information-technology">Information Technology Office</option>
                                </select>
                            </div>
                        </div>

                        <!-- Row 4: Region, Province (Region first — broader to narrower) -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="lguRegion" class="form-label">Region <span class="text-danger">*</span></label>
                                <br> <select id="lguRegion" name="lguRegion" class="select2 form-select" required>
                                    <option value="">- select region -</option>
                                    <option value="region-vi">Region VI</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="lguProvince" class="form-label">Province <span class="text-danger">*</span></label>
                                <br> <select id="lguProvince" name="lguProvince" class="select2 form-select" required>
                                    <option value="">- select province -</option>
                                    <option value="negros-occidental">Negros Occidental</option>
                                </select>
                            </div>
                        </div>

                        <!-- Row 5: Position / Role (full width) -->
                        <div class="row g-4 mb-4">                            
                            <div class="col-12">
                                <label class="form-label" for="lguPosition">Position / Role <span class="text-danger">*</span></label>
                                <input type="text" id="lguPosition" name="lguPosition" class="form-control" placeholder="Enter position / role" required />
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 mt-2">
                            <button type="button" class="btn btn-outline-primary" id="btn-back">
                                <i class="ti tabler-file me-2"></i>Back
                            </button>
                            <button style="margin-left: auto;" type="submit" name="btn_lgu_submit" class="btn btn-outline-success" id="btn-register">
                                <i class="ti tabler-star me-2"></i>Register
                            </button>
                        </div>

                        <!-- Error container -->
                        <div id="lguRegistrationError" class="alert alert-danger mt-4" style="display:none;"></div>
                    </div>
                </div>
            </div>
        </div>

        
    </form>
</div>