
const navToggleBtn = document.querySelector(".navbar-toggle");
const aside = document.querySelector(".aside"); // Assuming this is the main aside element

navToggleBtn.addEventListener("click", asideSectionToggleBtn);
function asideSectionToggleBtn(){
    aside.classList.toggle("open"); // Toggle the "open" class on the aside element
}

// Products Section

const ctxBar = document.getElementById('barchart');

new Chart(ctxBar, {
    type: 'bar',
    data: {
        labels: ['Mon', 'Tues', 'Wed', 'Thurs', 'Fri', 'Sat', 'Sun'],
        datasets: [{
                label: 'Oil',
                data: [3000, 2500, 3000, 5000, 4000, 3000, 3500],
                borderColor: '#F1B356',
                borderWidth: 2
            },
            {
                label: 'Car Accesories',
                data: [2000, 1500, 2000, 3000, 2000, 1500, 2400],
                borderColor: '#A375FF',
                borderWidth: 2
            },
            {
                label: 'Fluid',
                data: [1000, 500, 2000, 2500, 3000, 2000, 1000],
                borderColor: '#7ED957',
                borderWidth: 2
            },
            {
                label: 'Auto Parts',
                data: [900, 5500, 3000, 4500, 6200, 2100, 1600],
                borderColor: '#FF66C4',
                borderWidth: 2
            }
        ]
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

new Chart(ctxPie1, {
    type: 'doughnut',
    data: {
        labels: ['Car Accesories', 'Oil', 'Degreaser', 'Auto Parts', 'Fluid'],
        datasets: [{
            label: 'My First Dataset',
            data: [300, 50, 100, 50, 20],
            backgroundColor: [
                '#FF66C4',
                '#A375FF',
                '#FF914D',
                '#FFBD59',
                '#5271FF'
            ],
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: 'white',
                  }
            }
        }
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