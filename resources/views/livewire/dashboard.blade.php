<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">Dashboard</h3>
    </div>

    <!-- Metrics Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Sales -->
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Sales</h5>
                    <h2 class="card-text">₦{{ number_format($totalSales) }}</h2>
                    <small class="text-light">{{ $totalSalesSign }}{{ $totalSalesPercentage }}% from last month</small>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="col-md-4">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <h2 class="card-text">{{ number_format($totalOrders) }}</h2>
                    <small class="text-light">{{ $totalOrdersSign }}{{ $totalOrdersPercentage }}% from last
                        month</small>
                </div>
            </div>
        </div>

        <!-- Total Products -->
        <div class="col-md-4">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <h2 class="card-text">{{ $totalProducts }}</h2>
                    <small class="text-light">{{ $totalProductsSign }}{{ $totalProductsPercentage }}% from last month</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-3 mb-4">
        <!-- Sales Trend -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    Sales Trend
                </div>
                <div class="card-body">
                    <!-- Placeholder for Livewire / Chart.js -->
                    <canvas id="sales-chart" style="height: 300px;" wire:ignore></canvas>
                    <!-- Chart will be rendered here -->
                </div>
            </div>
        </div>

        <!-- Orders Status -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    Orders Status
                </div>
                <div class="card-body">
                    <!-- Placeholder for Livewire / Chart.js -->
                    <canvas id="orders-chart" style="height: 300px;" wire:ignore></canvas>
                    <!-- Chart will be rendered here -->
                </div>
            </div>
        </div>
    </div>


    <!-- Recent Activity -->
    <div class="row g-3 mb-4">
        <!-- Recent Products -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    Recent Products
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Variations</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentProducts as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $product['name'] }}</td>
                                    <td>{{ $product['product_variations_count'] }}</td>
                                    <td></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No recent products found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    Recent Orders
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td>{{ $order['tracking_id'] }}</td>
                                    <td>{{ $order['quantity'] }}</td>
                                    <td>
                                        @if(in_array($order['latest_status']['status'], ['Delivered']))
                                            <span class="badge bg-success">Delivered</span>
                                        @elseif(in_array($order['latest_status']['status'], ['Processing', 'Order Confimed', 'Shipped', 'Out for Delivery']))
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif(in_array($order['latest_status']['status'], ['Order Declined', 'Order Cancelled']))
                                            <span class="badge bg-danger">Cancelled</span>
                                        @else
                                            <span
                                                class="badge bg-secondary">{{ ucfirst($order['latest_status']['status']) }}</span>
                                        @endif
                                    </td>
                                    <td>₦{{ number_format($order['total'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No recent orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Prepare the data from Blade
            const ordersStatus = @json($ordersStatus);

            const labels = ordersStatus.map(item => item.status);
            const data = ordersStatus.map(item => item.total);

            const ctx = document.getElementById('orders-chart').getContext('2d');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Orders by Status',
                        data: data,
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.7)',   // Delivered
                            'rgba(255, 206, 86, 0.7)',   // Pending
                            'rgba(255, 99, 132, 0.7)',   // Cancelled
                            'rgba(54, 162, 235, 0.7)'    // Others
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const salesTrend = @json($salesTrend);

            const labels = salesTrend.map(item => item.date);
            const data = salesTrend.map(item => parseFloat(item.total));

            const ctx = document.getElementById('sales-chart').getContext('2d');

            if (window.salesChart) {
                window.salesChart.destroy();
            }

            window.salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales',
                        data: data,
                        fill: true,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const currency = salesTrend[context.dataIndex].currency || '';
                                    return currency + Number(context.raw).toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return '₦' + Number(value).toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</div>
