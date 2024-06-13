
const navToggleBtn = document.querySelector(".navbar-toggle");
const aside = document.querySelector(".aside"); // Assuming this is the main aside element

navToggleBtn.addEventListener("click", asideSectionToggleBtn);
function asideSectionToggleBtn(){
    aside.classList.toggle("open"); // Toggle the "open" class on the aside element
}

// Sales Section

const ctxLine1 = document.getElementById('linechart');
let chart; // Define chart variable outside AJAX success callback

function filterChart(dateInput) {
    const selectedDate = new Date(dateInput.value);
    const year = selectedDate.getFullYear();
    const month = selectedDate.getMonth(); // Months are zero-based (0-11)
    
    const startDate = new Date(year, month, 1);
    const endDate = new Date(year, month + 1, 0); // Last day of the month

    const startDateString = startDate.toISOString().split('T')[0]; // Format to YYYY-MM-DD
    const endDateString = endDate.toISOString().split('T')[0]; // Format to YYYY-MM-DD

    // Check if chart is defined before accessing its options
    if (chart) {
        chart.options.scales.x.min = startDateString;
        chart.options.scales.x.max = endDateString;
        chart.update(); // Re-render the chart after updating the options
    } else {
        console.error('Chart is not initialized yet.');
    }
}

$.ajax({
    url: 'get_chart_data.php', // URL of your server-side script
    type: 'GET',
    dataType: 'json',
    success: function(data) {
        chart = new Chart(ctxLine1, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: data.datasets
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: 'white'
                        }
                    }
                },
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day'
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Call filterChart function with appropriate input
        filterChart(document.getElementById('paymentdate')); // Pass your date input element here
    },
    error: function(jqXHR, textStatus, errorThrown) {
        console.error('AJAX error:', textStatus, errorThrown);
    }
});


const ctxPie1 = document.getElementById('pieschart');

$.ajax({
    url: 'get_pie_chart_data.php', // URL of your server-side script for pie chart data
    type: 'GET',
    dataType: 'json',
    success: function(data) {
        new Chart(ctxPie1, {
            type: 'doughnut',
            data: {
                labels: data.labels, // Use labels from server
                datasets: data.datasets // Use datasets from server
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: 'white' // Set legend text color to white
                        }
                    }
                }
            }
        });
    },
    error: function(jqXHR, textStatus, errorThrown) {
        console.error('AJAX error for pie chart:', textStatus, errorThrown);
    }
});
// Preview for the picture

function previewImage() {
    var preview = document.getElementById('preview');
    var fileInput = document.getElementById('productImage');
    var file = fileInput.files[0];
    var reader = new FileReader();
  
    reader.onloadend = function () {
      preview.src = reader.result;
      preview.style.display = 'block'; // Display the image
    }
  
    if (file) {
      reader.readAsDataURL(file); // Read the file as a data URL
    } else {
      preview.src = ''; // Clear the preview if no file is selected
    }
  }

//   Transaction Filter


function getMondayOfWeek(year, week) {
    const firstDayOfYear = new Date(year, 0, 1);
    const dayOfWeek = firstDayOfYear.getDay();
    const offset = dayOfWeek <= 4 ? dayOfWeek - 1 : dayOfWeek - 8;
    const mondayOfWeek = new Date(firstDayOfYear.setDate(firstDayOfYear.getDate() - offset + (week - 1) * 7));
    return mondayOfWeek;
}

function filterTransactions() {
    const monthInput = document.getElementById('filterMonth').value;
    const weekInput = document.getElementById('filterWeek').value;
    const table = document.getElementById('transactionTable');
    
    if (!table) {
        console.error('Table not found');
        return;
    }

    const tbody = table.getElementsByTagName('tbody')[0];
    
    if (!tbody) {
        console.error('Table body not found');
        return;
    }

    const rows = tbody.getElementsByTagName('tr');

    let filterStartDate, filterEndDate;

    if (monthInput) {
        const [year, month] = monthInput.split('-');
        filterStartDate = new Date(year, month - 1, 1);
        filterEndDate = new Date(year, month, 0); // Last day of the month
    } else if (weekInput) {
        const [year, week] = weekInput.split('-W');
        filterStartDate = getMondayOfWeek(year, parseInt(week));
        filterEndDate = new Date(filterStartDate);
        filterEndDate.setDate(filterStartDate.getDate() + 6); // Last day of the week (Sunday)
    }

    for (let i = 0; i < rows.length; i++) {
        const dateCell = rows[i].getElementsByTagName('td')[3];
        
        if (!dateCell) {
            console.error('Date cell not found for row:', rows[i]);
            continue;
        }

        const transactionDate = new Date(dateCell.textContent);

        if (filterStartDate && filterEndDate) {
            if (transactionDate >= filterStartDate && transactionDate <= filterEndDate) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        } else {
            rows[i].style.display = ''; // Show all rows if no filter is applied
        }
    }
}


// RecentOrder Filter

// Function to get the first Monday of a specified week in a specified year
function getMondayOfWeek(year, week) {
    const firstDayOfYear = new Date(year, 0, 1);
    const dayOfWeek = firstDayOfYear.getDay();
    const offset = dayOfWeek <= 4 ? dayOfWeek - 1 : dayOfWeek - 8;
    const firstMonday = new Date(firstDayOfYear.setDate(firstDayOfYear.getDate() - offset + (week - 1) * 7));
    return firstMonday;
}

// Function to filter the transactions based on the selected month or week
function filterTransaction() {
    const monthFilter = document.getElementById('monthsFilter').value;
    const weekFilter = document.getElementById('weeksFilter').value;
    const rows = document.querySelectorAll('#recentOrderTable tbody tr');

    let filterStartDate, filterEndDate;

    if (monthFilter) {
        const [year, month] = monthFilter.split('-');
        filterStartDate = new Date(year, month - 1, 1);
        filterEndDate = new Date(year, month, 0); // Last day of the month
    } else if (weekFilter) {
        const [year, week] = weekFilter.split('-W');
        filterStartDate = getMondayOfWeek(year, parseInt(week));
        filterEndDate = new Date(filterStartDate);
        filterEndDate.setDate(filterStartDate.getDate() + 6); // Last day of the week (Sunday)
    }

    rows.forEach(row => {
        const orderDate = new Date(row.getAttribute('data-order-date'));
        if (filterStartDate && filterEndDate) {
            if (orderDate >= filterStartDate && orderDate <= filterEndDate) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        } else {
            row.style.display = ''; // Show all rows if no filter is applied
        }
    });
}

// Function to export the orders to Excel
function exportOrderToExcel() {
    // Implement the logic to export the table to Excel
    console.log("Export to Excel function triggered");
}