<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        #sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            flex-shrink: 0;
        }

        #sidebar h2 {
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }

        #sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #sidebar ul li a {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            cursor: pointer; /* Make links look clickable */
            transition: background-color 0.3s ease;
        }

        #sidebar ul li a:hover {
            background-color: #495057;
        }

        #content {
            flex-grow: 1;
            padding: 20px;
        }

        #receptionist-login-form,
        #patient-login-form {
            display: none; /* Initially hide the forms */
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div id="sidebar">
        <h2>MMIS Portal</h2>
        <ul>
            <li><a href="#" data-target="receptionist-login-form">Receptionist Portal</a></li>
            {{-- <li><a href="#" data-target="patient-login-form">Patient Portal</a></li> --}}
            <li><a href="#">Doctor Portal</a></li>
            <li><a href="#">Pharmacist Portal</a></li>
            <li><a href="#">Lab Technician Portal</a></li>
            <li><a href="#">Nurse Portal</a></li>
        </ul>
    </div>
    <div id="content">
        <div class="container">
            <h1>Welcome to the MMIS Dashboard</h1>

            <div id="receptionist-login-form">
                @include('auth.receptionist-login-partial')
            </div>

            {{-- <div id="patient-login-form">
                @include('auth.patient-login-partial')
            </div> --}}

            <div id="default-content">
                <p>Please select a portal from the sidebar to log in.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('#sidebar ul li a');
            const contentDivs = {
                'receptionist-login-form': document.getElementById('receptionist-login-form'),
                // 'patient-login-form': document.getElementById('patient-login-form'),
                'default-content': document.getElementById('default-content'),
            };

            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const targetId = this.dataset.target;

                    // Hide all content divs
                    for (const id in contentDivs) {
                        contentDivs[id].style.display = 'none';
                    }

                    // Show the target content div
                    if (contentDivs[targetId]) {
                        contentDivs[targetId].style.display = 'block';
                    } else {
                        // Show default content if target is not found
                        contentDivs['default-content'].style.display = 'block';
                    }
                });
            });

            // Initially show the default content
            contentDivs['default-content'].style.display = 'block';
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>