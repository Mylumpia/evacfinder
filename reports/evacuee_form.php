<?php
require_once '../vendor/autoload.php';

class EvacueeRegistrationForm {
    public function printForm() {
        $pdf = new TCPDF('P', 'mm', 'A4');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(false, 0);

        $pdf->AddPage('P');

        $html = '
        <style>
            .form-title  { font-size:13pt; font-weight:bold; text-align:center; }
            .reg-date    { font-size:8pt; text-align:right; margin-bottom:2px; }
            .section-hdr { background-color:#000000; color:#ffffff; font-weight:bold;
                           font-size:8pt; padding:2px 4px; }
            .field-label { font-size:7pt; color:#555555; }
            td           { font-size:7.5pt; vertical-align:top; padding:1px 3px; }
            .blank       { border-bottom:1px solid #000000; width:100%; height:11px; display:inline-block; }
        </style>

        <!-- TITLE -->
        <p class="form-title" style="margin:0 0 1px 0;">EVACUEE REGISTRATION FORM</p>
        <p class="reg-date" style="margin:0 0 3px 0;">Registration Date: ____________________</p>

        <!-- SECTION 1: PERSONAL DETAILS -->
        <table width="100%" cellpadding="2" cellspacing="0">
            <tr>
                <td colspan="4" class="section-hdr">PERSONAL DETAILS</td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="field-label">Full Name:</span><br/>
                    <table width="100%" cellpadding="1" cellspacing="0" border="1"
                           style="border-collapse:collapse;">
                        <tr style="background-color:#f2f2f2; font-size:7pt; text-align:center;">
                            <td width="30%">Last name</td>
                            <td width="30%">First name</td>
                            <td width="28%">Middle name</td>
                            <td width="12%">Ext.</td>
                        </tr>
                        <tr>
                            <td width="30%" style="height:13px;">&nbsp;</td>
                            <td width="30%">&nbsp;</td>
                            <td width="28%">&nbsp;</td>
                            <td width="12%">&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="35%">
                    <span class="field-label">Relation to Family Head:</span><br/>
                    <div class="blank">&nbsp;</div>
                </td>
                <td width="20%">
                    <span class="field-label">Sex:</span><br/>
                    [&nbsp;] Male &nbsp;[&nbsp;] Female
                </td>
                <td width="25%">
                    <span class="field-label">Civil Status:</span><br/>
                    <div class="blank">&nbsp;</div>
                </td>
                <td width="20%">&nbsp;</td>
            </tr>
            <tr>
                <td width="30%">
                    <span class="field-label">Birth Date:</span><br/>
                    <div class="blank">&nbsp;</div>
                </td>
                <td width="15%">
                    <span class="field-label">Age:</span><br/>
                    <div class="blank">&nbsp;</div>
                </td>
                <td width="55%" colspan="2">
                    <span class="field-label">Occupation:</span><br/>
                    <div class="blank">&nbsp;</div>
                </td>
            </tr>
        </table>

        <!-- SECTION 2: CONTACT & ADDRESS -->
        <table width="100%" cellpadding="2" cellspacing="0" style="margin-top:3px;">
            <tr>
                <td colspan="2" class="section-hdr">CONTACT &amp; ADDRESS</td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="field-label">Complete Address:</span><br/>
                    <div class="blank">&nbsp;</div>
                    <span style="font-size:6.5pt; color:#777777;">Street / Barangay / Municipality / Province</span>
                </td>
            </tr>
            <tr>
                <td width="40%">
                    <span class="field-label">Contact Number:</span><br/>
                    <div class="blank">&nbsp;</div>
                </td>
                <td width="60%">&nbsp;</td>
            </tr>
            <tr>
                <td width="50%">
                    <span class="field-label">Emergency Contact Person:</span><br/>
                    <div class="blank">&nbsp;</div>
                </td>
                <td width="50%">
                    <span class="field-label">Emergency Contact #:</span><br/>
                    <div class="blank">&nbsp;</div>
                </td>
            </tr>
        </table>

        <!-- SECTION 3: EVACUATION & STATUS INFORMATION -->
        <table width="100%" cellpadding="2" cellspacing="0" style="margin-top:3px;">
            <tr>
                <td colspan="4" class="section-hdr">EVACUATION &amp; STATUS INFORMATION</td>
            </tr>
            <tr>
                <td width="40%">
                    <span class="field-label">Evacuation Center:</span><br/>
                    <div class="blank">&nbsp;</div>
                </td>
                <td width="30%">
                    <span class="field-label">Arrival Date:</span><br/>
                    <div class="blank">&nbsp;</div>
                </td>
                <td width="30%" colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="field-label">Status:</span>&nbsp;&nbsp;
                    [&nbsp;] Active / Admitted &nbsp;&nbsp;
                    [&nbsp;] Discharged / Left &nbsp;&nbsp;
                    [&nbsp;] Transferred &nbsp;&nbsp;
                    [&nbsp;] Missing / Unaccounted For
                </td>
            </tr>
            <tr>
                <td width="40%">
                    <span class="field-label">Departure Date:</span><br/>
                    <div class="blank">&nbsp;</div>
                </td>
                <td width="60%" colspan="3">
                    <span class="field-label">Encoded By:</span><br/>
                    <div class="blank">&nbsp;</div>
                </td>
            </tr>
        </table>

        <!-- SECTION 4: MEDICAL & VULNERABILITIES -->
        <table width="100%" cellpadding="2" cellspacing="0" style="margin-top:3px;">
            <tr>
                <td colspan="3" class="section-hdr">MEDICAL &amp; VULNERABILITIES (SPECIAL CONDITIONS)</td>
            </tr>
            <tr>
                <td width="33%">
                    <span class="field-label">Pregnant:</span>
                    &nbsp;[&nbsp;] Yes &nbsp;[&nbsp;] No
                </td>
                
                <td width="33%">
                    <span class="field-label">Lactating:</span>
                    &nbsp;[&nbsp;] Yes &nbsp;[&nbsp;] No
                </td>
                <td width="34%">
                    <span class="field-label">Elderly (60+):</span>
                    &nbsp;[&nbsp;] Yes &nbsp;[&nbsp;] No
                </td>
            </tr>
            <br>
            <tr>
                <td width="50%" colspan="2">
                    <span class="field-label">4Ps Beneficiary:</span>
                    &nbsp;[&nbsp;] Yes &nbsp;[&nbsp;] No
                </td>
                <td width="50%">&nbsp;</td>
            </tr>
            <br>
            <tr>
                <td colspan="3">
                    <span class="field-label">Person with Disability (PWD):</span>
                    &nbsp;[&nbsp;] Yes &nbsp;[&nbsp;] No
                    &nbsp;&nbsp;
                    <span class="field-label">If Yes, Type:</span>
                    ____________________
                </td>
            </tr>
        </table>

        <!-- SECTION 5: HEALTH & MEDICAL PROFILE -->
        <table width="100%" cellpadding="2" cellspacing="0" style="margin-top:3px;">
        <br>
        <br>
        <br>
            <tr>
                <td colspan="2" class="section-hdr">HEALTH &amp; MEDICAL PROFILE</td>
            </tr>
            <tr>
                <td width="35%"><span class="field-label">Health Status:</span></td>
                <td width="65%"><div class="blank">&nbsp;</div></td>
            </tr>
            <tr>
                <td><span class="field-label">Emergency Medical Condition:</span></td>
                <td><div class="blank">&nbsp;</div></td>
            </tr>
            <tr>
                <td><span class="field-label">Current Medications Taken:</span></td>
                <td><div class="blank">&nbsp;</div></td>
            </tr>
            <tr>
                <td><span class="field-label">Known Allergies:</span></td>
                <td><div class="blank">&nbsp;</div></td>
            </tr>
        </table>

        <!-- SIGNATURE BLOCK -->
        <table width="100%" cellpadding="4" cellspacing="0" style="margin-top:10px;">
        <br>
        <br>
        <br>
        <br>
        <br>
            <tr>
                <td width="45%" style="border-top:1px solid #000000; text-align:left; font-size:7.5pt;">
                    Encoder / Staff Signature over Printed Name
                </td>
                <td width="10%">&nbsp;</td>
                <td width="45%" style="border-top:1px solid #000000; text-align:right; font-size:7.5pt;">
                    Date of Encoding
                </td>
            </tr>
        </table>
        ';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('evacuee_registration_form.pdf', 'I');
    }
}

$form = new EvacueeRegistrationForm();
$form->printForm();