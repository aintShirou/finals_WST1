
const navToggleBtn = document.querySelector(".navbar-toggle");
const aside = document.querySelector(".aside"); // Assuming this is the main aside element

navToggleBtn.addEventListener("click", asideSectionToggleBtn);
function asideSectionToggleBtn(){
    aside.classList.toggle("open"); // Toggle the "open" class on the aside element
}

// Sales Section

const ctxLine1 = document.getElementById('linechart');

$.ajax({
    url: 'get_chart_data.php', // URL of your server-side script
    type: 'GET',
    dataType: 'json',
    success: function(data) {
        new Chart(ctxLine1, {
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
                            color: 'white' // set legend text color to white
                        }
                    }
                }
            }
        });
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