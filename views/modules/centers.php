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

                                <!-- Row 1: Center Name -->
                                <div class="row g-6">
                                    <div class="col-12">
                                        <label class="form-label" for="center_name">Evacuation Center Name</label>
                                        <input type="text" id="center_name" name="center_name" class="form-control" placeholder="Enter evacuation center name" required />
                                    </div>
                                </div>
                                <br>

                                <!-- Row 2: Category, Status -->
                                <div class="row g-6">
                                    <div class="col-md-6">
                                        <label for="category" class="form-label">Evacuation Category</label>
                                        <select id="category" name="category" class="select2 form-select" data-allow-clear="true" required>
                                            <option value="">- select category -</option>
                                            <option value="Primary">Primary</option>
                                            <option value="Secondary">Secondary</option>
                                            <option value="Tertiary">Tertiary</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="status" class="form-label">Status</label>
                                        <select id="status" name="status" class="select2 form-select" data-allow-clear="true" required>
                                            <option value="">- select status -</option>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                            <option value="Full">Full</option>
                                            <option value="Under Maintenance">Under Maintenance</option>
                                        </select>
                                    </div>
                                </div>
                                <br>

                                <!-- Row 3: Complete Address -->
                                <div class="row g-6">
                                    <div class="col-12">
                                        <label class="form-label" for="address">Complete Address</label>
                                        <input type="text" id="address" name="address" class="form-control" placeholder="Enter complete address" />
                                    </div>
                                </div>
                                <br>

                                <!-- Row 4: Barangay, City/Municipality -->
                                <div class="row g-6">
                                    <div class="col-md-6">
                                        <label class="form-label" for="barangay">Barangay</label>
                                        <input type="text" id="barangay" name="barangay" class="form-control" placeholder="Enter barangay" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="city">City / Municipality</label>
                                        <input type="text" id="city" name="city" class="form-control" placeholder="Enter city / municipality" />
                                    </div>
                                </div>
                                <br>

                                <!-- Row 5: Province -->
                                <div class="row g-6">
                                    <div class="col-12">
                                        <label class="form-label" for="province">Province</label>
                                        <select id="province" name="province" class="form-select" required>
                                            <option value="">- select province -</option>
                                            <option value="Negros Occidental">Negros Occidental</option>
                                        </select>
                                    </div>
                                </div>
                                <br>

                                <!-- Row 6: Latitude, Longitude -->
                                <div class="row g-6">
                                    <div class="col-md-6">
                                        <label class="form-label" for="latitude">Latitude</label>
                                        <input type="text" id="latitude" name="latitude" class="form-control" placeholder="Enter latitude" readonly tabindex="-1" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="longitude">Longitude</label>
                                        <input type="text" id="longitude" name="longitude" class="form-control" placeholder="Enter longitude" readonly tabindex="-1" />
                                    </div>
                                </div>
                                <br>

                                <!-- Row 7: Mini Map -->
                                <div class="row g-6">
                                    <div class="col-12">
                                        <label class="form-label">Mini Map <small class="text-muted">(Click to set location)</small></label>
                                        <div class="map-card map-preview-card" id="mapPreviewCard" style="position: relative; border: 1px solid #d9dee3; border-radius: 0.375rem; overflow: hidden;">
                                            <div id="centerMap" style="height: 350px; width: 100%; z-index: 1;"></div>
                                            <div class="map-overlay" id="mapOverlay"
                                                style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.35); color: #fff; font-size: 0.9rem; z-index: 2; pointer-events: none; transition: opacity 0.3s;">
                                                Click the map to set latitude and longitude
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <!-- Row 8: Estimated Capacity, Contact Number -->
                                <div class="row g-6">
                                    <div class="col-md-6">
                                        <label class="form-label" for="estimated_capacity">Estimated Capacity</label>
                                        <input type="number" id="estimated_capacity" name="estimated_capacity" class="form-control" placeholder="Enter estimated capacity" min="0" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="contact_number">Center Contact Number</label>
                                        <input type="text" id="contact_number" name="contact_number" class="form-control" placeholder="Enter contact number" />
                                    </div>
                                </div>
                                <br>

                                <!-- Row 9: Contact Person -->
                                <div class="row g-6">
                                    <div class="col-12">
                                        <label class="form-label" for="contact_person">Contact Person</label>
                                        <input type="text" id="contact_person" name="contact_person" class="form-control" placeholder="Enter contact person" />
                                    </div>
                                </div>
                                <br>

                                <!-- Row 10: Accessibility Features, Available Facilities -->
                                <div class="row g-6">
                                    <div class="col-md-6">
                                        <label class="form-label" for="accessibility">Accessibility Features</label>
                                        <input type="text" id="accessibility" name="accessibility" class="form-control" placeholder="Enter accessibility features" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="available_facilities">Available Facilities</label>
                                        <input type="text" id="available_facilities" name="available_facilities" class="form-control" placeholder="Enter available facilities" />
                                    </div>
                                </div>
                                <br>

                                <!-- Row 11: Remarks -->
                                <div class="row g-6">
                                    <div class="col-12">
                                        <label class="form-label" for="remarks">Notes / Remarks</label>
                                        <textarea name="remarks" class="form-control" id="remarks" rows="4" placeholder="Notes / Remarks"></textarea>
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
                                                            <th>Estimated Capacity</th>
                                                            <th>Contact Person</th>
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