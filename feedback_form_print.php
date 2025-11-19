<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Farmer Feedback and Governance Form</title>
    <link rel="stylesheet" href="css/farmer_feedback_print.css">
</head>

<body>

<div class="form-container">

    <div class="header">
        <h2>FARMER FEEDBACK AND GOVERNANCE FORM</h2>
        <p><strong>Barangay: _______________________________</strong></p>
    </div>

    <form>

        <div class="section-title">A. Farmer Information</div>

        <div class="row">
            <label>Full Name:</label>
            <input type="text" class="long-line">
        </div>

        <div class="row">
            <label>Address:</label>
            <input type="text" class="long-line">
        </div>

        <div class="row">
            <label>Contact Number:</label>
            <input type="text" class="short-line">
        </div>

        <div class="section-title">B. Type of Concern</div>

        <div class="checkbox-group">
            <label><input type="checkbox"> High price of fertilizer</label>
            <label><input type="checkbox"> Low price of rice</label>
            <label><input type="checkbox"> Water shortage / El Niño</label>
            <label><input type="checkbox"> Lack of machinery (Harvester / 4 wheels)</label>
            <label><input type="checkbox"> Other (please specify): ____________________________</label>
        </div>

        <div class="section-title">C. Description of Concern</div>
        <textarea class="textarea"></textarea>

        <div class="section-title">D. Date of Submission</div>

        <!-- Date cannot be clicked -->
        <div class="row">
            <label>Date:</label>
            <div class="date-box">____________________</div>
        </div>

        <div class="section-title">E. Farmer’s Signature</div>

        <div class="signature-box">
            <p>_________________________________________</p>
            <small>Signature Over Printed Name</small>
        </div>

    </form>

</div>

</body>
</html>
