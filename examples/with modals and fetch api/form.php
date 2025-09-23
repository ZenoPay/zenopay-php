<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f9fc;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
            font-weight: bold;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-size: 14px;
            font-weight: 500;
            color: #555;
            text-align: left;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="number"]:focus {
            border-color: #007BFF;
            outline: none;
        }

        input[type="submit"] {
            width: 100%;
            padding: 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .container form {
            text-align: left;
        }

        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .modal-content h3 {
            color: #333;
        }

        .modal-content p {
            margin: 10px 0;
        }

        .modal-content button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal-content button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Payment Form</h2>
        <form id="paymentForm">
            <label for="buyer_name">Full Name:</label>
            <input type="text" id="buyer_name" name="buyer_name" required>

            <label for="buyer_email">Email:</label>
            <input type="email" id="buyer_email" name="buyer_email" required>

            <label for="buyer_phone">Phone:</label>
            <input type="text" id="buyer_phone" name="buyer_phone" required>

            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" step="0.01" required>

            <input type="submit" id="submitBtn" value="Pay Now">
        </form>
    </div>

    <!-- Modal for Success/Error Messages -->
    <div id="responseModal" class="modal">
        <div class="modal-content">
            <h3 id="modalTitle">Payment Status</h3>
            <p id="modalMessage"></p>
            <button id="modalButton" onclick="closeModal()">OK</button>
        </div>
    </div>

    <script>
        let paymentSuccessful = false;
        let orderId = '';

        document.getElementById('paymentForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the form from submitting

            // Disable the submit button and change its text to "Processing..."
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.value = 'Processing...';

            // Create form data object
            const formData = new FormData(this);

            // Make AJAX request to the PHP backend
            fetch('processing.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Show success or error modal
                const modal = document.getElementById('responseModal');
                const modalTitle = document.getElementById('modalTitle');
                const modalMessage = document.getElementById('modalMessage');

                if (data.result === 'success') {
                    // modalTitle.textContent = 'Success';
                    // modalMessage.textContent = data.message;
                    orderId = data.order_id;
                    modalTitle.textContent = 'info';
                    modalMessage.textContent = "you'll receive a push notification. your payment id is "+data.order_id;
                    submitBtn.value = 'Pay Now';
                    // Reset the button back to original state after success
                    paymentSuccessful = true;
                } else {
                    modalTitle.textContent = 'Error';
                    modalMessage.textContent = 'Payment failed: ' + data.error;

                    // Re-enable the submit button after error
                    submitBtn.disabled = false;
                    submitBtn.value = 'Pay Now';
                    paymentSuccessful = false;
                }

                modal.style.display = 'flex';
            })
            .catch(error => {
                console.error('Error:', error);

                // Re-enable the submit button in case of error
                submitBtn.disabled = false;
                submitBtn.value = 'Pay Now';
            });
        });

        // Close the modal
        function closeModal() {
            document.getElementById('responseModal').style.display = 'none';

            // Redirect to homepage if payment was successful
            if (paymentSuccessful) {
                window.location.href = './redirectpage.php?orderId='+orderId; // Redirect to the homepage or any desired page
                submitBtn.value = 'Pay Now';
            }
        }
    </script>
</body>
</html>
