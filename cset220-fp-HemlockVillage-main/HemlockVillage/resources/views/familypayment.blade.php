<html>
    <head>
        <title>Family Payments</title>
		<link rel="stylesheet" href="{{ asset('./css/mainstyle.css') }}">

    </head>
    <body>
        <div class="container">
            <h1>Family Payments</h1>

            <div id="id-section">
                <div class="form-group">
                    <label for="patient-id">Patient ID:</label>
                    <input type="text" id="patient-id" placeholder="Enter Patient ID">
                </div>

                <div class="form-group">
                    <button type="button">Submit</button>
                </div>
            </div>

            <div id="payment-section">
                <div class="form-group">
                    <label for="total-due">Total Due:</label>
                    <input type="text" id="total-due" placeholder="50000.00">
                </div>

                <div class="form-group">
                    <label for="new-payment">New Payment:</label>
                    <input type="text" id="new-payment" placeholder="Enter Payment Amount">
                </div>

                <div class="form-group">
                <button type="button">Update Payment</button>
                <button type="button">Dashboard</button>
            </div>
        </div>

        @include('navbar')

    </body>
</html>
