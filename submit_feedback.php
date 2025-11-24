<?php require_once __DIR__ . '/includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Feedback</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <style>
        body {
            background: #f5f5f5;
        }
        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0,0,0,0.1);
        }
        .form-title {
            font-weight: bold;
        }
        .btn-back {
            text-decoration: none;
            color: #2a67a8ff;
            /* background: #6c757d; */
            padding: 8px 12px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }
        .date-box label {
            margin-bottom: 0;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-start" style="min-height:100vh; padding-top:5px;">
    <div class="col-md-6 form-container">

        <h2 class="text-center mb-4 form-title">FARMER FEEDBACK AND GOVERNANCE FORM</h2>

        <form action="save_feedback.php" method="POST">

            <!-- DATE & TIME -->
            <div class="d-flex justify-content-between mb-4">
                <div class="date-box p-2 border rounded w-50 me-2">
                    <label class="fw-bold">Date:</label>
                    <input type="date" name="date" class="form-control mb-2" required>
                </div>
                <div class="date-box p-2 border rounded w-50 ms-2">
                    <label class="fw-bold">Time:</label>
                    <input type="time" name="time" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Farmer’s Name:</label>
                <input type="text" name="farmer_name" class="form-control" required>
            </div>


            <div class="mb-3">
                <label class="fw-bold">Phone Number:</label>
                <input type="tel" name="phone" class="form-control" placeholder="09xxxxxxxxx" required>
            </div>
            <div class="mb-3">
                <label class="fw-bold">Organization / Farmers’ Group:</label>
                <input type="text" name="organization" class="form-control">
            </div>

            <div class="mb-3">
                <label class="fw-bold">Type of Concern:</label>
                <select name="concern_type" class="form-control" required>
                    <option value="">-- Select Concern --</option>
                    <option value="Fertilizer">Fertilizer</option>
                    <option value="Water Supply">Water Supply</option>
                    <option value="Rice Price">Rice Price</option>
                    <option value="Machine Request">Machine Request</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Details of Complaint / Feedback:</label>
                <textarea name="details" rows="5" class="form-control" required></textarea>
            </div>

            <div class="mb-4">
                <label class="fw-bold">Farmer’s Signature:</label>
                <div class="border-bottom" style="height:35px;"></div>
            </div>

            <button class="btn btn-success w-100" type="submit">Submit Feedback</button>

            <div class="text-center mt-3">
            <a href="dashboard.php" class="btn-back">⬅ Back to Dashboard</a>
        </div>

        </form>

    </div>
</div>
<?php include 'includes/footer.php'; ?>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
