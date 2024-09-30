<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

</head>

<body>
    <div class="container">
        <h2 class="mb-4">Customers</h2>

        <!-- Add Customer Form -->
        <form id="addCustomerForm">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" id="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" id="email" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone</label>
                <input type="text" name="phone_number" class="form-control" id="phone" required>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Add Customer</button>
        </form>

        <!-- Customers Table -->
        <table class="table mt-4" id="customersTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</body>
<script>
    $(document).ready(function() {
        // Fetch customers on page load
        fetchCustomers();

        // Add customer
        $('#addCustomerForm').on('submit', function(e) {
            e.preventDefault();
            // var token = getCookie.get('jwt_token');
            $.ajax({
                url: "http://localhost:8000/api/customers",
                type: "POST",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Cookie", '')
                },
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        $('#addCustomerForm')[0].reset(); // Reset form
                        fetchCustomers(); // Reload customers list
                    }
                },
                error: function(error) {
                    alert("Error adding customer.");
                }
            });
        });

        // Fetch customers
        function fetchCustomers() {
            // var token = getCookie.get('jwt_token');
            // console.log(token);

            $.ajax({
                url: "http://localhost:8000/api/customers",
                type: "GET",
                // headers: {
                //     "Accept": "application/json",
                //     "Authorization": "Bearer " + token
                // },
                success: function(customers) {
                    let tableBody = $('#customersTable tbody');
                    tableBody.empty();

                    customers.forEach(function(customer) {
                        tableBody.append(`
                            <tr data-id="${customer.id}">
                                <td>${customer.name}</td>
                                <td>${customer.email}</td>
                                <td>${customer.phone}</td>
                                <td>
                                    <button class="btn btn-info editCustomer" data-id="${customer.id}">Edit</button>
                                    <button class="btn btn-danger deleteCustomer" data-id="${customer.id}">Delete</button>
                                </td>
                            </tr>
                        `);
                    });
                }
            });
        }

        // Delete customer
        $(document).on('click', '.deleteCustomer', function() {
            let customerId = $(this).data('id');

            $.ajax({
                url: `/customers/${customerId}`,
                type: "DELETE",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert(response.message);
                    fetchCustomers(); // Reload customers list
                },
                error: function(error) {
                    alert("Error deleting customer.");
                }
            });
        });

        // Edit customer (similar process)
        $(document).on('click', '.editCustomer', function() {
            let customerId = $(this).data('id');
            // Fetch and show edit form (this can be implemented similarly to add)
            // You would populate the form with the selected customer's data.
        });
    });
</script>

</html>
