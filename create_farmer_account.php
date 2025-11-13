<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Farmer Account</title>
    <link rel="stylesheet" href="css/create_farmer_account.css">
</head>
<body>
    <div class="container">
        <h1>Create Farmer Account</h1>
        <h2>Step 1: Personal Information</h2>
        <form action="#" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" required>
                </div>
                <div class="form-group">
                    <label for="contact">Contact</label>
                    <input type="text" id="contact" name="contact" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="barangay">Barangay</label>
                    <input type="text" id="barangay" name="barangay" required>
                </div>
                <div class="form-group">
                    <label for="purok">Purok</label>
                    <input type="text" id="purok" name="purok" required>
                </div>
                <div class="form-group">
                    <label for="municipality">Municipality</label>
                    <input type="text" id="municipality" name="municipality" required>
                </div>
                <div class="form-group">
                    <label for="province">Province</label>
                    <input type="text" id="province" name="province" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="farm_type">Farm Type</label>
                    <input type="text" id="farm_type" name="farm_type" required>
                </div>
                <div class="form-group">
                    <label for="farm_size">Farm Size (hectares)</label>
                    <input type="number" id="farm_size" name="farm_size" step="0.01" required>
                </div>
            </div>

            <div class="form-row">
                <button type="submit" class="next-button">Next</button>
            </div>
        </form>
    </div>
</body>
</html>