<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Instagram Roast</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .response-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            margin-top: 20px;
        }

        .response-container h2 {
            margin-top: 0;
            color: #333;
        }

        .response-container p {
            margin: 5px 0;
        }

        .loading-spinner {
            border: 8px solid #f3f3f3;
            border-radius: 50%;
            border-top: 8px solid #007bff;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin-top: 20px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .hidden {
            display: none;
        }

        .error-message {
            color: red;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <h1>Instagram Roast</h1>

    <form action="{{ route('scrape-instagram') }}" method="POST" id="scrapeForm">
        @csrf
        <input type="text" name="username" id="username" placeholder="Enter Instagram username" required>
        <button type="submit">Submit</button>
    </form>

    <div class="loading-spinner hidden" id="loadingSpinner"></div>

    <div class="response-container hidden" id="responseContainer">
        <h2>Roasting Results</h2>
        <div id="responseContent"></div>
    </div>

    <div class="error-message hidden" id="errorMessage"></div>

    <script>
        document.getElementById('scrapeForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const username = document.getElementById('username').value;
            const token = document.querySelector('input[name="_token"]').value;

            // Show loading spinner
            document.getElementById('loadingSpinner').classList.remove('hidden');
            document.getElementById('responseContainer').classList.add('hidden');
            document.getElementById('errorMessage').classList.add('hidden');

            fetch("{{ route('scrape-instagram') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        username: username
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.text();
                })
                .then(data => {
                    // Hide loading spinner and show response
                    document.getElementById('loadingSpinner').classList.add('hidden');
                    document.getElementById('responseContainer').classList.remove('hidden');
                    document.getElementById('responseContent').innerHTML = data;
                })
                .catch(error => {
                    // Hide loading spinner and show error message
                    document.getElementById('loadingSpinner').classList.add('hidden');
                    document.getElementById('errorMessage').classList.remove('hidden');
                    document.getElementById('errorMessage').textContent = 'Error: ' + error.message;
                });
        });
    </script>
</body>

</html>
