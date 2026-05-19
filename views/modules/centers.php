<div class="container-fluid flex-grow-1 container-p-y">
    <form class="evacuation-form" method="POST" autocomplete="nope">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">

                    <div class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                        <h5 class="card-title mb-sm-0 me-2">EVACUATION CENTERS</h5>
                        <input type="hidden" name="encodedby" id="encodedby" value="<?php echo $_SESSION['userid']; ?>">
                        <input type="hidden" name="trans_type" id="trans_type" value="New">
                        <input type="hidden" name="center_id" id="center_id" value="">
                    </div>

                    <div class="card-body pt-12">
                        <div class="row">
                            <div class="col-lg-12">

                                <!-- Row 1: Center Name, Category -->
                                <div class="row g-6">
                                    <div class="col-md-5">
                                        <label class="form-label" for="center_name">Center Name</label>
                                        <input type="text" id="center_name" name="center_name" class="form-control" required />
                                    </div>
                                    <div class="col-md-3">
                                        <label for="category" class="form-label">Category</label>
                                        <select id="category" name="category" class="select2 form-select" data-allow-clear="true" required>
                                            <option></option>
                                            <option value="Primary">Primary</option>
                                            <option value="Secondary">Secondary</option>
                                            <option value="Tertiary">Tertiary</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3" style="margin-left: 20px;"> 
                                        <label for="status" class="form-label">Status</label>
                                        <select id="status" name="status" class="select2 form-select" data-allow-clear="true" required>
                                            <option></option>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                            <option value="Full">Full</option>
                                            <option value="Under Maintenance">Under Maintenance</option>
                                        </select>
                                    </div>
                                </div>
                                <br>

                                <!-- Row 2: Barangay, City/Municipality, Province -->
                                <div class="row g-6">
                                    <div class="col-md-4">
                                        <label class="form-label" for="barangay">Barangay</label>
                                        <input type="text" id="barangay" name="barangay" class="form-control" required />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="city">City / Municipality</label>
                                        <input type="text" id="city" name="city" class="form-control" required />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="province">Province</label>
                                        <input type="text" id="province" name="province" class="form-control" required />
                                    </div>
                                </div>
                                <br>

                                <!-- Row 3: Address/Street -->
                                <div class="row g-6">
                                    <div class="col-12">
                                        <label class="form-label" for="address">Address / Street</label>
                                        <textarea name="address" class="form-control" id="address" rows="2"></textarea>
                                    </div>
                                </div>
                                <br>

                                <!-- Row 4: Capacity, Max Persons, Primary Contact -->
                                <div class="row g-6">
                                    <div class="col-md-3">
                                        <label class="form-label" for="capacity">Capacity (sqm)</label>
                                        <input type="number" id="capacity" name="capacity" class="form-control" min="0" />
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="max_persons">Max Persons</label>
                                        <input type="number" id="max_persons" name="max_persons" class="form-control" min="0" required />
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="current_occupants">Current Occupants</label>
                                        <input type="number" id="current_occupants" name="current_occupants" class="form-control" min="0" value="0" />
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="contact_number">Primary Contact No.</label>
                                        <input type="text" id="contact_number" name="contact_number" class="form-control" />
                                    </div>
                                </div>
                                <br>

                                <!-- Row 5: Contact Person, Alternate Contact -->
                                <div class="row g-6">
                                    <div class="col-md-5">
                                        <label class="form-label" for="contact_person">In charge/Contact Person</label>
                                        <input type="text" id="contact_person" name="contact_person" class="form-control" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="alternate_contact">Alternate Contact No.</label>
                                        <input type="text" id="alternate_contact" name="alternate_contact" class="form-control" />
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="date_established">Date Established</label>
                                        <input type="text" id="date_established" name="date_established" class="form-control" />
                                    </div>
                                </div>
                                <br>

                                <!-- Row 6: Facilities Available -->
                                <div class="row g-6">
                                    <div class="col-md-6">
                                        <label for="facilities" class="form-label">Facilities Available</label>
                                        <select id="facilities" name="facilities[]" class="select2 form-select" multiple data-allow-clear="true">
                                            <option value="Restrooms">Restrooms</option>
                                            <option value="Potable Water">Potable Water</option>
                                            <option value="Electricity">Electricity</option>
                                            <option value="Generator">Generator</option>
                                            <option value="Medical Station">Medical Station</option>
                                            <option value="Kitchen/Canteen">Kitchen / Canteen</option>
                                            <option value="Sleeping Area">Sleeping Area</option>
                                            <option value="Prayer Room">Prayer Room</option>
                                            <option value="WiFi/Internet">WiFi / Internet</option>
                                            <option value="Parking Area">Parking Area</option>
                                            <option value="Wheelchair Access">Wheelchair Access</option>
                                            <option value="Children's Area">Children's Area</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="hazard_type" class="form-label">Hazard Type Served</label>
                                        <select id="hazard_type" name="hazard_type[]" class="select2 form-select" multiple data-allow-clear="true">
                                            <option value="Flood">Flood</option>
                                            <option value="Typhoon">Typhoon</option>
                                            <option value="Earthquake">Earthquake</option>
                                            <option value="Volcanic Eruption">Volcanic Eruption</option>
                                            <option value="Landslide">Landslide</option>
                                            <option value="Fire">Fire</option>
                                            <option value="Storm Surge">Storm Surge</option>
                                            <option value="Tsunami">Tsunami</option>
                                        </select>
                                    </div>
                                </div>
                                <br>

                                <!-- Row 7: Remarks -->
                                <div class="row g-6">
                                    <div class="col-12">
                                        <label class="form-label" for="remarks">Remarks / Notes</label>
                                        <textarea name="remarks" class="form-control" id="remarks" rows="2"></textarea>
                                    </div>
                                </div>
                                <br>

                                <!-- Action Buttons -->
                                <div class="demo-inline-spacing">
                                    <button type="button" class="btn btn-outline-primary" id="btn-new">
                                        <span class="icon-xs icon-base ti tabler-file me-2"></span>New
                                    </button>
                                    <button type="button" class="btn btn-outline-success" id="btn-save">
                                        <span class="icon-xs icon-base ti tabler-star me-2"></span>Save
                                    </button>
                                    <button type="button" class="btn btn-outline-info" id="btn-search"
                                        data-bs-toggle="modal" data-bs-target="#modal-search-center">
                                        <span class="icon-xs icon-base ti tabler-search me-2"></span>Search
                                    </button>
                                </div>

                                <!-- Search Modal -->
                                <div class="modal fade" id="modal-search-center" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalCenterTitle">EVACUATION CENTER LIST</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-bordered table-hover centerListTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Center Name</th>
                                                            <th>Barangay</th>
                                                            <th>City / Municipality</th>
                                                            <th>Province</th>
                                                            <th>Category</th>
                                                            <th>Max Persons</th>
                                                            <th>Current Occupants</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="table-border-bottom-0">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Search Modal -->

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>